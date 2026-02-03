@extends('layouts.master-without-nav')
@section('title')
    Register
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

                                            <h4 class="font-size-18 mt-4">Register account</h4>
                                            <p class="text-muted">Get your free Nazox account now.</p>
                                        </div>

                                        <div class="p-2 mt-5">
                                            <form method="POST" action="{{ route('register') }}">
                                                @csrf
                                                <div class="auth-form-group-custom mb-4">
                                                    <i class="ri-user-2-line auti-custom-input-icon"></i>
                                                    <label for="name">Name <span class="text-danger">*</span></label>
                                                    <input type="text"
                                                        class="form-control @error('name') is-invalid @enderror"
                                                        name="name" value="{{ old('name') }}" required
                                                        autocomplete="name" autofocus id="name"
                                                        placeholder="Enter name">
                                                    @error('name')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>

                                                <div class="auth-form-group-custom mb-4">
                                                    <i class="ri-mail-line auti-custom-input-icon"></i>
                                                    <label for="email">Email <span class="text-danger">*</span></label>
                                                    <input type="email"
                                                        class="form-control @error('email') is-invalid @enderror"
                                                        name="email" value="{{ old('email') }}" required
                                                        autocomplete="email" id="email" placeholder="Enter email">
                                                    @error('email')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>


                                                <div class="auth-form-group-custom mb-4">
                                                    <i class="ri-lock-2-line auti-custom-input-icon"></i>
                                                    <label for="userpassword">Password <span class="text-danger">*</span></label>
                                                    <input type="password"
                                                        class="form-control @error('password') is-invalid @enderror"
                                                        name="password" required autocomplete="new-password"
                                                        id="userpassword" placeholder="Enter password">
                                                    @error('password')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>

                                                <div class="auth-form-group-custom mb-4">
                                                    <i class="ri-lock-2-line auti-custom-input-icon"></i>
                                                    <label for="password-confirm">Confirm Password <span class="text-danger">*</span></label>
                                                    <input type="password"
                                                        class="form-control"
                                                        name="password_confirmation" required 
                                                        id="password-confirm" placeholder="Enter confirm password">
                                                </div>


                                                <div class="text-center">
                                                    <button class="btn btn-primary w-md waves-effect waves-light"
                                                        type="submit">Register</button>
                                                </div>

                                                <div class="mt-4 text-center">
                                                    <p class="mb-0">By registering you agree to the Nazox <a
                                                            href="#" class="text-primary">Terms of Use</a></p>
                                                </div>
                                            </form>
                                        </div>

                                        <div class="mt-5 text-center">
                                            <p>Already have an account ? <a href="{{ route('login') }}"
                                                    class="fw-medium text-primary"> Login</a> </p>
                                            <p>© {{ date('Y') }} Nazox. Crafted with <i
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
                    <div class="authentication-bg position-relative">
                        <div class="bg-overlay"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
