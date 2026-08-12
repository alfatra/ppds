@extends('layouts.master')
@section('title') Kegiatan Harian @endsection
@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Kegiatan Harian</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Logbook</a></li>
                    <li class="breadcrumb-item active">Kegiatan Harian</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="card-title">Daftar Kegiatan Harian</h4>
                    <a href="{{ route('daily-activities.create') }}" class="btn btn-primary btn-sm">
                        <i class="ri-add-line align-middle me-1"></i> Tambah Kegiatan
                    </a>
                </div>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="table-responsive">
                    <table id="datatable" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>PPDS / Pengguna</th>
                                <th>Tindakan Medis</th>
                                <th>Nama Pasien</th>
                                <th>No. RM</th>
                                <th>Keterangan</th>
                                <th>Supervisor</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($activities as $activity)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ \Carbon\Carbon::parse($activity->activity_date)->format('d-m-Y') }}</td>
                                <td>{{ $activity->user->name ?? '-' }}</td>
                                <td>{{ $activity->medicalActivity->name ?? '-' }}</td>
                                <td>{{ $activity->patient_name ?? '-' }}</td>
                                <td>{{ $activity->medical_record_no ?? '-' }}</td>
                                <td>{{ $activity->notes ?? '-' }}</td>
                                <td>{{ $activity->supervisor->name ?? '-' }}</td>
                                <td>
                                    @if($activity->approval_status == 'pending')
                                        <span class="badge bg-warning">Pending</span>
                                    @elseif($activity->approval_status == 'approved')
                                        <span class="badge bg-success">Approved</span>
                                    @else
                                        <span class="badge bg-danger" title="{{ $activity->supervisor_note }}">Rejected</span>
                                    @endif
                                </td>
                                <td>
                                    @if($activity->approval_status != 'approved' || auth()->user()->isSuperAdmin() || auth()->user()->isAdmin())
                                        @if($activity->approval_status == 'rejected')
                                            <a href="{{ route('daily-activities.edit', $activity->id) }}" class="btn btn-sm btn-danger" title="Revisi">
                                                <i class="ri-edit-circle-fill"></i> Revisi
                                            </a>
                                        @else
                                            <a href="{{ route('daily-activities.edit', $activity->id) }}" class="btn btn-sm btn-info" title="Edit">
                                                <i class="ri-pencil-line"></i>
                                            </a>
                                        @endif
                                        <form action="{{ route('daily-activities.destroy', $activity->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                        </form>
                                    @else
                                        <span class="badge bg-light text-muted"><i class="ri-lock-2-line"></i> Terkunci</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
