<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\SoapLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    /**
     * Menampilkan halaman utama absensi berdasarkan SOAP logs.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Get date range from request or default to current month
        $startDate = $request->input('start_date') 
            ? Carbon::createFromFormat('Y-m-d', $request->input('start_date'))
            : Carbon::now()->startOfMonth();
        
        $endDate = $request->input('end_date')
            ? Carbon::createFromFormat('Y-m-d', $request->input('end_date'))
            : Carbon::now()->endOfMonth();

        // Generate date range
        $dateRange = CarbonPeriod::create($startDate, '1 day', $endDate)->toArray();

        // Get PPDS users based on role
        // Admin/Superadmin dapat melihat semua PPDS
        // Regular users hanya dapat melihat data mereka sendiri
        if ($user->isAdmin() || $user->isSuperAdmin()) {
            $ppdsUsers = User::where('role', 'user')
                ->with('ppds')
                ->orderBy('name')
                ->get();
        } else {
            // Regular user hanya bisa melihat dirinya sendiri
            $ppdsUsers = User::where('id', $user->id)
                ->with('ppds')
                ->get();
        }

        // Get all SOAP logs for the date range
        $soapLogs = SoapLog::whereBetween('visit_date', [$startDate, $endDate])
            ->get()
            ->groupBy(function ($log) {
                return $log->doctor_id . '|' . $log->visit_date->format('Y-m-d');
            });

        // Build attendance data
        $attendanceData = [];
        
        foreach ($ppdsUsers as $ppds) {
            $attendance = [
                'user' => $ppds,
                'days' => []
            ];

            foreach ($dateRange as $date) {
                $dateKey = $ppds->id . '|' . $date->format('Y-m-d');
                $hasSOAPLog = false;

                // Check if this PPDS has a SOAP log for this date
                foreach ($soapLogs as $key => $logs) {
                    if (strpos($key, $ppds->id . '|' . $date->format('Y-m-d')) === 0) {
                        $hasSOAPLog = true;
                        break;
                    }
                }

                $attendance['days'][$date->format('Y-m-d')] = [
                    'date' => $date,
                    'is_present' => $hasSOAPLog,
                    'soap_count' => 0
                ];

                // Count SOAP logs for this day
                if ($hasSOAPLog) {
                    $key = $ppds->id . '|' . $date->format('Y-m-d');
                    $attendance['days'][$date->format('Y-m-d')]['soap_count'] = count($soapLogs[$key] ?? []);
                }
            }

            $attendanceData[] = $attendance;
        }

        // Calculate statistics
        $statistics = $this->calculateStatistics($attendanceData, $dateRange);

        return view('attendance.index', compact(
            'attendanceData',
            'dateRange',
            'startDate',
            'endDate',
            'statistics'
        ));
    }

    /**
     * Calculate attendance statistics
     */
    private function calculateStatistics($attendanceData, $dateRange)
    {
        $statistics = [];

        foreach ($attendanceData as $attendance) {
            $user = $attendance['user'];
            $presentDays = count(array_filter($attendance['days'], fn($day) => $day['is_present']));
            $totalDays = count($attendance['days']);

            $statistics[$user->id] = [
                'name' => $user->name,
                'present' => $presentDays,
                'absent' => $totalDays - $presentDays,
                'total' => $totalDays,
                'percentage' => $totalDays > 0 ? round(($presentDays / $totalDays) * 100, 2) : 0
            ];
        }

        return $statistics;
    }

    /**
     * Get SOAP logs detail for a specific PPDS on a specific date
     */
    public function getDetail(Request $request)
    {
        $user = Auth::user();
        $userId = $request->input('user_id');
        $date = $request->input('date');

        // Security check: regular users dapat hanya melihat data mereka sendiri
        if (!$user->isAdmin() && !$user->isSuperAdmin() && $user->id !== (int)$userId) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $logs = SoapLog::where('doctor_id', $userId)
            ->whereDate('visit_date', $date)
            ->with('patient', 'diagnosis')
            ->get();

        return response()->json($logs);
    }
}