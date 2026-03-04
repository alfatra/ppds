<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\SoapLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SoapLogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // show all soap logs with pagination
        $logs = SoapLog::with(['doctor','patient'])->orderBy('visit_date','desc')->paginate(20);
        return view('soap_logs.index', compact('logs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // show form to create new SOAP entry
        return view('soap_logs.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'patient_id' => 'nullable|integer',
            'visit_date' => 'nullable|date',
            'subjective' => 'nullable|string',
            'objective' => 'nullable|string',
            'assessment' => 'nullable|string',
            'plan' => 'nullable|string',
            'diagnosis' => 'nullable|string|max:255',
        ]);

        // set doctor_id from current user
        $data['doctor_id'] = Auth::id();
        $data['created_by'] = Auth::id();
        $log = SoapLog::create($data);

        return redirect()->route('ppds.soap-logs.index')->with('success', 'Soap log created.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $log = SoapLog::findOrFail($id);
        return view('soap_logs.show', compact('log'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $log = SoapLog::findOrFail($id);
        return view('soap_logs.edit', compact('log'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $log = SoapLog::findOrFail($id);
        $data = $request->validate([
            'patient_id' => 'nullable|integer',
            'visit_date' => 'nullable|date',
            'subjective' => 'nullable|string',
            'objective' => 'nullable|string',
            'assessment' => 'nullable|string',
            'plan' => 'nullable|string',
            'diagnosis' => 'nullable|string|max:255',
        ]);
        // doctor is current user (do not allow change)
        $data['doctor_id'] = Auth::id();
        $data['updated_by'] = Auth::id();
        $log->update($data);
        return redirect()->route('ppds.soap-logs.index')->with('success','Soap log updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $log = SoapLog::findOrFail($id);
        $log->delete();
        return redirect()->route('ppds.soap-logs.index')->with('success','Soap log deleted.');
    }
}
