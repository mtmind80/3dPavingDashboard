@extends('layouts.master-without-nav')
@section('title') 404 Error @endsection
@section('content')
    <div class="my-5 pt-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="text-center my-5">
                        <h1 class="font-weight-bold text-error">4 <span class="error-text">0<img src="{{ URL::asset('/assets/images/error-img.png')}}" alt="" class="error-img"></span> 4</h1>
                        <h3 class="text-uppercase">Seems that you may be lost, or you do not have access to this page.
                        </br>If you think this is an error report this to your supervisor.</h3>
                        <div class="mt-5 text-center">
                            <a class="btn btn-primary waves-effect waves-light" href="javascript:window.history.back();">Go Back</a>
                        </div>
                        <div class="mt-5 text-center">
                            <a class="btn btn-primary waves-effect waves-light" href="{{url('dashboard')}}">Back to Dashboard</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
