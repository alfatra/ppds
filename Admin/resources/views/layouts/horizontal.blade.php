<header id="page-topbar">
    <div class="navbar-header">
        <div class="d-flex">
            <!-- LOGO -->
            <div class="navbar-brand-box">
                <a href="index" class="logo logo-dark">
                    <span class="logo-sm">
                        <img src="{{ URL::asset('build/images/logo_rssm.png') }}" alt="logo-sm-dark" height="40">
                    </span>
                    <span class="logo-lg">
                        <img src="{{ URL::asset('build/images/logo_rssm.png') }}" alt="logo-dark" height="40">
                    </span>
                </a>

                <a href="index" class="logo logo-light">
                    <span class="logo-sm">
                        <img src="{{ URL::asset('build/images/logo_rssm.png') }}" alt="logo-sm-light" height="40">
                    </span>
                    <span class="logo-lg">
                        <img src="{{ URL::asset('build/images/logo_rssm.png') }}" alt="logo-light" height="40">
                    </span>
                </a>
            </div>

            <button type="button" class="btn btn-sm px-3 font-size-24 d-lg-none header-item" data-bs-toggle="collapse" data-bs-target="#topnav-menu-content">
                <i class="ri-menu-2-line align-middle"></i>
            </button>

            <!-- App Search-->
            <form class="app-search d-none d-lg-block">
                <div class="position-relative">
                    <input type="text" class="form-control" placeholder="Search...">
                    <span class="ri-search-line"></span>
                </div>
            </form>

            <div class="dropdown dropdown-mega d-none d-lg-block ms-2">
                <button type="button" class="btn header-item waves-effect" data-bs-toggle="dropdown" aria-haspopup="false" aria-expanded="false">
                    Mega Menu
                    <i class="mdi mdi-chevron-down"></i> 
                </button>
                <div class="dropdown-menu dropdown-megamenu">
                    <div class="row">
                        <div class="col-sm-8">
                
                            <div class="row">
                                <div class="col-md-4">
                                    <h5 class="font-size-14">UI Components</h5>
                                    <ul class="list-unstyled megamenu-list">
                                        <li>
                                            <a href="javascript:void(0);">Lightbox</a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0);">Range Slider</a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0);">Sweet Alert</a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0);">Rating</a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0);">Forms</a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0);">Tables</a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0);">Charts</a>
                                        </li>
                                    </ul>
                                </div>

                                <div class="col-md-4">
                                    <h5 class="font-size-14">Applications</h5>
                                    <ul class="list-unstyled megamenu-list">
                                        <li>
                                            <a href="javascript:void(0);">Ecommerce</a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0);">Calendar</a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0);">Email</a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0);">Projects</a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0);">Tasks</a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0);">Contacts</a>
                                        </li>
                                    </ul>
                                </div>

                                <div class="col-md-4">
                                    <h5 class="font-size-14">Extra Pages</h5>
                                    <ul class="list-unstyled megamenu-list">
                                        <li>
                                            <a href="javascript:void(0);">Light Sidebar</a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0);">Compact Sidebar</a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0);">Horizontal layout</a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0);">Maintenance</a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0);">Coming Soon</a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0);">Timeline</a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0);">FAQs</a>
                                        </li>
                                        
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="row">
                                <div class="col-sm-6">
                                    <h5 class="font-size-14">UI Components</h5>
                                    <ul class="list-unstyled megamenu-list">
                                        <li>
                                            <a href="javascript:void(0);">Lightbox</a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0);">Range Slider</a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0);">Sweetalert 2</a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0);">Rating</a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0);">Forms</a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0);">Tables</a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0);">Charts</a>
                                        </li>
                                    </ul>
                                </div>

                                <div class="col-sm-5">
                                    <div>
                                        <img src="{{ URL::asset('build/images/megamenu-img.png') }}" alt="megamenu-img" class="img-fluid mx-auto d-block">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="d-flex">

            <div class="dropdown d-inline-block d-lg-none ms-2">
                <button type="button" class="btn header-item noti-icon waves-effect" id="page-header-search-dropdown"
                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="ri-search-line"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0"
                    aria-labelledby="page-header-search-dropdown">
                    
                    <form class="p-3">
                        <div class="mb-3 m-0">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Search ...">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="submit"><i class="ri-search-line"></i></button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="dropdown d-inline-block">
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
                <button type="button" class="btn header-item noti-icon waves-effect"
                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="ri-apps-2-line"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                    <div class="px-lg-2">
                        <div class="row g-0">
                            <div class="col">
                                <a class="dropdown-icon-item" href="#">
                                    <img src="{{ URL::asset('build/images/brands/github.png') }}" alt="Github">
                                    <span>GitHub</span>
                                </a>
                            </div>
                            <div class="col">
                                <a class="dropdown-icon-item" href="#">
                                    <img src="{{ URL::asset('build/images/brands/bitbucket.png') }}" alt="bitbucket">
                                    <span>Bitbucket</span>
                                </a>
                            </div>
                            <div class="col">
                                <a class="dropdown-icon-item" href="#">
                                    <img src="{{ URL::asset('build/images/brands/dribbble.png') }}" alt="dribbble">
                                    <span>Dribbble</span>
                                </a>
                            </div>
                        </div>

                        <div class="row g-0">
                            <div class="col">
                                <a class="dropdown-icon-item" href="#">
                                    <img src="{{ URL::asset('build/images/brands/dropbox.png') }}" alt="dropbox">
                                    <span>Dropbox</span>
                                </a>
                            </div>
                            <div class="col">
                                <a class="dropdown-icon-item" href="#">
                                    <img src="{{ URL::asset('build/images/brands/mail_chimp.png') }}" alt="mail_chimp">
                                    <span>Mail Chimp</span>
                                </a>
                            </div>
                            <div class="col">
                                <a class="dropdown-icon-item" href="#">
                                    <img src="{{ URL::asset('build/images/brands/slack.png') }}" alt="slack">
                                    <span>Slack</span>
                                </a>
                            </div>
                        </div>
                    </div>
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
                <button type="button" class="btn header-item waves-effect" id="page-header-user-dropdown"
                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <img class="rounded-circle header-profile-user" src="{{ URL::asset('build/images/users/avatar-2.jpg') }}"
                        alt="Header Avatar">
                    <span class="d-none d-xl-inline-block ms-1">Kevin</span>
                    <i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-end">
                    <!-- item-->
                    <a class="dropdown-item" href="#"><i class="ri-user-line align-middle me-1"></i> Profile</a>
                    <a class="dropdown-item" href="#"><i class="ri-wallet-2-line align-middle me-1"></i> My Wallet</a>
                    <a class="dropdown-item d-block" href="#"><span class="badge bg-success float-end mt-1">11</span><i class="ri-settings-2-line align-middle me-1"></i> Settings</a>
                    <a class="dropdown-item" href="#"><i class="ri-lock-unlock-line align-middle me-1"></i> Lock screen</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item text-danger" href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();"><i class="ri-shut-down-line align-middle me-1 text-danger"></i> Logout</a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
            </div>

            <div class="dropdown d-inline-block">
                <button type="button" class="btn header-item noti-icon right-bar-toggle waves-effect">
                    <i class="ri-settings-2-line"></i>
                </button>
            </div>
            
        </div>
    </div>
