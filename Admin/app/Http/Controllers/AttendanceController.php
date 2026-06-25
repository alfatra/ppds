<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\SoapLog;
use App\Models\DailyActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    /**
     * Menampilkan halaman utama absensi berdasarkan SOAP logs dan Kegiatan Harian.
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
        $soapLogs = SoapLog::whereBetween('visit_date', [$startDate->copy()->startOfDay(), $endDate->copy()->endOfDay()])
            ->get()
            ->groupBy(function ($log) {
                return $log->doctor_id . '|' . Carbon::parse($log->visit_date)->format('Y-m-d');
            });

        // Get all Daily Activities for the date range
        $dailyActivities = DailyActivity::whereBetween('activity_date', [$startDate->copy()->startOfDay(), $endDate->copy()->endOfDay()])
            ->get()
            ->groupBy(function ($activity) {
                return $activity->user_id . '|' . Carbon::parse($activity->activity_date)->format('Y-m-d');
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
                $hasSOAPLog = isset($soapLogs[$dateKey]);
                $hasDailyActivity = isset($dailyActivities[$dateKey]);

                $isPresent = $hasSOAPLog || $hasDailyActivity;

                $attendance['days'][$date->format('Y-m-d')] = [
                    'date' => $date,
                    'is_present' => $isPresent,
                    'soap_count' => $hasSOAPLog ? count($soapLogs[$dateKey]) : 0,
                    'activity_count' => $hasDailyActivity ? count($dailyActivities[$dateKey]) : 0
                ];
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
            $totalDays = count($attendance['days']);
            $presentDays = count(array_filter($attendance['days'], fn($day) => $day['is_present']));
            $targetDays = isset($user->attendance_target) && $user->attendance_target !== null ? (int) $user->attendance_target : null;

            $statistics[$user->id] = [
                'name' => $user->name,
                'present' => $presentDays,
                'absent' => $totalDays - $presentDays,
                'total' => $totalDays,
                'target' => $targetDays,
                'target_met' => $targetDays !== null ? $presentDays >= $targetDays : null,
                'percentage' => $totalDays > 0 ? round(($presentDays / $totalDays) * 100, 2) : 0
            ];
        }

        return $statistics;
    }

    /**
     * Get detail for a specific PPDS on a specific date (both SOAP logs and Daily Activities)
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

        $soapLogs = SoapLog::where('doctor_id', $userId)
            ->whereDate('visit_date', $date)
            ->with('patient', 'diagnosis')
            ->get();

        $dailyActivities = DailyActivity::where('user_id', $userId)
            ->whereDate('activity_date', $date)
            ->with('medicalActivity')
            ->get();

        return response()->json([
            'soap_logs' => $soapLogs,
            'daily_activities' => $dailyActivities
        ]);
    }
}