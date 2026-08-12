<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\DailyActivity;
use App\Models\MedicalActivity;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DailyActivityController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $query = DailyActivity::with(['medicalActivity', 'user']);
        
        // If not admin/superadmin, only show their own activities
        if (!in_array($user->role, ['admin', 'superadmin'])) {
            $query->where('user_id', $user->id);
        }
        
        $activities = $query->latest()->get();
        return view('daily_activities.index', compact('activities'));
    }

    public function create()
    {
        $medicalActivities = MedicalActivity::all();
        return view('daily_activities.create', compact('medicalActivities'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'medical_activity_id' => 'required|exists:medical_activities,id',
            'activity_date' => 'required|date',
            'patient_name' => 'nullable|string|max:255',
            'medical_record_no' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        DailyActivity::create(array_merge($request->all(), [
            'user_id' => Auth::id(),
            'supervisor_id' => Auth::user()->supervisor_id
        ]));

        return redirect()->route('daily-activities.index')->with('success', 'Kegiatan Harian berhasil ditambahkan.');
    }

    public function edit(DailyActivity $dailyActivity)
    {
        // Ensure user can only edit their own, unless admin
        if (!in_array(Auth::user()->role, ['admin', 'superadmin']) && $dailyActivity->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        if ($dailyActivity->approval_status === 'approved' && !in_array(Auth::user()->role, ['admin', 'superadmin'])) {
            abort(403, 'Tidak dapat mengedit data yang sudah disetujui.');
        }

        $medicalActivities = MedicalActivity::all();
        return view('daily_activities.edit', compact('dailyActivity', 'medicalActivities'));
    }

    public function update(Request $request, DailyActivity $dailyActivity)
    {
        if (!in_array(Auth::user()->role, ['admin', 'superadmin']) && $dailyActivity->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        if ($dailyActivity->approval_status === 'approved' && !in_array(Auth::user()->role, ['admin', 'superadmin'])) {
            abort(403, 'Tidak dapat mengedit data yang sudah disetujui.');
        }

        $request->validate([
            'medical_activity_id' => 'required|exists:medical_activities,id',
            'activity_date' => 'required|date',
            'patient_name' => 'nullable|string|max:255',
            'medical_record_no' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        // Jika diedit dan status sebelumnya rejected, kembalikan ke pending
        $data = $request->all();
        // Paksa assign ke supervisor yang terhubung dengan user, atau gunakan yang sudah ada jika tidak ada
        $data['supervisor_id'] = Auth::user()->supervisor_id ?: $dailyActivity->supervisor_id;
        
        if ($dailyActivity->approval_status === 'rejected') {
            $data['approval_status'] = 'pending';
            $data['supervisor_note'] = null; // Clear rejection note
        }

        $dailyActivity->update($data);

        return redirect()->route('daily-activities.index')->with('success', 'Kegiatan Harian berhasil diperbarui.');
    }

    public function destroy(DailyActivity $dailyActivity)
    {
        if (!in_array(Auth::user()->role, ['admin', 'superadmin']) && $dailyActivity->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        if ($dailyActivity->approval_status === 'approved' && !in_array(Auth::user()->role, ['admin', 'superadmin'])) {
            abort(403, 'Tidak dapat menghapus data yang sudah disetujui.');
        }

        $dailyActivity->delete();

        return redirect()->route('daily-activities.index')->with('success', 'Kegiatan Harian berhasil dihapus.');
    }
}
