@extends('layouts.master')
@section('title', 'Kotak Persetujuan (Approval Inbox)')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Kotak Persetujuan (Approval Inbox)</h4>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Kegiatan Harian Menunggu Persetujuan</h4>
                <div class="table-responsive">
                    <table class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                            <tr>
                                <th>PPDS</th>
                                <th>Tanggal</th>
                                <th>Tindakan Medis</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($dailyActivities as $activity)
                            <tr>
                                <td>{{ $activity->user->name ?? '-' }}</td>
                                <td>{{ \Carbon\Carbon::parse($activity->activity_date)->format('d-m-Y') }}</td>
                                <td>{{ $activity->medicalActivity->name ?? '-' }}</td>
                                <td>
                                    @if($activity->approval_status == 'pending')
                                        <span class="badge bg-warning">Pending</span>
                                    @elseif($activity->approval_status == 'approved')
                                        <span class="badge bg-success">Approved</span>
                                    @else
                                        <span class="badge bg-danger">Rejected</span>
                                    @endif
                                </td>
                                <td>
                                    @if($activity->approval_status == 'pending')
                                    <form action="{{ route('approvals.daily-activity.approve', $activity->id) }}" method="POST" class="d-inline">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-success">Approve</button>
                                    </form>
                                    <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#rejectDailyModal{{ $activity->id }}">Reject</button>
                                    
                                    <!-- Modal -->
                                    <div class="modal fade" id="rejectDailyModal{{ $activity->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form action="{{ route('approvals.daily-activity.reject', $activity->id) }}" method="POST">
                                                    @csrf @method('PATCH')
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Tolak Laporan</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <label>Catatan Penolakan</label>
                                                            <textarea name="note" class="form-control" required></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-danger">Tolak</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="text-center">Tidak ada data</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <div class="card mt-4">
            <div class="card-body">
                <h4 class="card-title mb-4">SOAP Log Menunggu Persetujuan</h4>
                <div class="table-responsive">
                    <table class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                            <tr>
                                <th>PPDS</th>
                                <th>Tanggal Visit</th>
                                <th>Subjective</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($soapLogs as $log)
                            <tr>
                                <td>{{ $log->creator->name ?? '-' }}</td>
                                <td>{{ $log->visit_date->format('d F Y, H:i') }}</td>
                                <td>{{ \Illuminate\Support\Str::limit($log->subjective, 50) }}</td>
                                <td>
                                    @if($log->approval_status == 'pending')
                                        <span class="badge bg-warning">Pending</span>
                                    @elseif($log->approval_status == 'approved')
                                        <span class="badge bg-success">Approved</span>
                                    @else
                                        <span class="badge bg-danger">Rejected</span>
                                    @endif
                                </td>
                                <td>
                                    @if($log->approval_status == 'pending')
                                    <form action="{{ route('approvals.soap-log.approve', $log->id) }}" method="POST" class="d-inline">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-success">Approve</button>
                                    </form>
                                    <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#rejectSoapModal{{ $log->id }}">Reject</button>
                                    
                                    <!-- Modal -->
                                    <div class="modal fade" id="rejectSoapModal{{ $log->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form action="{{ route('approvals.soap-log.reject', $log->id) }}" method="POST">
                                                    @csrf @method('PATCH')
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Tolak Laporan SOAP</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <label>Catatan Penolakan</label>
                                                            <textarea name="note" class="form-control" required></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-danger">Tolak</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    @else
                                        -
                                    @endif
                                    <a href="{{ route('ppds.soap-logs.show', $log->id) }}" class="btn btn-sm btn-info" target="_blank">Lihat Detail</a>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="text-center">Tidak ada data</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
