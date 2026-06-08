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
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $isAdmin = $user->isSuperAdmin() || $user->isAdmin();

        $query = SoapLog::query();
        if (!$isAdmin) {
            $query->where('created_by', $user->id);
        }

        // Statistik Dasar
        $totalSoap = (clone $query)->count();
        $soapHariIni = (clone $query)->whereDate('created_at', today())->count();
        $soapBulanIni = (clone $query)->whereMonth('created_at', now()->month)
                                       ->whereYear('created_at', now()->year)
                                       ->count();

        // Data untuk grafik trend mingguan (7 hari terakhir)
        $weeklyData = $this->getWeeklySoapTrend($query);

        // Total unique pasien
        $totalPatients = (clone $query)->distinct('patient_id')->count('patient_id');

        // Breakdown diagnosa
        $diagnosisBreakdown = $this->getDiagnosisBreakdown($query);

        // Aktivitas dokter (jika admin)
        $doctorActivity = [];
        if ($isAdmin) {
            $doctorActivity = $this->getDoctorActivity();
        }

        // Performa PPDS - target SOAP mingguan
        $weeklyTarget = 5; // Target SOAP per minggu
        $weeklyProgress = $soapBulanIni > 0 ? min(100, ($soapBulanIni / ($weeklyTarget * 4)) * 100) : 0;

        // 5 aktivitas terbaru
        $recentSoaps = (clone $query)->with(['patient', 'doctor'])
                              ->latest()
                              ->take(5)
                              ->get();

        return view('dashboard.index', compact(
            'user', 'isAdmin', 'totalSoap', 'soapHariIni', 'soapBulanIni',
            'totalPatients', 'weeklyData', 'diagnosisBreakdown', 'doctorActivity',
            'weeklyProgress', 'recentSoaps'
        ));
    }

    private function getWeeklySoapTrend($baseQuery)
    {
        $days = [];
        $counts = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->startOfDay();
            $days[] = $date->format('d M');
            $count = (clone $baseQuery)->whereDate('created_at', $date)->count();
            $counts[] = $count;
        }

        return [
            'days' => $days,
            'counts' => $counts
        ];
    }

    private function getDiagnosisBreakdown($baseQuery)
    {
        $diagnosisCounts = (clone $baseQuery)
            ->selectRaw('soap_logs.diagnosa_id AS code, COALESCE(NULLIF(diagnoses.diagnose_name, \'\'), soap_logs.diagnosa_id) AS name, COUNT(*) AS count')
            ->leftJoin('diagnoses', 'soap_logs.diagnosa_id', '=', 'diagnoses.diagnose_id')
            ->whereNotNull('soap_logs.diagnosa_id')
            ->where('soap_logs.diagnosa_id', '<>', '')
            ->groupBy('soap_logs.diagnosa_id', 'diagnoses.diagnose_name')
            ->orderByRaw('COUNT(*) DESC')
            ->limit(5)
            ->get();

        $diagnosisMap = $this->getDiagnosisMapFromApi();

        $codes = [];
        $names = [];
        $counts = [];

        foreach ($diagnosisCounts as $item) {
            $codes[] = $item->code;
            $diagnosisName = $item->name ?: $item->code;

            if ($diagnosisName === $item->code) {
                $diagData = $diagnosisMap->get($item->code);
                if ($diagData) {
                    $diagnosisName = data_get($diagData, 'DiagnoseName')
                        ?: data_get($diagData, 'diagnose_name')
                        ?: data_get($diagData, 'name')
                        ?: data_get($diagData, 'DiagnoseDesc')
                        ?: $item->code;
                }
            }

            $names[] = $diagnosisName;
            $counts[] = $item->count;
        }

        \Log::debug('[Dashboard] Diagnosis Breakdown', [
            'count' => count($codes),
            'codes' => $codes,
            'names' => $names,
        ]);

        return [
            'codes' => $codes,
            'names' => $names,
            'counts' => $counts,
        ];
    }

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
                $q->where('created_at', '>=', now()->subDays(6)->startOfDay());
            }])
            ->orderByDesc('soap_logs_count')
            ->limit(5)
            ->get(['id', 'name', 'soap_logs_count']);

        return $doctors->map(function ($d) {
            return [
                'name' => $d->name,
                'count' => $d->soap_logs_count
            ];
        })->toArray();
    }
}