</header>
    
    <div class="topnav">
        <div class="container-fluid">
            <nav class="navbar navbar-light navbar-expand-lg topnav-menu">
    
                <div class="collapse navbar-collapse" id="topnav-menu-content">
                    <ul class="navbar-nav">

                        <li class="nav-item">
                            <a class="nav-link" href="index">
                                <i class="ri-dashboard-line me-2"></i> Dashboard
                            </a>
                        </li>
    
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-uielement" role="button"
                            >
                                <i class="ri-pencil-ruler-2-line me-2"></i>UI Elements <div class="arrow-down"></div>
                            </a>

                            <div class="dropdown-menu mega-dropdown-menu px-2 dropdown-mega-menu-xl"
                                aria-labelledby="topnav-uielement">
                                <div class="row">
                                    <div class="col-lg-4">
                                        <div>
                                            <a href="ui-alerts" class="dropdown-item">Alerts</a>
                                            <a href="ui-buttons" class="dropdown-item">Buttons</a>
                                            <a href="ui-cards" class="dropdown-item">Cards</a>
                                            <a href="ui-carousel" class="dropdown-item">Carousel</a>
                                            <a href="ui-dropdowns" class="dropdown-item">Dropdowns</a>
                                            <a href="ui-grid" class="dropdown-item">Grid</a>
                                            <a href="ui-images" class="dropdown-item">Images</a>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div>
                                            <a href="ui-lightbox" class="dropdown-item">Lightbox</a>
                                            <a href="ui-modals" class="dropdown-item">Modals</a>
                                            <a href="ui-offcanvas" class="dropdown-item">Offcanvas</a>
                                            <a href="ui-rangeslider" class="dropdown-item">Range Slider</a>
                                            <a href="ui-roundslider" class="dropdown-item">Round slider</a>
                                            <a href="ui-session-timeout" class="dropdown-item">Session Timeout</a>
                                            <a href="ui-progressbars" class="dropdown-item">Progress Bars</a>                                           
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div>
                                            <a href="ui-sweet-alert" class="dropdown-item">Sweetalert 2</a>
                                            <a href="ui-tabs-accordions" class="dropdown-item">Tabs & Accordions</a>
                                            <a href="ui-typography" class="dropdown-item">Typography</a>
                                            <a href="ui-video" class="dropdown-item">Video</a>
                                            <a href="ui-general" class="dropdown-item">General</a>
                                            <a href="ui-rating" class="dropdown-item">Rating</a>
                                            <a href="ui-notifications" class="dropdown-item">Notifications</a>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </li>
    
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-apps" role="button"
                            >
                                <i class="ri-apps-2-line me-2"></i>Apps <div class="arrow-down"></div>
                            </a>
                            <div class="dropdown-menu" aria-labelledby="topnav-apps">

                                <a href="calendar" class="dropdown-item">Calendar</a>
                                <a href="apps-chat" class="dropdown-item">Chat</a>
                                <div class="dropdown">
                                    <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-email"
                                        role="button">
                                        Email <div class="arrow-down"></div>
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="topnav-email">
                                        <a href="email-inbox" class="dropdown-item">Inbox</a>
                                        <a href="email-read" class="dropdown-item">Read Email</a>
                                    </div>
                                </div>
                                <div class="dropdown">
                                    <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-ecommerce"
                                        role="button">
                                        Ecommerce <div class="arrow-down"></div>
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="topnav-ecommerce">
                                        <a href="ecommerce-products" class="dropdown-item">Products</a>
                                        <a href="ecommerce-product-detail" class="dropdown-item">Product Detail</a>
                                        <a href="ecommerce-orders" class="dropdown-item">Orders</a>
                                        <a href="ecommerce-customers" class="dropdown-item">Customers</a>
                                        <a href="ecommerce-cart" class="dropdown-item">Cart</a>
                                        <a href="ecommerce-checkout" class="dropdown-item">Checkout</a>
                                        <a href="ecommerce-shops" class="dropdown-item">Shops</a>
                                        <a href="ecommerce-add-product" class="dropdown-item">Add Product</a>
                                    </div>
                                </div>

                                <a href="apps-kanban-board" class="dropdown-item">Kanban Board</a>
                            </div>
                        </li>
    
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-components" role="button"
                            >
                                <i class="ri-stack-line me-2"></i>Components <div class="arrow-down"></div>
                            </a>
                            <div class="dropdown-menu" aria-labelledby="topnav-components">
                                <div class="dropdown">
                                    <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-form"
                                        role="button">
                                        Forms <div class="arrow-down"></div>
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="topnav-form">
                                        <a href="form-elements" class="dropdown-item">Elements</a>
                                        <a href="form-validation" class="dropdown-item">Validation</a>
                                        <a href="form-advanced" class="dropdown-item">Advanced Plugins</a>
                                        <a href="form-editors" class="dropdown-item">Editors</a>
                                        <a href="form-uploads" class="dropdown-item">File Upload</a>
                                        <a href="form-xeditable" class="dropdown-item">Xeditable</a>
                                        <a href="form-wizard" class="dropdown-item">Wizard</a>
                                        <a href="form-mask" class="dropdown-item">Mask</a>
                                    </div>
                                </div>
                                <div class="dropdown">
                                    <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-table"
                                        role="button">
                                        Tables <div class="arrow-down"></div>
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="topnav-table">
                                        <a href="tables-basic" class="dropdown-item">Basic Tables</a>
                                        <a href="tables-datatable" class="dropdown-item">Data Tables</a>
                                        <a href="tables-responsive" class="dropdown-item">Responsive Table</a>
                                        <a href="tables-editable" class="dropdown-item">Editable Table</a>
                                    </div>
                                </div>
                                <div class="dropdown">
                                    <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-charts"
                                        role="button">
                                        Charts <div class="arrow-down"></div>
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="topnav-charts">
                                        <a href="charts-apex" class="dropdown-item">Apex charts</a>
                                        <a href="charts-chartjs" class="dropdown-item">Chartjs</a>
                                        <a href="charts-flot" class="dropdown-item">Flot Chart</a>
                                        <a href="charts-knob" class="dropdown-item">Jquery Knob Chart</a>
                                        <a href="charts-sparkline" class="dropdown-item">Sparkline Chart</a>
                                    </div>
                                </div>
                                <div class="dropdown">
                                    <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-icons"
                                        role="button">
                                        Icons <div class="arrow-down"></div>
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="topnav-icons">
                                        <a href="icons-remix" class="dropdown-item">Remix Icons</a>
                                        <a href="icons-materialdesign" class="dropdown-item">Material Design</a>
                                        <a href="icons-dripicons" class="dropdown-item">Dripicons</a>
                                        <a href="icons-fontawesome" class="dropdown-item">Font awesome 5</a>
                                    </div>
                                </div>
                                <div class="dropdown">
                                    <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-map"
                                        role="button">
                                        Maps <div class="arrow-down"></div>
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="topnav-map">
                                        <a href="maps-google" class="dropdown-item">Google Maps</a>
                                        <a href="maps-vector" class="dropdown-item">Vector Maps</a>
                                    </div>
                                </div>
                            </div>
                        </li>
    
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-more" role="button"
                            >
                                <i class="ri-file-copy-2-line me-2"></i>Pages <div class="arrow-down"></div>
                            </a>
                            <div class="dropdown-menu" aria-labelledby="topnav-more">
                                <div class="dropdown">
                                    <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-auth"
                                        role="button">
                                        Authentication <div class="arrow-down"></div>
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="topnav-auth">
                                        <a href="auth-login" class="dropdown-item">Login</a>
                                        <a href="auth-register" class="dropdown-item">Register</a>
                                        <a href="auth-recoverpw" class="dropdown-item">Recover Password</a>
                                        <a href="auth-lock-screen" class="dropdown-item">Lock Screen</a>
                                    </div>
                                </div>
                                <div class="dropdown">
                                    <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-utility"
                                        role="button">
                                        Utility <div class="arrow-down"></div>
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="topnav-utility">
                                        <a href="pages-starter" class="dropdown-item">Starter Page</a>
                                        <a href="pages-maintenance" class="dropdown-item">Maintenance</a>
                                        <a href="pages-comingsoon" class="dropdown-item">Coming Soon</a>
                                        <a href="pages-timeline" class="dropdown-item">Timeline</a>
                                        <a href="pages-faqs" class="dropdown-item">FAQs</a>
                                        <a href="pages-pricing" class="dropdown-item">Pricing</a>
                                        <a href="pages-404" class="dropdown-item">Error 404</a>
                                        <a href="pages-500" class="dropdown-item">Error 500</a>
                                    </div>
                                </div>
                            </div>
                        </li>

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-layout" role="button">
                                <i class="ri-layout-3-line me-2"></i><span key="t-layouts">Layouts</span> <div class="arrow-down"></div>
                            </a>
                            <div class="dropdown-menu" aria-labelledby="topnav-layout">
                                <div class="dropdown">
                                    <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-layout-verti"
                                        role="button">
                                        <span key="t-vertical">Vertical</span> <div class="arrow-down"></div>
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="topnav-layout-verti">
                                        <a href="layouts-light-sidebar" class="dropdown-item" key="t-light-sidebar">Light Sidebar</a>
                                        <a href="layouts-compact-sidebar" class="dropdown-item" key="t-compact-sidebar">Compact Sidebar</a>
                                        <a href="layouts-icon-sidebar" class="dropdown-item" key="t-icon-sidebar">Icon Sidebar</a>
                                        <a href="layouts-boxed" class="dropdown-item" key="t-boxed-width">Boxed Width</a>
                                        <a href="layouts-preloader" class="dropdown-item" key="t-preloader">Preloader</a>
                                        <a href="layouts-colored-sidebar" class="dropdown-item" key="t-colored-sidebar">Colored Sidebar</a>
                                    </div>
                                </div>
    
                                <div class="dropdown">
                                    <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-layout-hori"
                                        role="button">
                                        <span key="t-horizontal">Horizontal</span> <div class="arrow-down"></div>
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="topnav-layout-hori">
                                        <a href="layouts-horizontal" class="dropdown-item" key="t-horizontal">Horizontal</a>
                                        <a href="layouts-hori-topbar-light" class="dropdown-item" key="t-topbar-light">Topbar light</a>
                                        <a href="layouts-hori-boxed-width" class="dropdown-item" key="t-boxed-width">Boxed width</a>
                                        <a href="layouts-hori-preloader" class="dropdown-item" key="t-preloader">Preloader</a>
                                        <a href="layouts-hori-colored-header" class="dropdown-item" key="t-colored-topbar">Colored Header</a>
                                    </div>
                                </div>
                            </div>
                        </li>
    
                    </ul>
                </div>
            </nav>
        </div>
    </div>