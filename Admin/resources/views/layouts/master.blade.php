<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>@yield('title') | Nazox - Admin & Dashboard Template</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <base href="<?php echo e(url('/')); ?>">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="Themesdesign" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ URL::asset('build/images/favicon.ico') }}">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
</head>
    @include('layouts.head-css')
            <!-- ... file-file CSS lainnya ... -->

        <!-- Sweet Alert-->
        <link href="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
                 
    </head>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  

<body data-sidebar="dark">
    <!-- Begin page -->
    <div id="layout-wrapper">
        @include('layouts.topbar')
        @include('layouts.sidebar')
        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid">
                    {{-- Notifikasi untuk akun yang belum diaktifkan --}}
                    @auth
                        @if(!Auth::user()->is_active && session('inactive_account_warning'))
                            @include('components.inactive-account-alert')
                        @endif
                    @endauth

                    @yield('content')
                </div>
            </div>
            @include('layouts.footer')
        </div>

    </div>

    @include('layouts.right-sidebar')
    @include('layouts.vendor-scripts')
        <!-- ... file-file JavaScript lainnya ... -->

    <!-- Sweet Alerts js -->
    <script src="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.js') }}"></script>

    <!-- Logout Confirmation Handler -->
    <script>
        function handleLogout(event) {
            event.preventDefault();
            
            // Show confirmation dialog
            Swal.fire({
                title: 'Logout?',
                text: 'Apakah Anda yakin ingin keluar dari aplikasi?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Logout',
                cancelButtonText: 'Batal',
                allowOutsideClick: false,
                didOpen: function() {
                    // Ensure the cancel button is focused
                    Swal.getCancelButton().focus();
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Submit the hidden logout form
                    document.getElementById('logout-form').submit();
                }
            });
        }
    </script>

    <!-- Ini adalah tempat script dari halaman lain akan dimasukkan -->
    @stack('scripts')
    
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <!-- App js -->
    <script src="{{ URL::asset('build/js/app.js') }}"></script>
    <script>
        window.addEventListener('pageshow', function(event) {
            var navEntries = performance.getEntriesByType && performance.getEntriesByType('navigation');
            var isBackForward = Array.isArray(navEntries) && navEntries.some(function(entry) {
                return entry.type === 'back_forward';
            });

            if (event.persisted || isBackForward) {
                window.location.reload();
            }
        });
    </script>
    {{-- =================================================================== --}}
{{--                  BLOK KODE UNTUK SWEETALERT                         --}}
{{-- Letakkan kode ini sebelum tag penutup </body> di layout utama Anda --}}
{{-- =================================================================== --}}

{{-- Pastikan Anda sudah memuat library SweetAlert2 di atas kode ini --}}
{{-- Contoh: <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> --}}

{{-- Script untuk menampilkan notifikasi SweetAlert standar --}}
@if (session('sweet_alert'))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const alertData = @json(session('sweet_alert'));
            Swal.fire({
                title: alertData.title,
                text: alertData.text,
                icon: alertData.icon,
                confirmButtonText: 'Mengerti'
            });
        });
    </script>
@endif

{{-- Script untuk menampilkan notifikasi SweetAlert dengan tombol redirect (untuk profil belum lengkap) --}}
@if (session('sweet_alert_redirect'))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const alertData = @json(session('sweet_alert_redirect'));
            Swal.fire({
                title: alertData.title,
                text: alertData.text,
                icon: alertData.icon,
                showCancelButton: alertData.showCancelButton || false,
                confirmButtonText: alertData.confirmButtonText || 'OK',
                cancelButtonText: alertData.cancelButtonText || 'Batal',
                confirmButtonColor: '#556ee6',
                cancelButtonColor: '#74788d',
            }).then((result) => {
                // Jika tombol konfirmasi diklik, arahkan ke URL yang ditentukan
                if (result.isConfirmed && alertData.redirectUrl) {
                    window.location.href = alertData.redirectUrl;
                }
            });
        });
    </script>
@endif
{{-- =================================================================== --}}
{{--                      AKHIR BLOK KODE SWEETALERT                     --}}
{{-- =================================================================== --}}


    {{-- ... script-script lain milik template Anda mungkin ada di sini ... --}}
    
</body>
</html>

</body>
</html>