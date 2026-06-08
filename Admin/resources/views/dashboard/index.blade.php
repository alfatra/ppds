@extends('layouts.master')

@section('title', 'Dashboard PPDS')

@section('content')

<div class="container-fluid">
    {{-- Page Header --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <div>
                    <h4 class="mb-sm-0 font-size-24">Dashboard PPDS</h4>
                    <p class="text-muted mb-0 mt-1">Selamat datang, <strong>{{ $user->name }}</strong></p>
                </div>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    {{-- Hero Metrics Section --}}
    <div class="row">
        {{-- Total SOAP Card --}}
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted font-size-13 mb-2">
                                <i class="ri-file-list-line"></i> Total SOAP
                            </p>
                            <h2 class="mb-0 font-weight-bold">{{ $totalSoap }}</h2>
                            <p class="text-muted font-size-12 mt-2 mb-0">Semua periode</p>
                        </div>
                        <div class="avatar-sm rounded-circle bg-primary bg-opacity-10" style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                            <i class="ri-file-list-line font-size-24" style="color: #5664d2;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- SOAP Hari Ini Card --}}
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted font-size-13 mb-2">
                                <i class="ri-calendar-today-line"></i> SOAP Hari Ini
                            </p>
                            <h2 class="mb-0 font-weight-bold">{{ $soapHariIni }}</h2>
                            <p class="text-muted font-size-12 mt-2 mb-0">Entri hari ini</p>
                        </div>
                        <div class="avatar-sm rounded-circle" style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; background-color: rgba(28, 187, 140, 0.1);">
                            <i class="ri-calendar-today-line font-size-24" style="color: #1cbb8c;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- SOAP Bulan Ini Card --}}
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted font-size-13 mb-2">
                                <i class="ri-calendar-month-line"></i> SOAP Bulan Ini
                            </p>
                            <h2 class="mb-0 font-weight-bold">{{ $soapBulanIni }}</h2>
                            <p class="text-muted font-size-12 mt-2 mb-0">Entri bulan ini</p>
                        </div>
                        <div class="avatar-sm rounded-circle" style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; background-color: rgba(252, 185, 44, 0.1);">
                            <i class="ri-calendar-month-line font-size-24" style="color: #fcb92c;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Total Pasien Card --}}
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted font-size-13 mb-2">
                                <i class="ri-user-3-line"></i> Total Pasien
                            </p>
                            <h2 class="mb-0 font-weight-bold">{{ $totalPatients }}</h2>
                            <p class="text-muted font-size-12 mt-2 mb-0">Pasien unik</p>
                        </div>
                        <div class="avatar-sm rounded-circle" style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; background-color: rgba(255, 61, 96, 0.1);">
                            <i class="ri-user-3-line font-size-24" style="color: #ff3d60;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Target SOAP Progress (untuk PPDS biasa) --}}
    @if (!$isAdmin)
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title mb-0">Performa SOAP Bulan Ini</h5>
                        <span class="badge bg-primary">{{ round($weeklyProgress) }}%</span>
                    </div>
                    <div class="progress" style="height: 25px;">
                        <div class="progress-bar bg-success" role="progressbar" style="width: {{ $weeklyProgress }}%" aria-valuenow="{{ $weeklyProgress }}" aria-valuemin="0" aria-valuemax="100">
                            {{ round($weeklyProgress) }}%
                        </div>
                    </div>
                    <p class="text-muted font-size-12 mt-2 mb-0">Target: 20 SOAP per bulan (5 per minggu)</p>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Charts Section --}}
    <div class="row mb-4">
        {{-- Weekly Trend Chart --}}
        <div class="col-lg-6 mb-3">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light border-0">
                    <h5 class="card-title mb-0">
                        <i class="ri-line-chart-line"></i> Tren SOAP 7 Hari Terakhir
                    </h5>
                </div>
                <div class="card-body">
                    <div id="weeklyTrendChart"></div>
                </div>
            </div>
        </div>

        {{-- Diagnosis Distribution Table --}}
        <div class="col-lg-6 mb-3">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light border-0">
                    <h5 class="card-title mb-0">
                        <i class="ri-list-check"></i> Top Diagnosa
                    </h5>
                </div>
                <div class="card-body">
                    @if (count($diagnosisBreakdown['counts']) > 0)
                        <div class="table-responsive">
                            <table class="table table-borderless mb-0">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Diagnosa</th>
                                        <th class="text-end">Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($diagnosisBreakdown['names'] as $index => $name)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>
                                                <div>
                                                    <span class="badge bg-primary">{{ $diagnosisBreakdown['codes'][$index] ?? '-' }}</span>
                                                </div>
                                                <div class="mt-1">
                                                    <strong>{{ $name }}</strong>
                                                </div>
                                            </td>
                                            <td class="text-end">{{ $diagnosisBreakdown['counts'][$index] ?? 0 }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5 text-muted">
                            <i class="ri-information-line" style="font-size: 32px;"></i>
                            <p class="mb-0 mt-2">Belum ada data diagnosa untuk ditampilkan.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Doctor Activity (Admin Only) --}}
    @if ($isAdmin && count($doctorActivity) > 0)
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light border-0">
                    <h5 class="card-title mb-0">
                        <i class="ri-team-line"></i> Dokter Paling Aktif 7 Hari Terakhir
                    </h5>
                </div>
                <div class="card-body">
                    <div id="doctorActivityChart"></div>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Recent Activities --}}
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light border-0">
                    <h5 class="card-title mb-0">
                        <i class="ri-history-line"></i> Aktivitas SOAP Terbaru
                    </h5>
                </div>
                <div class="card-body">
                    @if ($recentSoaps->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Pasien</th>
                                        <th>Dokter</th>
                                        <th>Tanggal Kunjungan</th>
                                        <th>Status</th>
                                        <th class="text-end">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($recentSoaps as $soap)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-sm rounded-circle bg-primary bg-opacity-10" style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;">
                                                        <i class="ri-user-3-line" style="color: #5664d2;"></i>
                                                    </div>
                                                    <div class="ms-2">
                                                        <p class="mb-0">{{ $soap->patient_name_manual ?? $soap->patient?->name ?? '-' }}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $soap->doctor?->name ?? $soap->nama_dpjp ?? '-' }}</td>
                                            <td>
                                                <span class="font-size-12">{{ $soap->visit_date ? \Carbon\Carbon::parse($soap->visit_date)->format('d M Y') : '-' }}</span>
                                            </td>
                                            <td>
                                                <span class="badge bg-success-light text-success">Tercatat</span>
                                            </td>
                                            <td class="text-end">
                                                @if (Route::has('ppds.soap-logs.show'))
                                                    <a href="{{ route('ppds.soap-logs.show', $soap->id) }}" class="btn btn-sm btn-primary">
                                                        <i class="ri-eye-line"></i> Lihat
                                                    </a>
                                                @else
                                                    <button class="btn btn-sm btn-primary" disabled>
                                                        <i class="ri-eye-line"></i> Lihat
                                                    </button>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div style="font-size: 48px; margin-bottom: 10px;">
                                <i class="ri-inbox-line" style="color: #d1d3d4;"></i>
                            </div>
                            <p class="text-muted mb-0">Belum ada aktivitas SOAP</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

