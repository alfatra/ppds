<!-- ========== Left Sidebar Start ========== -->
<div class="vertical-menu">

    <div data-simplebar class="h-100">

        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">
                <li class="menu-title">Utama</li>

                <li>
                    <a href="index" class="waves-effect">
                        <i class="ri-dashboard-line"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                @if(in_array(Auth::user()->role, ['admin', 'superadmin']))
                <li class="menu-title">Administrator</li>
                
                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="ri-database-2-line"></i>
                        <span>Master Data</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{ route('admin.users.index') }}">Pengguna Sistem</a></li>
                        <li><a href="{{ route('ppds.index') }}">Data Peserta PPDS</a></li>
                        <li><a href="{{ route('admin.medical-activities.index') }}">Tindakan Medis</a></li>
                    </ul>
                </li>
                @endif

                <li class="menu-title">Aktivitas PPDS</li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="ri-book-read-line"></i>
                        <span>Logbook</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{ route('daily-activities.index') }}">Kegiatan Harian</a></li>
                        <li><a href="{{ route('ppds.soap-logs.index') }}">Laporan SOAP</a></li>
                       
                    </ul>
                </li>

                <li>
                    <a href="{{ route('attendance.index') }}" class="waves-effect">
                        <i class="ri-fingerprint-line"></i>
                        <span>Absensi</span>
                    </a>
                </li>



            </ul>
        </div>
        <!-- Sidebar -->
    </div>
</div>
<!-- Left Sidebar End -->