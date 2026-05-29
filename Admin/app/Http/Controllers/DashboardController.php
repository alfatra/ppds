<?php

namespace App\Http\Controllers;

use App\Models\SoapLog;
use App\Models\Diagnosis;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
            ->selectRaw('COALESCE(diagnoses.diagnose_name, soap_logs.diagnosa_id) AS name, COUNT(*) AS count')
            ->leftJoin('diagnoses', 'soap_logs.diagnosa_id', '=', 'diagnoses.diagnose_id')
            ->whereNotNull('soap_logs.diagnosa_id')
            ->where('soap_logs.diagnosa_id', '<>', '')
            ->groupBy('soap_logs.diagnosa_id', 'diagnoses.diagnose_name')
            ->orderByRaw('COUNT(*) DESC')
            ->limit(5)
            ->get();

        \Log::debug('[Dashboard] Diagnosis Breakdown', [
            'count' => $diagnosisCounts->count(),
            'data' => $diagnosisCounts->toArray()
        ]);

        return [
            'names' => $diagnosisCounts->pluck('name')->toArray(),
            'counts' => $diagnosisCounts->pluck('count')->toArray(),
        ];
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
            return [
                'name' => $d->name,
                'count' => $d->soap_logs_count
            ];
        })->toArray();
    }
}