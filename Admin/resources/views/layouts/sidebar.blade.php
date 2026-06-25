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
                        <li><a href="#">Tindakan Medis</a></li>
                    </ul>
                </li>

                <li>
                    <a href="{{ route('attendance.index') }}" class="waves-effect">
                        <i class="ri-fingerprint-line"></i>
                        <span>Absensi</span>
                    </a>
                </li>

                <li class="menu-title">Aplikasi Tambahan</li>

                <li>
                    <a href="calendar" class="waves-effect">
                        <i class="ri-calendar-2-line"></i>
                        <span>Kalender</span>
                    </a>
                </li>

                <li>
                    <a href="apps-chat" class="waves-effect">
                        <i class="ri-chat-1-line"></i>
                        <span>Pesan Chat</span>
                    </a>
                </li>
                
                {{-- Email menu removed per request --}}

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="ri-layout-3-line"></i>
                        <span>Layouts</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="true">
                        <li>
                            <a href="javascript: void(0);" class="has-arrow">Vertical</a>
                            <ul class="sub-menu" aria-expanded="true">
                                <li><a href="layouts-light-sidebar">Light Sidebar</a></li>
                                <li><a href="layouts-compact-sidebar">Compact Sidebar</a></li>
                                <li><a href="layouts-icon-sidebar">Icon Sidebar</a></li>
                                <li><a href="layouts-boxed">Boxed Layout</a></li>
                                <li><a href="layouts-preloader">Preloader</a></li>
                                <li><a href="layouts-colored-sidebar">Colored Sidebar</a></li>
                            </ul>
                        </li>

                        <li>
                            <a href="javascript: void(0);" class="has-arrow">Horizontal</a>
                            <ul class="sub-menu" aria-expanded="true">
                                <li><a href="layouts-horizontal">Horizontal</a></li>
                                <li><a href="layouts-hori-topbar-light">Topbar light</a></li>
                                <li><a href="layouts-hori-boxed-width">Boxed width</a></li>
                                <li><a href="layouts-hori-preloader">Preloader</a></li>
                                <li><a href="layouts-hori-colored-header">Colored Header</a></li>
                            </ul>
                        </li>
                    </ul>
                </li>

                <li class="menu-title">Pages</li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="ri-account-circle-line"></i>
                        <span>Authentication</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="auth-login">Login</a></li>
                        <li><a href="auth-register">Register</a></li>
                        <li><a href="auth-recoverpw">Recover Password</a></li>
                        <li><a href="auth-lock-screen">Lock Screen</a></li>
                    </ul>
                </li>

                <!-- Utility menu removed per user request -->

            </ul>
        </div>
        <!-- Sidebar -->
    </div>
</div>
<!-- Left Sidebar End -->