<?php

namespace App\Http\Controllers;

use App\Models\MedicalActivity;
use Illuminate\Http\Request;

class MedicalActivityController extends Controller
{
    public function index()
    {
        $activities = MedicalActivity::latest()->paginate(10);
        return view('admin.medical_activities.index', compact('activities'));
    }

    public function create()
    {
        return view('admin.medical_activities.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        MedicalActivity::create($request->all());

        return redirect()->route('admin.medical-activities.index')
            ->with('success', 'Master Tindakan Medis berhasil ditambahkan.');
    }

    public function edit(MedicalActivity $medicalActivity)
    {
        return view('admin.medical_activities.edit', compact('medicalActivity'));
    }

    public function update(Request $request, MedicalActivity $medicalActivity)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $medicalActivity->update($request->all());

        return redirect()->route('admin.medical-activities.index')
            ->with('success', 'Master Tindakan Medis berhasil diperbarui.');
    }

    public function destroy(MedicalActivity $medicalActivity)
    {
        $medicalActivity->delete();

        return redirect()->route('admin.medical-activities.index')
            ->with('success', 'Master Tindakan Medis berhasil dihapus.');
    }
}
