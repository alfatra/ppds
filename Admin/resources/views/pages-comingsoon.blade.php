@extends('layouts.master-without-nav')
@section('title')
    Commin Soon
@endsection
@section('content')
        <div>
            <div class="container-fluid p-0">
                <div class="row g-0">
                    <div class="col-lg-4">
                        <div class="authentication-page-content p-4 d-flex align-items-center min-vh-100">
                            <div class="w-100 py-4">
                                <div class="row justify-content-center">
                                    <div class="col-lg-9">
                                        <div>
                                            <div class="text-center">
                                                <div>
                                                    <a href="index" class="authentication-logo">
                                                        <img src="{{ URL::asset('build/images/logo_rssm.png') }}" alt="" height="20" class="auth-logo logo-dark mx-auto">
                                                        <img src="{{ URL::asset('build/images/logo-light.png') }}" alt="" height="20" class="auth-logo logo-light mx-auto">
                                                    </a>
                                                </div>
    
                                                <h4 class="font-size-18 mt-4">Let's get started with Nazox</h4>
                                                <p class="text-muted">It will be as simple as Occidental in fact it will be Occidental.</p>
                                            </div>

                                            <div class="p-2 mt-5">
                                                <div data-countdown="2025/08/04" class="counter-number"></div>
                                            </div>
                                    
                                            <div class="input-section mt-5">
                                                <div class="row">
                                                    <div class="col">
                                                        <div class="position-relative">
                                                            <input type="email" class="form-control" placeholder="Enter email address...">
                                                        </div>
                                                    </div>
                                                    <div class="col-auto">
                                                        <button type="submit" class="btn btn-primary w-md waves-effect waves-light">Subscribe</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <div class="authentication-bg comingsoon-bg">
                            <div class="bg-overlay"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    
    @endsection
    @push('script')
    <!-- Plugins js-->
    <script src="{{ URL::asset('build/libs/jquery-countdown/jquery.countdown.min.js') }}"></script>

    <!-- Countdown js -->
    <script src="{{ URL::asset('build/js/pages/coming-soon.init.js') }}"></script>
    <script src="{{ URL::asset('build/js/app.js') }}"></script>
@endpush
