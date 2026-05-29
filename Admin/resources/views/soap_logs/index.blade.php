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
   <div class="d-flex justify-content-end mb-3">
    <!-- max-width membatasi lebar form agar tidak memanjang -->
    <form action="{{ route('ppds.soap-logs.index') }}" method="GET" style="max-width: 300px; width: 100%;">
        <!-- input-group-sm membuat ukuran input dan tombol menjadi lebih kecil -->
        <div class="input-group input-group-sm">
            <input type="text" name="search" class="form-control" placeholder="Cari laporan..." value="{{ request('search') }}">
            <button class="btn btn-primary" type="submit">Cari</button>
            @if(request('search'))
                <a href="{{ route('ppds.soap-logs.index') }}" class="btn btn-secondary" title="Reset">X</a>
            @endif
        </div>
    </form>
</div>

    <div class="table-responsive">
    <table class="table table-bordered align-middle">
        <thead class="table-light">
            <tr>
                <th>Patient</th>
                <th>Doctor</th>
                <th>Visit Date</th>
                <th>Subjective</th>
                <th>Diagnosa</th>
                <th>Diinput Oleh</th>
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
                    <td>
                    @if($log->diagnosa_id)
                        <span class="badge bg-primary">{{ $log->diagnosa_id }}</span><br>
                        <small class="text-muted">{{ $log->api_diagnosis_name ?? '-' }}</small>
                    @else
                        -
                    @endif
                    </td>
                    <td>{{ $log->creator->name ?? 'User Dihapus' }}</td>
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
