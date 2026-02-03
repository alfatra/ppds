@extends('layouts.master-without-nav')
@section('title')
    Login
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
                                                <a href="/index" class="authentication-logo">
                                                    <img src="{{ URL::asset('build/images/logo-dark.png') }}" alt="" height="20"
                                                        class="auth-logo logo-dark mx-auto">
                                                    <img src="{{ URL::asset('build/images/logo-light.png') }}" alt="" height="20"
                                                        class="auth-logo logo-light mx-auto">
                                                </a>
                                            </div>

                                            <h4 class="font-size-18 mt-4">Welcome Back !</h4>
                                            <p class="text-muted">Sign in to continue to Nazox.</p>
                                        </div>

                                        <div class="p-2 mt-5">
                                            <form method="POST" action="{{ route('login') }}">
                                                @csrf

                                                <div class="mb-3 auth-form-group-custom mb-4">
                                                    <i class="ri-user-2-line auti-custom-input-icon"></i>
                                                    <label for="email" class="fw-semibold">Email <span class="text-danger">*</span></label>
                                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                                        name="email" id="email" value="admin@themesdesign.com" required
                                                        autocomplete="email" autofocus placeholder="Enter email">
                                                    @error('email')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>

                                                <div class="mb-3 auth-form-group-custom mb-4">
                                                    <i class="ri-lock-2-line auti-custom-input-icon"></i>
                                                    <label for="userpassword">Password <span class="text-danger">*</span></label>
                                                    <input type="password"
                                                        class="form-control @error('password') is-invalid @enderror"
                                                        id="userpassword" name="password" required value="12345678"
                                                        autocomplete="current-password" placeholder="Enter password">
                                                    @error('password')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>

                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" id="customControlInline"
                                                        name="remember" id="remember"
                                                        {{ old('remember') ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="customControlInline">Remember
                                                        me</label>
                                                </div>

                                                <div class="mt-4 text-center">
                                                    <button class="btn btn-primary w-md waves-effect waves-light"
                                                        type="submit">Log In</button>
                                                </div>

                                                @if (Route::has('password.request'))
                                                    <div class="mt-4 text-center">
                                                        <a href="{{ route('password.request') }}" class="text-muted"><i
                                                                class="mdi mdi-lock me-1"></i> Forgot your password?</a>
                                                    </div>
                                                @endif
                                            </form>
                                        </div>

                                        <div class="mt-5 text-center">
                                            <p>Don't have an account ? <a href="{{ route('register') }}"
                                                    class="fw-medium text-primary"> Register </a> </p>
                                            <p>©
                                                {{ date('Y') }} Nazox. Crafted with <i
                                                    class="mdi mdi-heart text-danger"></i> by Themesdesign
                                            </p>
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
