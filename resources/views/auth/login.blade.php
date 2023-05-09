@extends('layouts.master-without-nav')
@section('title')
Login 
@endsection
@section('body')
<body class="auth-body-bg">
@endsection
@section('content')

<div>
    <div class="container-fluid p-0">
        <div class="row no-gutters">
            <div class="col-lg-4">
                <div class="authentication-page-content p-4 d-flex align-items-center min-vh-100">
                    <div class="w-100">
                        <div class="row justify-content-center">
                            <div class="col-lg-9">
                                <div>
                                    <div class="text-center">
                                        <div>
                                            <a href="{{url('index')}}" class="logo"><img src="{{ URL::asset('/assets/images/logo-light-1.png')}}" height="60" alt="logo"></a>
                                        </div>

                                        <h4 class="font-size-18 mt-4">Welcome Back!</h4>
                                        <p class="text-muted">Sign in to continue.</p>
                                    </div>

                                    <div class="p-2 mt-5">
                                        <form method="POST" action="{{ route('login') }}">
                                            @csrf

                                            <div class="form-group auth-form-group-custom mb-4">
                                                <i class="ri-user-2-line auti-custom-input-icon"></i>
                                                <label for="email">{{ __('E-Mail Address') }}</label>
                                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="Enter Email">
                                                @error('email')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>

                                            <div class="form-group auth-form-group-custom mb-4">
                                                <i class="ri-lock-2-line auti-custom-input-icon"></i>
                                                <label for="password">{{ __('Password') }}</label>
                                                <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" id="password" placeholder="Enter password">
                                                @error('password')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                            <!--
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="form-control" id="rememberme" name="rememberme" value="1">
                                                <label class="custom-control-label" for="customControlInline">Remember me</label>
                                            </div>
                                            -->
                                            <div class="mt-4 text-center">
                                                <button class="btn btn-primary w-md waves-effect waves-light" type="submit">{{ __('Log In') }}</button>
                                            </div>

                                            <div class="mt-4 text-center">
                                                @if (Route::has('password.request'))
                                                    <a href="{{ route('password.request') }}" class="text-muted"><i class="mdi mdi-lock mr-1"></i> {{ __('Forgot your password?') }}</a>
                                                @endif
                                                    <p></p>
                                                    <p></p>

                                                    <p><script>document.write(new Date().getFullYear())</script>© 3D Paving.  
                                                    <br/>
                                                        <!--
                                                        Crafted with <i class="mdi mdi-heart text-danger"></i> by ALLPOINTSWEST Software
                                                    -->
                                                    </p>

                                            </div>
                                        </form>

                                    </div>

                                 </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="authentication-bg">
 <!--                <div class="bg-overlay"></div> -->
            </div>
        </div>
    </div>
</div>
</div>
@endsection
