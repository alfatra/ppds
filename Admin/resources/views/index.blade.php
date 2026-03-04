@extends('layouts.master')
@section('title') Dashbord @endsection
@push('css')
    <!-- jquery.vectormap css -->
    <link href="{{ URL::asset('build/libs/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.css') }}" rel="stylesheet" type="text/css" />

    <!-- DataTables -->
    <link href="{{ URL::asset('build/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    
    <!-- Responsive datatable examples -->
    <link href="{{ URL::asset('build/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
@endpush
@section('content')

<x-breadcrumb pagetitle="Nazox" title="Dashboard" />

<div class="homepage-content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-xl-8">
        <div class="row">
          <div class="col-md-4">
            <div class="card">
              <div class="card-body">
                <div class="d-flex">
                  <div class="flex-1 overflow-hidden">
                    <p class="text-truncate font-size-14 mb-2">Jumlah Pasien Rawat Inap</p>
                    <h4 class="mb-0">250</h4>
                  </div>
                  <div class="text-primary ms-auto">
                    <i class="fas fa-procedures font-size-24"></i>
                  </div>
                </div>
              </div>

              <div class="card-body border-top py-3">
                <div class="text-truncate">
                  <span class="badge badge-soft-success font-size-11"><i class="mdi mdi-menu-up"> </i> 2.4% </span>
                  <span class="text-muted ms-2">From previous period</span>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="card">
              <div class="card-body">
                <div class="d-flex">
                  <div class="flex-1 overflow-hidden">
                    <p class="text-truncate font-size-14 mb-2">Jumlah Pasien Rawat Jalan</p>
                    <h4 class="mb-0">1.235</h4>
                  </div>
                  <div class="text-primary ms-auto">
                    <i class="fas fa-walking font-size-24"></i>
                  </div>
                </div>
              </div>
              <div class="card-body border-top py-3">
                <div class="text-truncate">
                  <span class="badge badge-soft-success font-size-11"><i class="mdi mdi-menu-up"> </i> 2.4% </span>
                  <span class="text-muted ms-2">From previous period</span>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="card">
              <div class="card-body">
                <div class="d-flex">
                  <div class="flex-1 overflow-hidden">
                    <p class="text-truncate font-size-14 mb-2">Bed Tersedia</p>
                    <h4 class="mb-0">10 / 260</h4>
                  </div>
                  <div class="text-primary ms-auto">
                    <i class="fas fa-bed font-size-24"></i>
                  </div>
                </div>
              </div>
              <div class="card-body border-top py-3">
                <div class="text-truncate">
                  <span class="badge badge-soft-success font-size-11"><i class="mdi mdi-menu-up"> </i> 2.4% </span>
                  <span class="text-muted ms-2">From previous period</span>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- End Informasi Umum -->

        <!--Start Jadwal Operasi -->
        <div class="card">
          <div class="card-body">
            <h4 class="card-title mb-4">Jadwal Kamar Operasi</h4>
            <div>
              <table id="datatable" class="informasijadwaloperasi table table-bordered table-hover dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
              <thead>
                <tr>
                  <th>Kamar Operasi</th>
                  <th>No. Rekam Medis</th>
                  <th><abbr title="Dokter Penanggung Jawab Pasien">DPJP</abbr></th>
                  <th>Status</th>
                </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>Kamar Operasi A</td>
                    <td>00-46-47-40</td>
                    <td>dr. Dewi Yusuf, Sp. B, SubSpe, BE (K)</td>
                    <td>Selesai Operasi</td>
                  </tr>
                  <tr>
                    <td>Kamar Operasi B</td>
                    <td>00-60-08-37</td>
                    <td>dr. Almeida Handayani, Sp.B</td>
                    <td>Selesai Operasi</td>
                  </tr>
                  <tr>
                    <td>Kamar Operasi C</td>
                    <td>00-60-18-66</td>
                    <td>dr. Oriano Yanan, Sp.B</td>
                    <td>Menunggu Operasi</td>
                  </tr>
                  <tr>
                    <td>Kamar Operasi D</td>
                    <td>00-60-15-06</td>
                    <td>dr. Abdul Malik Yusuf, Sp.B Uro</td>
                    <td>Selesai Operasi</td>
                  </tr>
              </tbody>
            </table>
            </div>
          </div>

          <div class="card-body border-top text-center">
            <div class="row">
              <div class="col-sm-3">
                <div class="mt-4 mt-sm-0">
                  <p class="mb-2 text-muted text-truncate">
                    <i class="mdi mdi-circle text-primary font-size-10 me-1"></i>
                    Kamar Operasi A
                  </p>
                  <div class="d-inline-flex">
                    <h5 class="mb-0">2 Antrian</h5>
                  </div>
                </div>
              </div>

              <div class="col-sm-3">
                <div class="mt-4 mt-sm-0">
                  <p class="mb-2 text-muted text-truncate">
                    <i class="mdi mdi-circle text-success font-size-10 me-1"></i>
                    Kamar Operasi B
                  </p>
                  <div class="d-inline-flex">
                    <h5 class="mb-0">1 Antrian</h5>
                  </div>
                </div>
              </div>

              <div class="col-sm-3">
                <div class="mt-4 mt-sm-0">
                  <p class="mb-2 text-muted text-truncate">
                    <i class="mdi mdi-circle text-warning font-size-10 me-1"></i>
                    Kamar Operasi C
                  </p>
                  <div class="d-inline-flex">
                    <h5 class="mb-0">4 Antrian</h5>
                  </div>
                </div>
              </div>

              <div class="col-sm-3">
                <div class="mt-4 mt-sm-0">
                  <p class="mb-2 text-muted text-truncate">
                    <i class="mdi mdi-circle text-info font-size-10 me-1"></i>
                    Kamar Operasi D
                  </p>
                  <div class="d-inline-flex">
                    <h5 class="mb-0">3 Antrian</h5>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-xl-4">
        <div class="card">
          <div class="card-body">
            <h4 class="card-title mb-4">Informasi Tempat Tidur</h4>
            <table id="datatable" class="informasitempattidur table table-bordered table-hover dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
              <thead>
                <tr>
                  <th>Kelas</th>
                  <th>Kapasitas</th>
                  <th>Terisi</th>
                  <th>Tersedia</th>
                </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>VVIP</td>
                    <td>12</td>
                    <td>6</td>
                    <td>6</td>
                  </tr>
                  <tr>
                    <td>VIP</td>
                    <td>11 </td>
                    <td>10 </td>
                    <td>1</td>
                  </tr>
                  <tr>
                    <td>PICU</td>
                    <td>8 </td>
                    <td>1 </td>
                    <td>7</td>
                  </tr>
                  <tr>
                    <td>NICU</td>
                    <td>2 </td>
                    <td>0 </td>
                    <td>2</td>
                  </tr>
                  <tr>
                    <td>KELAS III</td>
                    <td>56 </td>
                    <td>34 </td>
                    <td>22</td>
                  </tr>
                  <tr>
                    <td>KELAS II</td>
                    <td>51 </td>
                    <td>27 </td>
                    <td>24</td>
                  </tr>
                  <tr>
                    <td>KELAS I</td>
                    <td>70 </td>
                    <td>56 </td>
                    <td>14</td>
                  </tr>
                  <tr>
                    <td>RUANG ISOLASI</td>
                    <td>25 </td>
                    <td>17 </td>
                    <td>8</td>
                  </tr>
                  <tr>
                    <td>ICU</td>
                    <td>4 </td>
                    <td>0 </td>
                    <td>4</td>
                  </tr>
                  <tr>
                    <td>ICCU</td>
                    <td>5 </td>
                    <td>4 </td>
                    <td>1</td>
                  </tr>
                  <tr>
                    <td>HCU</td>
                    <td>6 </td>
                    <td>3 </td>
                    <td>3</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
    </div>
  </div>
</div>
<!-- End Page-content -->

@endsection
@push('script')
    <!-- apexcharts -->
    <script src="{{ URL::asset('build/libs/apexcharts/apexcharts.min.js') }}"></script>

    <!-- jquery.vectormap map -->
    <script src="{{ URL::asset('build/libs/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.min.js') }}"></script>
    <script src="{{ URL::asset('build/libs/admin-resources/jquery.vectormap/maps/jquery-jvectormap-us-merc-en.js') }}"></script>

    <!-- Required datatable js -->
    <script src="{{ URL::asset('build/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('build/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    
    <!-- Responsive examples -->
    <script src="{{ URL::asset('build/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ URL::asset('build/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') }}"></script>

    <script src="{{ URL::asset('build/js/pages/dashboard.init.js') }}"></script>

    <!-- App js -->
    <script src="{{ URL::asset('build/js/app.js') }}"></script>
@endpush