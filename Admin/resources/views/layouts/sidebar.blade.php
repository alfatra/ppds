<!-- ========== Left Sidebar Start ========== -->
<div class="vertical-menu">

    <div data-simplebar class="h-100">

        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">
                <li class="menu-title">Menu</li>

                <li>
                    <a href="index" class="waves-effect">
                        <i class="ri-dashboard-line"></i><span class="badge rounded-pill bg-success float-end">3</span>
                        <span>Dashboard</span>
                    </a>
                </li>

                {{-- Menu Data PPDS hanya untuk admin dan superadmin --}}
                @if(in_array(Auth::user()->role, ['admin', 'superadmin']))
                <li>
                    <a href="{{ route('ppds.index') }}" class="waves-effect">
                        <i class="ri-user-add-line"></i>
                        <span>Data PPDS</span>
                    </a>
                </li>
                @endif

                {{-- Menu Manajemen Pengguna hanya untuk admin dan superadmin --}}
                @if(in_array(Auth::user()->role, ['admin', 'superadmin']))
                    <li>
                        <a href="{{ route('admin.users.index') }}" class="waves-effect">
                            <i class="ri-user-settings-line"></i>
                            <span>Manajemen Pengguna</span>
                        </a>
                    </li>
                    @endif

            <li>
                <a href="javascript: void(0);" class="has-arrow waves-effect">
                    <i class="fas fa-notes-medical"></i>
                    <span>Loogbook</span>
                </a>
                <ul class="sub-menu" aria-expanded="false">
                    <li><a href="#">Kegiatan Harian</a></li>
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


                <li>
                    <a href="calendar" class=" waves-effect">
                        <i class="ri-calendar-2-line"></i>
                        <span>Calendar</span>
                    </a>
                </li>

                <li>
                    <a href="apps-chat" class=" waves-effect">
                        <i class="ri-chat-1-line"></i>
                        <span>Chat</span>
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

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="ri-profile-line"></i>
                        <span>Utility</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="pages-starter">Starter Page</a></li>
                        <li><a href="pages-maintenance">Maintenance</a></li>
                        <li><a href="pages-comingsoon">Coming Soon</a></li>
                        <li><a href="pages-timeline">Timeline</a></li>
                        <li><a href="pages-faqs">FAQs</a></li>
                        <li><a href="pages-pricing">Pricing</a></li>
                        <li><a href="pages-404">Error 404</a></li>
                        <li><a href="pages-500">Error 500</a></li>
                    </ul>
                </li>

            </ul>
        </div>
        <!-- Sidebar -->
    </div>
</div>
<!-- Left Sidebar End -->