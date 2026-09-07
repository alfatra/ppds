<?php

namespace App\Http\Controllers;

use App\Models\Ppds;
use App\Models\SoapLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SoapLogController extends Controller
{
    /**
     * Menampilkan daftar Laporan SOAP.
     * Admin/Superadmin melihat semua, user lain hanya melihat miliknya.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = SoapLog::query();

        // Jika user BUKAN admin atau superadmin, filter data berdasarkan ID mereka
        if (!$user->isSuperAdmin() && !$user->isAdmin()) {
            $query->where('created_by', $user->id);
        }

        // Fitur Pencarian
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_dpjp', 'like', '%' . $search . '%')
                  ->orWhere('subjective', 'like', '%' . $search . '%')
                  ->orWhere('objective', 'like', '%' . $search . '%')
                  ->orWhere('assessment', 'like', '%' . $search . '%')
                  ->orWhere('plan', 'like', '%' . $search . '%')
                  ->orWhereHas('patient', function ($patientQuery) use ($search) {
                      $patientQuery->where('name', 'like', '%' . $search . '%');
                  })
                  ->orWhere('patient_name_manual', 'like', '%' . $search . '%')
                  ->orWhere('patient_registration_no', 'like', '%' . $search . '%')
                  ->orWhereHas('doctor', function ($doctorQuery) use ($search) {
                      $doctorQuery->where('name', 'like', '%' . $search . '%');
                  })
                  ->orWhereHas('creator', function ($creatorQuery) use ($search) {
                      $creatorQuery->where('name', 'like', '%' . $search . '%');
                  });
            });
        }

        // Ambil data, urutkan dari yang terbaru, dan gunakan pagination
       $logs = $query->with('patient', 'doctor', 'creator', 'diagnosis')
              ->latest()
              ->orderBy('id', 'desc')
              ->paginate(15)->withQueryString();

        // Menyisipkan nama diagnosa dari API ke dalam setiap item log
        $diagnosisMap = $this->getDiagnosisMapFromApi();
        foreach ($logs as $log) {
            if ($log->diagnosa_id) {
                $diagData = $diagnosisMap->get($log->diagnosa_id);
                $log->api_diagnosis_name = $diagData ? $diagData['DiagnoseName'] : 'Tidak ditemukan';
            } else {
                $log->api_diagnosis_name = '-';
            }

            // Menentukan nama pasien yang akan ditampilkan
            $log->display_patient_name = $log->patient_name_manual ?? ($log->patient ? $log->patient->name : $log->patient_id);
        }

        // Asumsi view Anda ada di resources/views/soap_logs/index.blade.php
        return view('soap_logs.index', compact('logs'));
    }

    /**
     * Menampilkan form untuk membuat Laporan SOAP baru.
     */
    public function create()
    {
        $user = Auth::user();
        $ppds = Ppds::where('email', $user->email)->first();

        // Cek apakah profil PPDS sudah lengkap. Asumsi 'lengkap' berarti telepon dan alamat sudah diisi.
        $isProfileIncomplete = !$ppds || !$ppds->telepon || !$ppds->alamat;

        // Pengecekan hanya berlaku untuk user biasa, bukan admin/superadmin
        if ($isProfileIncomplete && !$user->isSuperAdmin() && !$user->isAdmin()) {
            $alert = [
                'title' => 'Profil Belum Lengkap',
                'text' => 'Silakan lengkapi data profil Anda untuk dapat membuat Laporan SOAP baru.',
                'icon' => 'warning',
                'confirmButtonText' => 'Lengkapi Profil',
                'cancelButtonText' => 'Batal',
                'showCancelButton' => true,
                'redirectUrl' => route('profile.edit') // URL untuk redirect jika tombol konfirmasi diklik
            ];

            return redirect()->route('ppds.soap-logs.index')->with('sweet_alert_redirect', $alert);
        }

        $dokters = $this->getDokterListFromApi();
        
        return view('soap_logs.create', compact('dokters'));
    }

    /**
     * Menyimpan Laporan SOAP baru ke database.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $ppds = Ppds::where('email', $user->email)->first();
        $isProfileIncomplete = !$ppds || !$ppds->telepon || !$ppds->alamat;

        // Tambahkan validasi di sisi server untuk keamanan jika user mencoba mengirim data secara langsung
        // Pengecekan hanya berlaku untuk user biasa, bukan admin/superadmin
        if ($isProfileIncomplete && !$user->isSuperAdmin() && !$user->isAdmin()) {
            abort(403, 'AKSI DITOLAK. Profil Anda belum lengkap. Silakan lengkapi profil untuk melanjutkan.');
        }


        $validatedData = $request->validate([
            'patient_id' => 'required|string', // Accept both integer ID and string
            'patient_name_manual' => 'nullable|string',
            'patient_registration_no' => 'nullable|string',
            'medical_record_no' => 'nullable|string',
            'visit_date' => 'required|date',
            'nama_dpjp' => 'required|string|max:255',
            'api_dpjp_id' => 'nullable|string',
            'subjective' => 'required|string',
            'objective' => 'required|string',
            'assessment' => 'required|string',
            'plan' => 'required|string',
            'ttv_td' => 'nullable|string',
            'ttv_hr' => 'nullable|string',
            'ttv_rr' => 'nullable|string',
            'ttv_temp' => 'nullable|string',
            'ttv_spo2' => 'nullable|string',
            'ttv_vas' => 'nullable|string',
            'diagnosa_id' => 'nullable|string',
            'foto_visite' => 'nullable|array',
            'foto_visite.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Process patient_id - could be integer or string (registration number)
        $patientId = $validatedData['patient_id'];
        $patientNameManual = $validatedData['patient_name_manual'] ?? null;
        
        // If manual patient data provided, store as-is
        // Otherwise try to convert to integer for API-sourced patients
        if (!$patientNameManual && is_numeric($patientId)) {
            $patientId = (int)$patientId;
        }
        
        $validatedData['patient_id'] = $patientId;
        
        // Store patient name if from manual entry (optional: could create a patient record)
        if ($patientNameManual) {
            Log::info('Manual patient entry', [
                'patient_id' => $patientId,
                'patient_name' => $patientNameManual,
                'registration_no' => $validatedData['patient_registration_no']
            ]);
        }

        // Secara otomatis mengisi ID user yang membuat laporan
        $validatedData['created_by'] = Auth::id();
        // Asumsi dokter yang mengisi adalah user yang login
        $validatedData['doctor_id'] = Auth::id();
        // Just-In-Time (JIT) Provisioning: Cari atau buat akun DPJP (Konsulen) otomatis
        $namaDpjp = $validatedData['nama_dpjp'];
        $apiDpjpId = $request->input('api_dpjp_id'); // Ambil kode dokter
        
        // Gunakan kode dokter sebagai email (atau slug nama jika kode tidak ada)
        $emailPrefix = $apiDpjpId ? strtolower(trim($apiDpjpId)) : \Illuminate\Support\Str::slug($namaDpjp) . '_' . uniqid();
        
        // Coba cari berdasarkan nama yang persis sama terlebih dahulu untuk menghindari duplikasi
        $dpjp = \App\Models\User::where('role', \App\Models\User::ROLE_KONSULEN)
                    ->where('name', $namaDpjp)
                    ->first();

        if (!$dpjp) {
            $dpjp = \App\Models\User::firstOrCreate(
                ['email' => $emailPrefix . '@rs.local'], // Cari berdasarkan email (kode dokter) agar unik
                [
                    'name' => $namaDpjp,
                    'password' => bcrypt('12345678'), // Password default 12345678 sesuai permintaan
                    'role' => \App\Models\User::ROLE_KONSULEN,
                    'is_active' => true,
                ]
            );

        }

        // Jika user sudah ada tapi namanya mungkin berubah di API, update namanya
        if ($dpjp->name !== $namaDpjp) {
            $dpjp->update(['name' => $namaDpjp]);
        }

        // Set supervisor_id ke ID dari DPJP yang baru saja dipilih/dibuat
        $validatedData['supervisor_id'] = $dpjp->id;

        // Opsi tambahan: Jika Anda ingin DPJP ini JUGA menjadi supervisor utama/default untuk profil PPDS ini seterusnya
        // Anda bisa uncomment baris di bawah ini:
        // $user->update(['supervisor_id' => $dpjp->id]);

        // Handle file upload
        if ($request->hasFile('foto_visite')) {
            $paths = [];
            foreach ($request->file('foto_visite') as $file) {
                $paths[] = $file->store('soap_photos', 'public');
            }
            $validatedData['foto_visite'] = $paths;
        }

        // Handle body diagram base64
        if ($request->has('body_diagram_base64') && !empty($request->body_diagram_base64)) {
            try {
                $image_parts = explode(";base64,", $request->body_diagram_base64);
                if (count($image_parts) == 2) {
                    $image_base64 = base64_decode($image_parts[1]);
                    $fileName = 'diagram_' . uniqid() . '.png';
                    $filePath = 'soap_body_diagrams/' . $fileName;
                    \Illuminate\Support\Facades\Storage::disk('public')->put($filePath, $image_base64);
                    $validatedData['body_diagram'] = $filePath;
                }
            } catch (\Exception $e) {
                Log::error('Error saving body diagram: ' . $e->getMessage());
            }
        }

        SoapLog::create($validatedData);

        return redirect()->route('ppds.soap-logs.index')
                         ->with('success', 'Laporan SOAP berhasil ditambahkan.');
    }

    /**
     * Menampilkan detail satu Laporan SOAP.
     */
    public function show(SoapLog $log)
    {
        $user = Auth::user();

        // Hanya admin/superadmin, pemilik data, atau supervisor yang bersangkutan yang bisa melihat
        if (!$user->isSuperAdmin() && !$user->isAdmin() && $log->created_by != $user->id && $log->supervisor_id != $user->id) {
            // Jika tidak berhak, kembalikan error 403 (Forbidden)
            abort(403, 'ANDA TIDAK MEMILIKI AKSES UNTUK MELIHAT DATA INI.');
        }

        // Eager load relasi yang ada di DB lokal (relasi 'diagnosis' tidak dipakai lagi)
        $log->load('creator', 'patient', 'doctor');

        // Menentukan nama pasien yang akan ditampilkan untuk PDF
        $log->display_patient_name = $log->patient_name_manual ?? ($log->patient ? $log->patient->name : $log->patient_id);
            
        // Ambil nama diagnosis dari API (dengan caching)
        $diagnosisName = $this->getDiagnosisNameFromApi($log->diagnosa_id);

        // Buat PDF dari view 'soap_logs.pdf'
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('soap_logs.pdf', [
            'log' => $log,
            'diagnosisName' => $diagnosisName,
        ]);

        // Tampilkan PDF di browser (inline) agar bisa langsung di-print
        return $pdf->stream('laporan-soap-' . $log->id . '.pdf');
    }

    /**
     * Mengambil nama diagnosis dari API eksternal berdasarkan kode.
     * Hasilnya di-cache untuk mengurangi beban API.
     */
    private function getDiagnosisNameFromApi($code)
    {
        if (!$code) {
            return null;
        }

        $map = $this->getDiagnosisMapFromApi();
        return $map->get($code)['DiagnoseName'] ?? null;
    }

    /**
     * Mengambil seluruh daftar diagnosis dari API dan menyimpannya di cache.
     * Mencegah pemanggilan API berulang kali untuk setiap baris data di halaman index.
     */
    private function getDiagnosisMapFromApi()
    {
        return Cache::remember('diagnosis_full_map', now()->addHours(24), function () {
            $apiUrl = config('apis.diagnosa.url') ?: env('API_DIAGNOSA_URL', 'http://192.168.10.33/medinfrasapi/rssm/api/diagnose/list');
            $consumerId = config('apis.diagnosa.consumer_id') ?: env('API_CONSUMER_ID');
            $consumerPassword = config('apis.diagnosa.consumer_password') ?: env('API_CONSUMER_PASSWORD');

            if (!$consumerId || !$consumerPassword) {
                Log::error('Kredensial API Diagnosa tidak ditemukan untuk mapping.');
                return collect();
            }

            try {
                $timestamp = time();
                $dataToSign = $timestamp . $consumerId;
                $signature = base64_encode(hash_hmac('sha256', $dataToSign, $consumerPassword, true));

                $response = Http::timeout(30)->withHeaders([
                    'X-cons-id' => $consumerId,
                    'X-timestamp' => $timestamp,
                    'X-signature' => $signature
                ])->get($apiUrl);

                $responseData = $response->json();
                if ($response->successful() && isset($responseData['Data'])) {
                    $dataField = $responseData['Data'];
                    $diagnoses = is_string($dataField) ? json_decode($dataField, true) : $dataField;
                    return collect($diagnoses ?? [])->keyBy('DiagnoseID');
                }
                return collect();
            } catch (\Exception $e) {
                Log::error('Error koneksi ke API Diagnosa saat mapping data: ' . $e->getMessage());
                return collect();
            }
        });
    }

    /**
     * Menampilkan form untuk mengedit Laporan SOAP.
     */
    public function edit(SoapLog $log)
    {
        $user = Auth::user();
        // Hanya admin/superadmin, pemilik data, atau supervisor yang bersangkutan yang bisa mengedit
        if (!$user->isSuperAdmin() && !$user->isAdmin() && $log->created_by != $user->id && $log->supervisor_id != $user->id) {
            abort(403, 'ANDA TIDAK MEMILIKI AKSES UNTUK MENGEDIT DATA INI.');
        }

        if ($log->approval_status === 'approved' && !$user->isSuperAdmin() && !$user->isAdmin()) {
            abort(403, 'Tidak dapat mengedit data yang sudah disetujui.');
        }

        $dokters = $this->getDokterListFromApi();

        return view('soap_logs.edit', compact('log', 'dokters'));
    }

    /**
     * 
     * Memperbarui Laporan SOAP di database.
     */
    public function update(Request $request, SoapLog $log)
    {
        $user = Auth::user();
        // Cek otorisasi sebelum validasi (Admin, Pemilik, atau Supervisor)
        if (!$user->isSuperAdmin() && !$user->isAdmin() && $log->created_by != $user->id && $log->supervisor_id != $user->id) {
            abort(403, 'ANDA TIDAK MEMILIKI AKSES UNTUK MEMPERBARUI DATA INI.');
        }

        if ($log->approval_status === 'approved' && !$user->isSuperAdmin() && !$user->isAdmin()) {
            abort(403, 'Tidak dapat mengedit data yang sudah disetujui.');
        }

        $validatedData = $request->validate([
            'patient_id' => 'required|string',
            'patient_name_manual' => 'nullable|string',
            'patient_registration_no' => 'nullable|string',
            'medical_record_no' => 'nullable|string',
            'visit_date' => 'required|date',
            'nama_dpjp' => 'required|string|max:255',
            'api_dpjp_id' => 'nullable|string',
            'subjective' => 'required|string',
            'objective' => 'required|string',
            'assessment' => 'required|string',
            'plan' => 'required|string',
            'ttv_td' => 'nullable|string',
            'ttv_hr' => 'nullable|string',
            'ttv_rr' => 'nullable|string',
            'ttv_temp' => 'nullable|string',
            'ttv_spo2' => 'nullable|string',
            'ttv_vas' => 'nullable|string',
            'diagnosa_id' => 'nullable|string',
            'foto_visite' => 'nullable|array',
            'foto_visite.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Secara otomatis mengisi ID user yang mengupdate
        $validatedData['updated_by'] = Auth::id();
        // Just-In-Time (JIT) Provisioning: Cari atau buat akun DPJP (Konsulen) otomatis
        $namaDpjp = $validatedData['nama_dpjp'];
        $apiDpjpId = $request->input('api_dpjp_id'); // Ambil kode dokter
        
        // Gunakan kode dokter sebagai email (atau slug nama jika kode tidak ada)
        $emailPrefix = $apiDpjpId ? strtolower(trim($apiDpjpId)) : \Illuminate\Support\Str::slug($namaDpjp) . '_' . uniqid();
        
        // Coba cari berdasarkan nama yang persis sama terlebih dahulu untuk menghindari duplikasi
        $dpjp = \App\Models\User::where('role', \App\Models\User::ROLE_KONSULEN)
                    ->where('name', $namaDpjp)
                    ->first();

        if (!$dpjp) {
            $dpjp = \App\Models\User::firstOrCreate(
                ['email' => $emailPrefix . '@rs.local'], // Cari berdasarkan email (kode dokter) agar unik
                [
                    'name' => $namaDpjp,
                    'password' => bcrypt('12345678'), // Password default 12345678 sesuai permintaan
                    'role' => \App\Models\User::ROLE_KONSULEN,
                    'is_active' => true,
                ]
            );
        }

        // Jika user sudah ada tapi namanya mungkin berubah di API, update namanya
        if ($dpjp->name !== $namaDpjp) {
            $dpjp->update(['name' => $namaDpjp]);
        }

        // Set otomatis supervisor ke DPJP yang dipilih dari API
        $validatedData['supervisor_id'] = $dpjp->id;

        // Handle file upload
        if ($request->hasFile('foto_visite')) {
            // Ambil foto lama jika ada
            $paths = is_array($log->foto_visite) ? $log->foto_visite : [];
            if (!empty($log->foto_visite) && !is_array($log->foto_visite)) {
                $paths = [$log->foto_visite];
            }
            
            // Tambahkan foto baru
            foreach ($request->file('foto_visite') as $file) {
                $paths[] = $file->store('soap_photos', 'public');
            }
            $validatedData['foto_visite'] = $paths;
        }

        // Handle body diagram base64
        if ($request->has('body_diagram_base64') && !empty($request->body_diagram_base64)) {
            try {
                $image_parts = explode(";base64,", $request->body_diagram_base64);
                if (count($image_parts) == 2) {
                    $image_base64 = base64_decode($image_parts[1]);
                    $fileName = 'diagram_' . uniqid() . '.png';
                    $filePath = 'soap_body_diagrams/' . $fileName;
                    \Illuminate\Support\Facades\Storage::disk('public')->put($filePath, $image_base64);
                    $validatedData['body_diagram'] = $filePath;
                }
            } catch (\Exception $e) {
                Log::error('Error saving body diagram: ' . $e->getMessage());
            }
        }

        if ($log->approval_status === 'rejected') {
            $validatedData['approval_status'] = 'pending';
            $validatedData['supervisor_note'] = null;
        }

        $log->update($validatedData);

        return redirect()->route('ppds.soap-logs.index')
                         ->with('success', 'Laporan SOAP berhasil diperbarui.');
    }

    /**
     * Menghapus Laporan SOAP dari database.
     */
    public function destroy(SoapLog $log)
    {
        $user = Auth::user();
        // Hanya admin/superadmin atau pemilik data yang bisa menghapus
        if (!$user->isSuperAdmin() && !$user->isAdmin() && $log->created_by != $user->id) {
            abort(403, 'ANDA TIDAK MEMILIKI AKSES UNTUK MENGHAPUS DATA INI.');
        }

        if ($log->approval_status === 'approved' && !$user->isSuperAdmin() && !$user->isAdmin()) {
            abort(403, 'Tidak dapat menghapus data yang sudah disetujui.');
        }

        $log->delete();

        return redirect()->route('ppds.soap-logs.index')
                         ->with('success', 'Laporan SOAP berhasil dihapus.');
    }

    /**
     * Mengambil daftar dokter/paramedis dari API eksternal.
     * Hasilnya di-cache selama 24 jam agar loading halaman form lebih cepat.
     */
    private function getDokterListFromApi()
    {
        return Cache::remember('paramedic_list', now()->addHours(24), function () {
            $apiUrl = env('API_PARAMEDIC_URL', 'http://192.168.10.33/medinfrasapi/rssm/api/Paramedic/base/list');
            $consumerId = env('API_CONSUMER_ID');
            $consumerPassword = env('API_CONSUMER_PASSWORD');

            try {
                // Tambahkan HMAC authentication jika credential tersedia
                $headers = [];
                if ($consumerId && $consumerPassword) {
                    $timestamp = time();
                    $dataToSign = $timestamp . $consumerId;
                    $signature = base64_encode(hash_hmac('sha256', $dataToSign, $consumerPassword, true));

                    $headers = [
                        'X-cons-id' => $consumerId,
                        'X-timestamp' => $timestamp,
                        'X-signature' => $signature
                    ];
                }

                $response = Http::timeout(30)->withHeaders($headers)->get($apiUrl);

                if ($response->successful()) {
                    $data = $response->json();
                    
                    // Menyesuaikan dengan format return API Anda. 
                    // Jika data terbungkus di dalam key 'Data' (seperti API diagnosa), kita ekstrak:
                    if (isset($data['Data'])) {
                        return is_string($data['Data']) ? json_decode($data['Data'], true) : $data['Data'];
                    }
                    
                    return $data; // Kembalikan langsung jika response sudah berbentuk array daftar dokter
                }
                return [];
            } catch (\Exception $e) {
                Log::error('Error koneksi ke API Paramedic: ' . $e->getMessage());
                return [];
            }
        });
    }
}