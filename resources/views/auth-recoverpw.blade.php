@extends('layouts.master-without-nav')
@section('title')
Recover Password
@endsection
@section('body')
<body class="auth-body-bg">
@endsection
@section('content')
<div class="home-btn d-none d-sm-block">
    <a href="{{url('index')}}"><i class="mdi mdi-home-variant h2 text-white"></i></a>
</div>
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
                                            <a href="{{url('index')}}" class="logo"><img src="{{ URL::asset('/assets/images/logo-light-1.png')}}" height="20" alt="logo"></a>
                                        </div>

                                        <h4 class="font-size-18 mt-4">Recover Password</h4>
                                        <p class="text-muted">Recover your password to 3D Paving Dashboard.</p>
                                    </div>

                                    <div class="p-2 mt-5">
                                        <div class="alert alert-success mb-4" role="alert">
                                            Enter your Email if we find that email in our database then a password reset link will be sent to you!
                                        </div>
                                        <form class="form-horizontal" action="{{url('resetpassword')}}">
            
                                            <div class="form-group auth-form-group-custom mb-4">
                                                <i class="ri-mail-line auti-custom-input-icon"></i>
                                                <label for="useremail">Email</label>
                                                <input type="email" class="form-control" id="useremail" placeholder="Enter email">
                                            </div>

                                            <div class="mt-4 text-center">
                                                <button class="btn btn-primary w-md waves-effect waves-light" type="submit">Reset</button>
                                            </div>
                                        </form>
                                    </div>

                                    <div class="mt-5 text-center">
                                        <p>Have an account already ? <a href="{{url('login')}}" class="font-weight-medium text-primary"> Login</a> </p>
                                        <p><script>document.write(new Date().getFullYear())</script>© 3D Paving.  </br/>Crafted with <i class="mdi mdi-heart text-danger"></i> by ALLPOINTSWEST Software</p>
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
