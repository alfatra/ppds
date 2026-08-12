@extends('layouts.master')

@section('title','SOAP Log Details')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title mb-0">SOAP Entry #{{ $log->id }}</h4>
        </div>
        <div class="card-body">
            <dl class="row">
                <dt class="col-sm-3">Patient</dt>
                <dd class="col-sm-9">{{ $log->patient? $log->patient->name : $log->patient_id }}</dd>

                <dt class="col-sm-3">Doctor</dt>
                <dd class="col-sm-9">{{ $log->doctor? $log->doctor->name : $log->doctor_id }}</dd>

                <dt class="col-sm-3">Visit Date</dt>
                <dd class="col-sm-9">{{ optional($log->visit_date)->format('Y-m-d H:i') }}</dd>

                <dt class="col-sm-3">Subjective</dt>
                <dd class="col-sm-9"><pre>{{ $log->subjective }}</pre></dd>

                <dt class="col-sm-3">Objective</dt>
                <dd class="col-sm-9">
                    @if($log->ttv_td || $log->ttv_hr || $log->ttv_rr || $log->ttv_temp || $log->ttv_spo2 || $log->ttv_vas)
                    <div class="mb-2">
                        <span class="badge bg-light text-dark border me-1 mb-1">TD: {{ $log->ttv_td ?? '-' }}</span>
                        <span class="badge bg-light text-dark border me-1 mb-1">HR: {{ $log->ttv_hr ?? '-' }}</span>
                        <span class="badge bg-light text-dark border me-1 mb-1">RR: {{ $log->ttv_rr ?? '-' }}</span>
                        <span class="badge bg-light text-dark border me-1 mb-1">Suhu: {{ $log->ttv_temp ?? '-' }}</span>
                        <span class="badge bg-light text-dark border me-1 mb-1">SpO2: {{ $log->ttv_spo2 ?? '-' }}</span>
                        <span class="badge bg-light text-dark border me-1 mb-1">VAS: {{ $log->ttv_vas ?? '-' }}</span>
                    </div>
                    @endif
                    <pre>{{ $log->objective }}</pre>
                </dd>

                <dt class="col-sm-3">Assessment</dt>
                <dd class="col-sm-9"><pre>{{ $log->assessment }}</pre></dd>
                <dt class="col-sm-3">Diagnosa</dt>
                <dd class="col-sm-9">
                @if($log->diagnosis)
                    {{ $log->diagnosis->diagnose_id }} - {{ $log->diagnosis->diagnose_name }}
                @else
                @endif
                </dd>
                <dt class="col-sm-3">Plan</dt>
                <dd class="col-sm-9"><pre>{{ $log->plan }}</pre></dd>
            </dl>
            <a href="{{ route('ppds.soap-logs.index') }}" class="btn btn-secondary btn-sm">Back</a>
        </div>
    </div>
</div>
@endsection
