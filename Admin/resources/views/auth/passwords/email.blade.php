@extends('layouts.master-without-nav')
@section('title')
    Forget Password
@endsection
@section('content')
    <div>
        <div class="container-fluid p-0">
            <div class="row g-0">
                <div class="col-lg-4">
                    <div class="authentication-page-content p-4 d-flex align-items-center min-vh-100">
                        <div class="w-100">
                            <div class="row justify-content-center">
                                <div class="col-lg-9">
                                    <div>
                                        <div class="text-center">
                                            <div>
                                                <a href="/index" class="">
                                                        <img src="{{ URL::asset('build/images/logo_rssm.png') }}" alt=""
                                                        height="20" class="auth-logo logo-dark mx-auto">
                                                    <img src="{{ URL::asset('build/images/logo-light.png') }}"
                                                        alt="" height="20" class="auth-logo logo-light mx-auto">
                                                </a>
                                            </div>

                                            <h4 class="font-size-18 mt-4">Reset Password</h4>
                                            <p class="text-muted">Reset your password to Nazox.</p>
                                        </div>

                                        <div class="p-2 mt-5">
                                            <div class="alert alert-success mb-4" role="alert">
                                                Enter your Email and instructions will be sent to you!
                                            </div>
                                            <form method="POST" action="{{ route('password.email') }}">
                                                @csrf
                                                <div class="auth-form-group-custom mb-4">
                                                    <i class="ri-mail-line auti-custom-input-icon"></i>
                                                    <label for="useremail">Email <span class="text-danger">*</span></label>
                                                    <input type="email"
                                                        class="form-control @error('email') is-invalid @enderror"
                                                        name="email" value="{{ old('email') }}" required
                                                        autocomplete="email" autofocus id="useremail"
                                                        placeholder="Enter email">
                                                    @error('email')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>

                                                <div class="mt-4 text-center">
                                                    <button class="btn btn-primary w-100 waves-effect waves-light"
                                                        type="submit">Reset</button>
                                                </div>
                                            </form>
                                        </div>

                                        <div class="mt-5 text-center">
                                            <p>Don't have an account ? <a href="{{ route('register') }}"
                                                    class="fw-medium text-primary"> Sign Up </a> </p>
                                            <p>© {{ date('Y') }} Nazox. Crafted with <i
                                                    class="mdi mdi-heart text-danger"></i> by Themesdesign</p>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="authentication-bg">
                        <div class="bg-overlay"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
