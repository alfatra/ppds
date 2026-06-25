@extends('layouts.master')

@php use Illuminate\Support\Str; @endphp

@section('title','SOAP Logs')

@section('content')
<div class="container-fluid">

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">SOAP Logbook</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-body pb-0">
                    <div class="row mb-3 align-items-center">
                        <div class="col-md-6 mb-2 mb-md-0">
                            <a href="{{ route('ppds.soap-logs.create') }}" class="btn btn-primary rounded-pill shadow-sm">
                                <i class="mdi mdi-plus me-1"></i> New Entry
                            </a>
                        </div>
                        <div class="col-md-6">
                            <form action="{{ route('ppds.soap-logs.index') }}" method="GET" class="d-flex justify-content-md-end">
                                <div class="input-group" style="max-width: 350px;">
                                    <input type="text" name="search" class="form-control rounded-start bg-light border-light" placeholder="Cari dokter, pasien, diinput..." value="{{ request('search') }}">
                                    <button class="btn btn-primary" type="submit">
                                        <i class="mdi mdi-magnify"></i> Cari
                                    </button>
                                    @if(request('search'))
                                        <a href="{{ route('ppds.soap-logs.index') }}" class="btn btn-outline-secondary rounded-end" title="Reset">
                                            <i class="mdi mdi-close"></i>
                                        </a>
                                    @endif
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="card-body pt-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-nowrap align-middle mb-0">
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
                                        <td>
                                            <h5 class="font-size-14 mb-0 text-dark">{{ $log->display_patient_name }}</h5>
                                        </td>
                                        <td>{{ $log->doctor? $log->doctor->name : $log->doctor_id }}</td>
                                        <td>
                                            <div class="badge bg-soft-info text-info font-size-12 p-2 rounded-pill">
                                                <i class="mdi mdi-calendar-clock me-1"></i> {{ $log->visit_date->format('d F Y, H:i') }}
                                            </div>
                                        </td>
                                        <td>
                                            <span class="text-muted text-truncate d-inline-block" style="max-width: 200px;" title="{{ $log->subjective }}">
                                                {{ Str::limit($log->subjective, 50) }}
                                            </span>
                                        </td>
                                        <td>
                                        @if($log->diagnosa_id)
                                            <span class="badge bg-primary rounded-pill px-3 py-1">{{ $log->diagnosa_id }}</span><br>
                                            <small class="text-muted mt-1 d-block text-truncate" style="max-width: 200px;" title="{{ $log->api_diagnosis_name }}">
                                                {{ $log->api_diagnosis_name ?? '-' }}
                                            </small>
                                        @else
                                            <span class="badge bg-soft-secondary text-secondary rounded-pill px-3 py-1">N/A</span>
                                        @endif
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-xs me-2">
                                                    <span class="avatar-title rounded-circle bg-soft-primary text-primary font-size-12">
                                                        {{ strtoupper(substr($log->creator->name ?? 'U', 0, 1)) }}
                                                    </span>
                                                </div>
                                                <span>{{ $log->creator->name ?? 'User Dihapus' }}</span>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('ppds.soap-logs.show',$log) }}" class="btn btn-outline-info btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" title="View">
                                                    <i class="mdi mdi-eye d-block d-sm-none"></i>
                                                    <span class="d-none d-sm-block">View</span>
                                                </a>
                                                <a href="{{ route('ppds.soap-logs.edit',$log) }}" class="btn btn-outline-warning btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit">
                                                    <i class="mdi mdi-pencil d-block d-sm-none"></i>
                                                    <span class="d-none d-sm-block">Edit</span>
                                                </a>
                                                <form action="{{ route('ppds.soap-logs.destroy',$log) }}" method="POST" style="display:inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus log ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger btn-sm rounded-end" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete">
                                                        <i class="mdi mdi-delete d-block d-sm-none"></i>
                                                        <span class="d-none d-sm-block">Delete</span>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="mdi mdi-file-document-outline display-4 mb-3"></i>
                                                <p class="font-size-15">Tidak ada log SOAP yang ditemukan.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse 
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="d-flex justify-content-end mt-4">
                        {{ $logs->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
