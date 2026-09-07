<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DailyActivity;
use App\Models\SoapLog;
use Illuminate\Support\Facades\Auth;

class ApprovalController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Get daily activities (only pending ones for inbox)
        $dailyActivitiesQuery = DailyActivity::with(['user', 'medicalActivity'])->where('approval_status', 'pending')->latest();
        if ($user->isKonsulen()) {
            $dailyActivitiesQuery->where('supervisor_id', $user->id);
        }
        $dailyActivities = $dailyActivitiesQuery->get();

        // Get soap logs (only pending ones for inbox)
        $soapLogsQuery = SoapLog::with(['creator', 'patient'])->where('approval_status', 'pending')->latest();
        if ($user->isKonsulen()) {
            $soapLogsQuery->where('supervisor_id', $user->id);
        }
        $soapLogs = $soapLogsQuery->get();

        return view('approvals.index', compact('dailyActivities', 'soapLogs'));
    }

    public function approveDailyActivity(Request $request, $id)
    {
        $activity = DailyActivity::findOrFail($id);
        $this->authorizeApproval($activity);

        $activity->update(['approval_status' => 'approved', 'supervisor_note' => null]);
        return redirect()->back()->with('success', 'Daily Activity approved.');
    }

    public function rejectDailyActivity(Request $request, $id)
    {
        $request->validate(['note' => 'required|string']);
        
        $activity = DailyActivity::findOrFail($id);
        $this->authorizeApproval($activity);

        $activity->update(['approval_status' => 'rejected', 'supervisor_note' => $request->note]);
        return redirect()->back()->with('success', 'Daily Activity rejected.');
    }

    public function approveSoapLog(Request $request, $id)
    {
        $log = SoapLog::findOrFail($id);
        $this->authorizeApproval($log);

        $log->update(['approval_status' => 'approved', 'supervisor_note' => null]);
        return redirect()->back()->with('success', 'SOAP Log approved.');
    } 

    public function rejectSoapLog(Request $request, $id)
    {
        $request->validate(['note' => 'required|string']);
        
        $log = SoapLog::findOrFail($id);
        $this->authorizeApproval($log);

        $log->update(['approval_status' => 'rejected', 'supervisor_note' => $request->note]);
        return redirect()->back()->with('success', 'SOAP Log rejected.');
    }

    private function authorizeApproval($model)
    {
        $user = Auth::user();
        if (!$user->isSuperAdmin() && !$user->isAdmin() && $model->supervisor_id !== $user->id) {
            abort(403, 'Unauthorized action.');
        }
    }
}