</div>

@endsection

@push('scripts')
    <script src="{{ URL::asset('build/libs/apexcharts/apexcharts.min.js') }}"></script>
    <script>
        function renderCharts() {
            console.log('[Charts] Checking ApexCharts availability...');
            
            if (typeof ApexCharts === 'undefined') {
                console.warn('[Charts] ApexCharts not ready yet, retrying in 200ms...');
                setTimeout(renderCharts, 200);
                return;
            }

            console.log('[Charts] ApexCharts ready, rendering...');

            try {
                // Sidebar toggle button
                var btn = document.getElementById('vertical-menu-btn');
                if (btn) {
                    btn.addEventListener('click', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        var body = document.body;
                        body.classList.toggle('sidebar-enable');
                        if (window.innerWidth >= 992) {
                            body.classList.toggle('vertical-collpsed');
                        } else {
                            body.classList.remove('vertical-collpsed');
                        }
                    }, true);
                }

                // Weekly Trend Chart
                try {
                    var weeklyOptions = {
                        series: [{
                            name: 'SOAP Entries',
                            data: @json($weeklyData['counts'])
                        }],
                        chart: {
                            type: 'line',
                            height: 300,
                            toolbar: {
                                show: false
                            },
                            sparkline: {
                                enabled: false
                            }
                        },
                        colors: ['#5664d2'],
                        stroke: {
                            width: 3,
                            lineCap: 'round'
                        },
                        xaxis: {
                            categories: @json($weeklyData['days']),
                            axisBorder: {
                                show: false
                            },
                            axisTicks: {
                                show: false
                            }
                        },
                        yaxis: {
                            min: 0
                        },
                        grid: {
                            borderColor: '#f1f5f7',
                            padding: {
                                bottom: 0
                            }
                        },
                        legend: {
                            show: false
                        },
                        fill: {
                            type: 'gradient',
                            gradient: {
                                shadeIntensity: 1,
                                opacityFrom: 0.45,
                                opacityTo: 0.05,
                                stops: [20, 100, 100, 100]
                            }
                        }
                    };

                    var weeklyChart = new ApexCharts(document.querySelector("#weeklyTrendChart"), weeklyOptions);
                    weeklyChart.render();
                    console.log('[Charts] Weekly trend chart rendered');
                } catch (e) {
                    console.error('[Charts] Error rendering weekly chart:', e.message);
                }

                // Diagnosis section is now rendered as a table, no chart needed

                // Doctor Activity Chart
                @if ($isAdmin && count($doctorActivity) > 0)
                    try {
                        var doctorOptions = {
                            series: [{
                                name: 'SOAP Entries 7 Hari Terakhir',
                                data: @json(array_column($doctorActivity, 'count'))
                            }],
                            chart: {
                                type: 'bar',
                                height: 350,
                                toolbar: {
                                    show: false
                                }
                            },
                            colors: ['#1cbb8c'],
                            xaxis: {
                                categories: @json(array_column($doctorActivity, 'name')),
                                axisBorder: {
                                    show: false
                                },
                                axisTicks: {
                                    show: false
                                }
                            },
                            yaxis: {
                                min: 0
                            },
                            grid: {
                                borderColor: '#f1f5f7'
                            },
                            legend: {
                                show: false
                            },
                            plotOptions: {
                                bar: {
                                    borderRadius: 5,
                                    dataLabels: {
                                        position: 'top'
                                    }
                                }
                            }
                        };

                        var doctorChart = new ApexCharts(document.querySelector("#doctorActivityChart"), doctorOptions);
                        doctorChart.render();
                        console.log('[Charts] Doctor activity chart rendered');
                    } catch (e) {
                        console.error('[Charts] Error rendering doctor chart:', e.message);
                    }
                @endif

            } catch (e) {
                console.error('[Charts] Unexpected error:', e.message);
            }
        }

        // Start rendering when DOM is ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', renderCharts);
        } else {
            renderCharts();
        }
    </script>
@endpush
