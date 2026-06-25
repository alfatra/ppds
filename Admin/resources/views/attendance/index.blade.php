@extends('layouts.master')

@section('title', 'Attendance Check-In')

@section('content')
<div class="container-fluid">
    <h4 class="mb-4"><i class="mdi mdi-check-circle"></i> Attendance Check-In Menu</h4>

    <!-- Date Range Filter -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-4">
                    <label for="start_date" class="form-label">Start Date</label>
                    <input type="date" class="form-control" id="start_date" name="start_date" 
                           value="{{ $startDate->format('Y-m-d') }}">
                </div>
                <div class="col-md-4">
                    <label for="end_date" class="form-label">End Date</label>
                    <input type="date" class="form-control" id="end_date" name="end_date" 
                           value="{{ $endDate->format('Y-m-d') }}">
                </div>
                <div class="col-md-4 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="mdi mdi-filter"></i> Filter
                    </button>
                    <a href="{{ route('attendance.index') }}" class="btn btn-secondary">
                        <i class="mdi mdi-refresh"></i> Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Attendance Statistics Summary -->
    @if($statistics)
    <div class="row mb-4">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Attendance Summary</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>PPDS Name</th>
                                    <th class="text-center">Present Days</th>
                                    <th class="text-center">Absent Days</th>
                                    <th class="text-center">Total Days</th>
                                    <th class="text-center">Target Days</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Attendance %</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($statistics as $stat)
                                <tr>
                                    <td><strong>{{ $stat['name'] }}</strong></td>
                                    <td class="text-center">
                                        <span class="badge bg-success">{{ $stat['present'] }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-danger">{{ $stat['absent'] }}</span>
                                    </td>
                                    <td class="text-center">{{ $stat['total'] }}</td>
                                    <td class="text-center">
                                        @if($stat['target'] !== null)
                                            {{ $stat['target'] }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($stat['target'] === null)
                                            <span class="badge bg-secondary">No Target</span>
                                        @elseif($stat['target_met'])
                                            <span class="badge bg-success">Target Met</span>
                                        @else
                                            <span class="badge bg-warning">Needs {{ max(0, $stat['target'] - $stat['present']) }} more</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($stat['percentage'] >= 80)
                                            <span class="badge bg-success">{{ $stat['percentage'] }}%</span>
                                        @elseif($stat['percentage'] >= 60)
                                            <span class="badge bg-warning">{{ $stat['percentage'] }}%</span>
                                        @else
                                            <span class="badge bg-danger">{{ $stat['percentage'] }}%</span>
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
    @endif

    <!-- Attendance Table - Horizontal Layout -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Daily Attendance Check-In
                <small class="text-muted">({{ $startDate->format('d M Y') }} - {{ $endDate->format('d M Y') }})</small>
            </h5>
        </div>
        <div class="card-body">
            @if($attendanceData && count($attendanceData) > 0)
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light sticky-top">
                        <tr>
                            <th class="text-center" style="min-width: 200px;">PPDS Name</th>
                            @foreach($dateRange as $date)
                            <th class="text-center" style="min-width: 60px;">
                                <small>
                                    {{ $date->format('D') }}<br>
                                    <strong>{{ $date->format('d') }}</strong>
                                </small>
                            </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($attendanceData as $attendance)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar me-2">
                                        <span class="avatar-title rounded-circle bg-primary-light">
                                           {{ substr($attendance['user']->name, 0, 1) }}
                                        </span>
                                    </div>
                                    <div>
                                        <h6 class="mb-0">{{ $attendance['user']->name }}</h6>
                                        <small class="text-muted">{{ $attendance['user']->email }}</small>
                                    </div>
                                </div>
                            </td>
                            @foreach($attendance['days'] as $dayData)
                            <td class="text-center cursor-pointer position-relative" 
                                @if($dayData['is_present'])
                                    onclick="showSoapDetail('{{ $attendance['user']->id }}', '{{ $dayData['date']->format('Y-m-d') }}')"
                                    title="Click to view SOAP entries"
                                @endif
                                style="background-color: {{ $dayData['is_present'] ? '#d4edda' : '#f8f9fa' }}">
                                @if($dayData['is_present'])
                                    <span class="badge bg-success p-2" data-bs-toggle="tooltip" 
                                          title="{{ $dayData['soap_count'] }} SOAP, {{ $dayData['activity_count'] }} Activity">
                                        <i class="mdi mdi-check-circle-outline"></i>
                                    </span>
                                    @if($dayData['soap_count'] > 0 || $dayData['activity_count'] > 0)
                                    <small class="d-block text-muted">S:{{ $dayData['soap_count'] }} A:{{ $dayData['activity_count'] }}</small>
                                    @endif
                                @else
                                    <span class="badge bg-light text-dark p-2">
                                        <i class="mdi mdi-minus-circle-outline"></i>
                                    </span>
                                @endif
                            </td>
                            @endforeach
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Legend -->
            <div class="mt-3 pt-3 border-top">
                <p class="mb-2"><strong>Legend:</strong></p>
                <span class="badge bg-success p-2 me-2">
                    <i class="mdi mdi-check-circle-outline"></i> Present (Has SOAP or Activity)
                </span>
                <span class="badge bg-light text-dark p-2">
                    <i class="mdi mdi-minus-circle-outline"></i> Absent (No Entries)
                </span>
                <span class="ms-3 text-muted">
                    <small><strong>S:</strong> SOAP Logs Count | <strong>A:</strong> Daily Activities Count</small>
                </span>
            </div>

            @else
            <div class="alert alert-info">
                <i class="mdi mdi-information"></i> No PPDS found or no attendance data available for the selected date range.
            </div>
            @endif
        </div>
    </div>
</div>

<!-- SOAP Details Modal -->
<div class="modal fade" id="soapModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">SOAP Entries</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="modalBody">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .avatar-title {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        font-weight: 600;
    }

    .bg-primary-light {
        background-color: #e7f1ff !important;
        color: #0055cc !important;
    }

    .cursor-pointer {
        cursor: pointer;
    }

    .cursor-pointer:hover {
        opacity: 0.8;
    }

    .sticky-top {
        top: 0;
        z-index: 10;
    }

    table tbody tr:hover {
        background-color: #f8f9fa;
    }
</style>

<script>
    // Initialize tooltips
    document.addEventListener('DOMContentLoaded', function() {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });

    function showSoapDetail(userId, date) {
        const modal = new bootstrap.Modal(document.getElementById('soapModal'));
        const modalTitle = document.getElementById('modalTitle');
        const modalBody = document.getElementById('modalBody');

        modalTitle.textContent = `SOAP Entries - ${date}`;
        modalBody.innerHTML = '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>';

        fetch(`{{ route('attendance.detail') }}?user_id=${userId}&date=${date}`)
            .then(response => response.json())
            .then(data => {
                if (data.soap_logs.length === 0 && data.daily_activities.length === 0) {
                    modalBody.innerHTML = '<div class="alert alert-info">No entries found for this date.</div>';
                } else {
                    let html = '';
                    
                    if (data.soap_logs.length > 0) {
                        html += '<h6>SOAP Logs</h6>';
                        html += '<div class="table-responsive"><table class="table table-sm"><thead class="table-light"><tr><th>Patient</th><th>Diagnose</th><th>Time</th></tr></thead><tbody>';
                        data.soap_logs.forEach(log => {
                            html += `<tr>
                                <td>${log.patient ? log.patient.name : log.patient_name_manual || 'N/A'}</td>
                                <td>${log.diagnosis ? log.diagnosis.diagnose_name : 'N/A'}</td>
                                <td>${new Date(log.visit_date).toLocaleString()}</td>
                            </tr>`;
                        });
                        html += '</tbody></table></div>';
                    }

                    if (data.daily_activities.length > 0) {
                        html += '<h6 class="mt-3">Daily Activities</h6>';
                        html += '<div class="table-responsive"><table class="table table-sm"><thead class="table-light"><tr><th>Activity</th><th>Patient</th><th>Time</th></tr></thead><tbody>';
                        data.daily_activities.forEach(log => {
                            html += `<tr>
                                <td>${log.medical_activity ? log.medical_activity.name : 'N/A'}</td>
                                <td>${log.patient_name || 'N/A'}</td>
                                <td>${new Date(log.activity_date).toLocaleDateString()}</td>
                            </tr>`;
                        });
                        html += '</tbody></table></div>';
                    }

                    modalBody.innerHTML = html;
                }
                modal.show();
            })
            .catch(error => {
                modalBody.innerHTML = '<div class="alert alert-danger">Error loading SOAP entries: ' + error.message + '</div>';
                modal.show();
                
            });
    }
</script>
@endsection
