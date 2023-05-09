@extends('layouts.no-nav')
@section('title') Lock screen @endsection
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
                                            <a href="{{url('login')}}" class="logo"><img src="{{ URL::asset('/assets/images/logo-light-1.png')}}" height="60" alt="logo"></a>

                                        </div>

                                        <h4 class="font-size-18 mt-4">Lock screen</h4>
                                        <p class="text-muted">@lang('translation.enterpasswordunlock')</p>
                                    </div>

                                    <div class="p-2 mt-5">
                                        <form class="form-horizontal" action="{{route ('endlockout')}}" method="post">
                                            @csrf
                                            <div class="user-thumb text-center mb-5">
                                                <img src="{{ URL::asset('/assets/images/users/avatar-2.jpg')}}" class="rounded-circle img-thumbnail avatar-md" alt="thumbnail">
                                                <h5 class="font-size-15 mt-3">{{auth()->user()->FullName }}</h5>
                                            </div>
                    
                                            <div class="form-group auth-form-group-custom mb-4">
                                                <i class="ri-lock-2-line auti-custom-input-icon"></i>
                                                <label for="userpassword">@lang('translation.enterpassword')</label>
                                                <input type="password" class="form-control" id="userpassword" required autocomplete="off" readonly
                                                       onfocus="this.removeAttribute('readonly');" name="userpassword" placeholder="Enter password">
                                            </div>

                                            <div class="mt-4 text-center">
                                                <button class="btn btn-primary w-md waves-effect waves-light" type="submit">Unlock</button>
                                            </div>
                                        </form>
                                    </div>

                                    <div class="mt-5 text-center">
                                        <p>Not you ? <a href="login" class="font-weight-medium text-primary"> Log in </a> </p>
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
