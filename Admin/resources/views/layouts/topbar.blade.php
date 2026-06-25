<header id="page-topbar">
    <div class="navbar-header">
        <div class="d-flex">
            <!-- LOGO -->
            <div class="navbar-brand-box">
                <a href="index" class="logo logo-dark">
                    <span class="logo-sm">
                        <img src="{{ URL::asset('build/images/logo.png') }}" alt="logo-sm-dark" height="22">
                    </span>
                    <span class="logo-lg">
                        <img src="{{ URL::asset('build/images/logo_rssm.png') }}" alt="logo_rssm" height="40">
                    </span>
                </a>

                <a href="index" class="logo logo-light">
                    <span class="logo-sm">
                        <img src="{{ URL::asset('build/images/logo.png') }}" alt="logo-sm-light" height="22">
                    </span>
                    <span class="logo-lg">
                        <img src="{{ URL::asset('build/images/logo_rssm.png') }}" alt="logo-light" height="40">
                    </span>
                </a>
            </div>

            <button type="button" class="btn btn-sm px-3 font-size-24 header-item waves-effect" id="vertical-menu-btn">
                <i class="ri-menu-2-line align-middle"></i>
            </button>


        </div>

        <div class="d-flex">



            <div class="dropdown d-none d-sm-inline-block">
                <button type="button" class="btn header-item waves-effect"
                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <img class="" src="{{ URL::asset('build/images/flags/us.jpg') }}" alt="Header Language" height="16">
                </button>
                <div class="dropdown-menu dropdown-menu-end">
                    
                    <!-- item-->
                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                        <img src="{{ URL::asset('build/images/flags/spain.jpg') }}" alt="user-image" class="me-1" height="12"> <span class="align-middle">Spanish</span>
                    </a>

                    <!-- item-->
                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                        <img src="{{ URL::asset('build/images/flags/germany.jpg') }}" alt="user-image" class="me-1" height="12"> <span class="align-middle">German</span>
                    </a>

                    <!-- item-->
                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                        <img src="{{ URL::asset('build/images/flags/italy.jpg') }}" alt="user-image" class="me-1" height="12"> <span class="align-middle">Italian</span>
                    </a>

                    <!-- item-->
                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                        <img src="{{ URL::asset('build/images/flags/russia.jpg') }}" alt="user-image" class="me-1" height="12"> <span class="align-middle">Russian</span>
                    </a>
                </div>
            </div>

            <div class="dropdown d-none d-lg-inline-block ms-1">
                <button type="button" class="btn header-item noti-icon waves-effect" data-toggle="fullscreen">
                    <i class="ri-fullscreen-line"></i>
                </button>
            </div>

            <div class="dropdown d-inline-block">
                <button type="button" class="btn header-item noti-icon waves-effect" id="page-header-notifications-dropdown"
                      data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="ri-notification-3-line"></i>
                    <span class="noti-dot"></span>
                </button>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0"
                    aria-labelledby="page-header-notifications-dropdown">
                    <div class="p-3">
                        <div class="row align-items-center">
                            <div class="col">
                                <h6 class="m-0"> Notifications </h6>
                            </div>
                            <div class="col-auto">
                                <a href="#!" class="small"> View All</a>
                            </div>
                        </div>
                    </div>
                    <div data-simplebar style="max-height: 230px;">
                        <a href="" class="text-reset notification-item">
                            <div class="d-flex">
                                <div class="avatar-xs me-3">
                                    <span class="avatar-title bg-primary rounded-circle font-size-16">
                                        <i class="ri-shopping-cart-line"></i>
                                    </span>
                                </div>
                                <div class="flex-1">
                                    <h6 class="mb-1">Your order is placed</h6>
                                    <div class="font-size-12 text-muted">
                                        <p class="mb-1">If several languages coalesce the grammar</p>
                                        <p class="mb-0"><i class="mdi mdi-clock-outline"></i> 3 min ago</p>
                                    </div>
                                </div>
                            </div>
                        </a>
                        <a href="" class="text-reset notification-item">
                            <div class="d-flex">
                                <img src="{{ URL::asset('build/images/users/avatar-3.jpg') }}"
                                    class="me-3 rounded-circle avatar-xs" alt="user-pic">
                                <div class="flex-1">
                                    <h6 class="mb-1">James Lemire</h6>
                                    <div class="font-size-12 text-muted">
                                        <p class="mb-1">It will seem like simplified English.</p>
                                        <p class="mb-0"><i class="mdi mdi-clock-outline"></i> 1 hours ago</p>
                                    </div>
                                </div>
                            </div>
                        </a>
                        <a href="" class="text-reset notification-item">
                            <div class="d-flex">
                                <div class="avatar-xs me-3">
                                    <span class="avatar-title bg-success rounded-circle font-size-16">
                                        <i class="ri-checkbox-circle-line"></i>
                                    </span>
                                </div>
                                <div class="flex-1">
                                    <h6 class="mb-1">Your item is shipped</h6>
                                    <div class="font-size-12 text-muted">
                                        <p class="mb-1">If several languages coalesce the grammar</p>
                                        <p class="mb-0"><i class="mdi mdi-clock-outline"></i> 3 min ago</p>
                                    </div>
                                </div>
                            </div>
                        </a>

                        <a href="" class="text-reset notification-item">
                            <div class="d-flex">
                                <img src="{{ URL::asset('build/images/users/avatar-4.jpg') }}"
                                    class="me-3 rounded-circle avatar-xs" alt="user-pic">
                                <div class="flex-1">
                                    <h6 class="mb-1">Salena Layfield</h6>
                                    <div class="font-size-12 text-muted">
                                        <p class="mb-1">As a skeptical Cambridge friend of mine occidental.</p>
                                        <p class="mb-0"><i class="mdi mdi-clock-outline"></i> 1 hours ago</p>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="p-2 border-top">
                        <div class="d-grid">
                            <a class="btn btn-sm btn-link font-size-14 text-center" href="javascript:void(0)">
                                <i class="mdi mdi-arrow-right-circle me-1"></i> View More..
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="dropdown d-inline-block user-dropdown">
                <button type="button" class="btn header-item waves-effect profile-trigger" id="page-header-user-dropdown"
                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <img class="rounded-circle header-profile-user" src="{{ Auth::user()->getProfilePhotoUrl() }}"
                        alt="Header Avatar">
                    <span class="d-none d-xl-inline-block ms-1">
                        {{ Auth::user()->name }}
                        @if(!Auth::user()->is_active)
                            <span class="badge bg-warning text-dark ms-1" title="Akun pending aktivasi">
                                <i class="ri-time-line"></i> Pending
                            </span>
                        @else
                            <span class="badge bg-success ms-1" title="Akun aktif">
                                <i class="ri-check-line"></i> Aktif
                            </span>
                        @endif
                    </span>
                    <i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-end dropdown-menu-profile">
                    <div class="profile-dropdown-header p-3 text-white">
                        <div class="d-flex align-items-center">
                            <img src="{{ Auth::user()->getProfilePhotoUrl() }}" class="rounded-circle avatar-sm border border-white me-2"
                                alt="{{ Auth::user()->name }}">
                            <div>
                                <h6 class="mb-1">{{ Auth::user()->name }}</h6>
                                <p class="mb-0 text-white-75">{{ Auth::user()->email }}</p>
                            </div>
                        </div>
                        <div class="mt-3 d-flex justify-content-between align-items-center">
                            <span class="badge bg-light text-dark">{{ Auth::user()->is_active ? 'Akun Aktif' : 'Pending' }}</span>
                            <small class="text-white-75">
                                @if(Auth::user()->isSuperAdmin())
                                    Superadmin
                                @elseif(Auth::user()->isAdmin())
                                    Admin
                                @else
                                    User
                                @endif
                            </small>
                        </div>
                    </div>
                    <a href="{{ route('profile.edit') }}" class="dropdown-item d-flex align-items-center">
                        <i class="ri-user-line align-middle me-2"></i>
                        <span>Edit Profil</span>
                        <span class="badge bg-primary ms-auto">Baru</span>
                    </a>
                    <div class="dropdown-divider"></div>
                    <button type="button" class="dropdown-item text-danger w-100 text-start logout-action" id="btn-logout"
                        onclick="handleLogout(event)">
                        <i class="ri-shut-down-line align-middle me-2"></i>
                        <span>Logout</span>
                    </button>
                </div>
            </div>

            <!-- Hidden Logout Form -->
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>

            <div class="dropdown d-inline-block">
                <button type="button" class="btn header-item noti-icon right-bar-toggle waves-effect">
                    <i class="ri-settings-2-line"></i>
                </button>
            </div>
            
        </div>
    </div>
