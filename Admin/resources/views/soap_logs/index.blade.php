@extends('layouts.master')

@php use Illuminate\Support\Str; @endphp

@section('title','SOAP Logs')

@section('content')
<div class="container-fluid">
    <h4 class="mb-3">SOAP Logbook</h4>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <p><a href="{{ route('ppds.soap-logs.create') }}" class="btn btn-primary btn-sm">New Entry</a></p>
    <div class="table-responsive">
    <table class="table table-bordered align-middle">
        <thead class="table-light">
            <tr>
                <th>Patient</th>
                <th>Doctor</th>
                <th>Visit Date</th>
                <th>Subjective</th>
                <th>Diagnosa</th>
                <th class="text-center">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($logs as $log)
                <tr>
                    <td>{{ $log->patient? $log->patient->name : $log->patient_id }}</td>
                    <td>{{ $log->doctor? $log->doctor->name : $log->doctor_id }}</td>
                    <td>  {{ $log->visit_date->format('d F Y, H:i') }}</td>
                    <td>{{ Str::limit($log->subjective,50) }}</td>
                    <td>{{ $log->diagnosis ?? '-' }}</td>
                    <td class="text-center">
                        <a href="{{ route('ppds.soap-logs.show',$log) }}" class="btn btn-sm btn-info">View</a>
                        <a href="{{ route('ppds.soap-logs.edit',$log) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('ppds.soap-logs.destroy',$log) }}" method="POST" style="display:inline">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger" onclick="return confirm('Delete?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7">No SOAP entries found.</td></tr>
            @endforelse 
        </tbody>
    </table>
</div>
    {{ $logs->links() }}
</div>
@endsection
