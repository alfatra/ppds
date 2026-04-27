<?php

namespace App\Http\Controllers;

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
    public function index()
    {
        $user = Auth::user();
        $query = SoapLog::query();

        // Jika user BUKAN admin atau superadmin, filter data berdasarkan ID mereka
        if (!$user->isSuperAdmin() && !$user->isAdmin()) {
            $query->where('created_by', $user->id);
        }

        // Ambil data, urutkan dari yang terbaru, dan gunakan pagination
       $logs = $query->with('patient', 'doctor', 'creator', 'diagnosis')
              ->latest()
              ->paginate(15);

        // Asumsi view Anda ada di resources/views/soap_logs/index.blade.php
        return view('soap_logs.index', compact('logs'));
    }

    /**
     * Menampilkan form untuk membuat Laporan SOAP baru.
     */
    public function create()
    {
        $dokters = $this->getDokterListFromApi();
        
        return view('soap_logs.create', compact('dokters'));
    }

    /**
     * Menyimpan Laporan SOAP baru ke database.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'patient_id' => 'required|integer', // Sesuaikan dengan validasi Anda
            'visit_date' => 'required|date',
            'nama_dpjp' => 'required|string|max:255',
            'subjective' => 'required|string',
            'objective' => 'required|string',
            'assessment' => 'required|string',
            'plan' => 'required|string',
            'diagnosa_id' => 'nullable|string',
        ]);

        // Secara otomatis mengisi ID user yang membuat laporan
        $validatedData['created_by'] = Auth::id();
        // Asumsi dokter yang mengisi adalah user yang login
        $validatedData['doctor_id'] = Auth::id();

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

        // Hanya admin/superadmin atau pemilik data yang bisa melihat
        if (!$user->isSuperAdmin() && !$user->isAdmin() && $log->created_by != $user->id) {
            // Jika tidak berhak, kembalikan error 403 (Forbidden)
            abort(403, 'ANDA TIDAK MEMILIKI AKSES UNTUK MELIHAT DATA INI.');
        }

        // Eager load relasi yang ada di DB lokal (relasi 'diagnosis' tidak dipakai lagi)
        $log->load('creator', 'patient', 'doctor');

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

        // Cache hasil selama 24 jam untuk kode yang sama
        return Cache::remember('diagnosis_name_' . $code, now()->addHours(24), function () use ($code) {
            $apiUrl = config('apis.diagnosa.url');
            $consumerId = config('apis.diagnosa.consumer_id');
            $consumerPassword = config('apis.diagnosa.consumer_password');

            if (!$consumerId || !$consumerPassword) {
                Log::error('Kredensial API Diagnosa tidak ditemukan.');
                return null;
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

                if ($response->successful() && isset($response->json()['Data'])) {
                    $diagnoses = json_decode($response->json()['Data'], true) ?? [];
                    $diagnosisMap = collect($diagnoses)->keyBy('kode');
                    return $diagnosisMap->get($code)['nama'] ?? null;
                }
                return null;
            } catch (\Exception $e) {
                Log::error('Error koneksi ke API Diagnosa saat mengambil nama: ' . $e->getMessage());
                return null;
            }
        });
    }

    /**
     * Menampilkan form untuk mengedit Laporan SOAP.
     */
    public function edit(SoapLog $log)
    {
        $user = Auth::user();
        // Hanya admin/superadmin atau pemilik data yang bisa mengedit
        if (!$user->isSuperAdmin() && !$user->isAdmin() && $log->created_by != $user->id) {
            abort(403, 'ANDA TIDAK MEMILIKI AKSES UNTUK MENGEDIT DATA INI.');
        }

        $dokters = $this->getDokterListFromApi();

        return view('soap_logs.edit', compact('log', 'dokters'));
    }

    /**
     * Memperbarui Laporan SOAP di database.
     */
    public function update(Request $request, SoapLog $log)
    {
        $user = Auth::user();
        // Cek otorisasi sebelum validasi
        if (!$user->isSuperAdmin() && !$user->isAdmin() && $log->created_by != $user->id) {
            abort(403, 'ANDA TIDAK MEMILIKI AKSES UNTUK MEMPERBARUI DATA INI.');
        }

        $validatedData = $request->validate([
            'patient_id' => 'required|integer',
            'visit_date' => 'required|date',
            'nama_dpjp' => 'required|string|max:255',
            'subjective' => 'required|string',
            'objective' => 'required|string',
            'assessment' => 'required|string',
            'plan' => 'required|string',
            'diagnosa_id' => 'nullable|string',
        ]);

        // Secara otomatis mengisi ID user yang mengupdate
        $validatedData['updated_by'] = Auth::id();

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