</header>

<style>
    .profile-trigger {
        border-radius: 50px;
        padding: 0.55rem 0.85rem;
        transition: transform .15s ease, box-shadow .15s ease;
    }

    .profile-trigger:hover {
        transform: translateY(-1px);
        box-shadow: 0 10px 24px rgba(0, 0, 0, 0.08);
    }

    .header-profile-user {
        object-fit: cover;
    }

    .dropdown-menu-profile {
        min-width: 300px;
        border-radius: 1rem;
        overflow: hidden;
        box-shadow: 0 24px 60px rgba(15, 23, 42, 0.12);
        border: 0;
    }

    .profile-dropdown-header {
        background: linear-gradient(135deg, rgba(91, 33, 182, 0.95), rgba(103, 58, 183, 0.92));
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    }

    .profile-dropdown-header .avatar-sm {
        width: 48px;
        height: 48px;
        object-fit: cover;
    }

    .dropdown-menu-profile .dropdown-item {
        padding: 0.85rem 1.2rem;
        color: #4b4f58;
        transition: background-color .2s ease, transform .2s ease;
    }

    .dropdown-menu-profile .dropdown-item:hover,
    .dropdown-menu-profile .dropdown-item.logout-action:hover {
        background-color: rgba(103, 58, 183, 0.08);
        transform: translateX(4px);
    }

    .dropdown-menu-profile .dropdown-divider {
        margin: 0.5rem 0;
        border-top-color: rgba(17, 24, 39, 0.08);
    }

    .dropdown-menu-profile .badge {
        font-size: 0.65rem;
        letter-spacing: 0.02em;
        text-transform: uppercase;
        padding: 0.4em 0.55em;
    }

    .profile-dropdown-header h6 {
        font-size: 0.95rem;
        font-weight: 700;
        margin-bottom: 0.125rem;
    }

    .profile-dropdown-header p {
        font-size: 0.78rem;
        opacity: 0.92;
    }

    .profile-dropdown-header small {
        font-size: 0.75rem;
    }
</style>