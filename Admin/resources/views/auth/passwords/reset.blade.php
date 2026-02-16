@extends('layouts.master-without-nav')
@section('title')
    Reset Password
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
                                                    <img src="{{ URL::asset('build/images/logo-dark.png') }}" alt=""
                                                        height="60" class="auth-logo logo-dark mx-auto">
                                                    <img src="{{ URL::asset('build/images/logo-sm-light.png') }}" alt=""
                                                        height="20" class="auth-logo logo-light mx-auto">
                                                </a>
                                            </div>
                                            <h4 class="font-size-18 mt-4">Reset Password</h4>
                                            <p class="text-muted">Reset password Anda.</p>
                                        </div>
                                        <div class="p-2 mt-5">
                                            <form method="POST" action="{{ route('password.update') }}">
                                                @csrf
                                                <input type="hidden" name="token" value="{{ $token }}">

                                                <div class="mb-3 auth-form-group-custom mb-4">
                                                    <i class="ri-mail-line auti-custom-input-icon"></i>
                                                    <label for="email">Email <span class="text-danger">*</span></label>
                                                    <input id="email" type="email"
                                                        class="form-control @error('email') is-invalid @enderror"
                                                        name="email" value="{{ $email ?? old('email') }}" required
                                                        autocomplete="email" autofocus placeholder="Enter email">
                                                    @error('email')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>

                                                <div class="mb-3 auth-form-group-custom mb-4">
                                                    <i class="ri-lock-2-line auti-custom-input-icon"></i>
                                                    <label for="password">Password Baru <span
                                                            class="text-danger">*</span></label>
                                                    <input id="password" type="password"
                                                        class="form-control @error('password') is-invalid @enderror"
                                                        name="password" required autocomplete="new-password"
                                                        placeholder="Enter new password">
                                                    @error('password')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>

                                                <div class="mb-3 auth-form-group-custom mb-4">
                                                    <i class="ri-lock-2-line auti-custom-input-icon"></i>
                                                    <label for="password-confirm">Konfirmasi Password <span
                                                            class="text-danger">*</span></label>
                                                    <input id="password-confirm" type="password" class="form-control"
                                                        name="password_confirmation" required autocomplete="new-password"
                                                        placeholder="Confirm new password">
                                                </div>

                                                <div class="mt-4 text-center">
                                                    <button class="btn btn-primary w-md waves-effect waves-light"
                                                        type="submit">
                                                        Reset Password
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="mt-5 text-center">
                                            <p>© {{ date('Y') }} Nazox. Crafted with <i
                                                    class="mdi m-di-heart text-danger"></i> by Themesdesign</p>
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
