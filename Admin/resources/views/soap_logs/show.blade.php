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
                <dd class="col-sm-9"><pre>{{ $log->objective }}</pre></dd>

                <dt class="col-sm-3">Assessment</dt>
                <dd class="col-sm-9"><pre>{{ $log->assessment }}</pre></dd>

                <dt class="col-sm-3">Plan</dt>
                <dd class="col-sm-9"><pre>{{ $log->plan }}</pre></dd>
            </dl>
            <a href="{{ route('ppds.soap-logs.index') }}" class="btn btn-secondary btn-sm">Back</a>
        </div>
    </div>
</div>
@endsection
