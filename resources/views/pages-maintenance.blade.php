@extends('layouts.master-without-nav')

@section('title') Maintenance @endsection
@section('body')
<body data-sidebar="dark">
@endsection
@section('content')

<div class="home-btn d-none d-sm-block">
    <a href="{{url('')}}" class="text-dark"><i class="mdi mdi-home-variant h2"></i></a>
</div>

<div class="my-5 pt-sm-5">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="text-center">
                    <div class="mb-5">
                        <a href="{{url('index')}}">
                            <img src="{{ URL::asset('/assets/images/logo-light-1.png')}}" alt="logo" height="20" />
                        </a>
                    </div>

                    <div class="row justify-content-center">
                        <div class="col-sm-4">
                            <div class="maintenance-img">
                                <img src="{{ URL::asset('/assets/images/maintenance-bg.png')}}" alt="" class="img-fluid mx-auto d-block">
                            </div>
                        </div>
                    </div>
                    <h3 class="mt-5">Site is Under Maintenance</h3>
                    <p>Please check back in sometime.</p>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="mt-4 maintenance-box">
                                <div class="p-3">
                                    <div class="avatar-sm mx-auto">
                                        <span class="avatar-title bg-soft-primary rounded-circle">
                                            <i class="mdi mdi-access-point-network font-size-24 text-primary"></i>
                                        </span>
                                    </div>

                                    <h5 class="font-size-15 text-uppercase mt-4">Why is the Site Down?</h5>
                                    <p class="text-muted mb-0">For scheduled updates.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mt-4 maintenance-box">
                                <div class="p-3">
                                    <div class="avatar-sm mx-auto">
                                        <span class="avatar-title bg-soft-primary rounded-circle">
                                            <i class="mdi mdi-clock-outline font-size-24 text-primary"></i>
                                        </span>
                                    </div>
                                    <h5 class="font-size-15 text-uppercase mt-4">
                                        What is the Downtime?</h5>
                                    <p class="text-muted mb-0">Approx 20 minutes.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mt-4 maintenance-box">
                                <div class="p-3">
                                    <div class="avatar-sm mx-auto">
                                        <span class="avatar-title bg-soft-primary rounded-circle">
                                            <i class="mdi mdi-email-outline font-size-24 text-primary"></i>
                                        </span>
                                    </div>
                                    <h5 class="font-size-15 text-uppercase mt-4">
                                        Do you need Support?</h5>
                                    <p class="text-muted mb-0">If you are in need of support for this issue please contact your supervisor.
                                        Or wait for the updates to finish.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end row -->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
