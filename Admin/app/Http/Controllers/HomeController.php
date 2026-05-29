<?php

namespace App\Http\Controllers;

use App\Models\SoapLog;
use App\Models\Diagnosis;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        // Jika request path adalah '/', 'dashboard', atau 'index', tampilkan dashboard
        if (in_array($request->path(), ['/', 'dashboard', 'index'], true)) {
            return $this->showDashboard();
        }

        if (view()->exists($request->path())) {
            return view($request->path());
        }
        return abort(404);
    }

    private function showDashboard()
    {
        $user = Auth::user();
        $isAdmin = $user->isSuperAdmin() || $user->isAdmin();

        $query = SoapLog::query();
        if (!$isAdmin) {
            $query->where('created_by', $user->id);
        }

        $totalSoap = (clone $query)->count();
        $soapHariIni = (clone $query)->whereDate('created_at', today())->count();
        $soapBulanIni = (clone $query)->whereMonth('created_at', now()->month)
                                       ->whereYear('created_at', now()->year)
                                       ->count();

        $weeklyData = $this->getWeeklySoapTrend($query);
        $totalPatients = (clone $query)->distinct('patient_id')->count('patient_id');
        $diagnosisBreakdown = $this->getDiagnosisBreakdown($query);

        $doctorActivity = [];
        if ($isAdmin) {
            $doctorActivity = $this->getDoctorActivity();
        }

        $weeklyTarget = 5;
        $weeklyProgress = $soapBulanIni > 0 ? min(100, ($soapBulanIni / ($weeklyTarget * 4)) * 100) : 0;

        $recentSoaps = (clone $query)->with(['patient', 'doctor'])
                              ->latest()
                              ->take(5)
                              ->get();

        return view('index', compact(
            'user', 'isAdmin', 'totalSoap', 'soapHariIni', 'soapBulanIni',
            'totalPatients', 'weeklyData', 'diagnosisBreakdown', 'doctorActivity',
            'weeklyProgress', 'recentSoaps'
        ));
    }

    private function getWeeklySoapTrend($baseQuery)
    {
        $days = [];
        $counts = [];
        $userCounts = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->startOfDay();
            $days[] = $date->format('d M');

            $count = (clone $baseQuery)->whereDate('created_at', $date)->count();
            $counts[] = $count;

            $uniqueUsers = (clone $baseQuery)->whereDate('created_at', $date)
                                            ->distinct('created_by')
                                            ->count('created_by');
            $userCounts[] = $uniqueUsers;
        }

        return [
            'days' => $days,
            'counts' => $counts,
            'user_counts' => $userCounts
        ];
    }

    private function getDiagnosisBreakdown($baseQuery)
    {
        $diagnosisCounts = (clone $baseQuery)
            ->selectRaw('soap_logs.diagnosa_id, COUNT(*) AS count')
            ->whereNotNull('soap_logs.diagnosa_id')
            ->where('soap_logs.diagnosa_id', '<>', '')
            ->groupBy('soap_logs.diagnosa_id')
            ->orderByRaw('COUNT(*) DESC')
            ->limit(5)
            ->get();

        // Ambil diagnosis data dari API
        $diagnosisMap = $this->getDiagnosisMapFromApi();

        $codes = [];
        $names = [];
        $counts = [];

        foreach ($diagnosisCounts as $item) {
            $codes[] = $item->diagnosa_id;
            $diagData = $diagnosisMap->get($item->diagnosa_id);
            $names[] = $diagData ? $diagData['DiagnoseName'] : $item->diagnosa_id;
            $counts[] = $item->count;
        }

        \Log::debug('[Home] Diagnosis Breakdown', [
            'count' => count($codes),
            'codes' => $codes,
            'names' => $names
        ]);

        return [
            'codes' => $codes,
            'names' => $names,
            'counts' => $counts,
        ];
    }

    /**
     * Mengambil seluruh daftar diagnosis dari API dan menyimpannya di cache.
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

    private function getDoctorActivity()
    {
        $doctors = User::where('role', 'user')
            ->withCount(['soapLogs' => function ($q) {
                $q->whereMonth('created_at', now()->month);
            }])
            ->orderByDesc('soap_logs_count')
            ->limit(5)
            ->get(['id', 'name', 'soap_logs_count']);

        return $doctors->map(function ($d) {
            return ['name' => $d->name, 'count' => $d->soap_logs_count];
        })->toArray();
    }
}
