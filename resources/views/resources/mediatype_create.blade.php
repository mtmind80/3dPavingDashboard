@extends('layouts.master')

@section('title')
    3D Paving Resources
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('title')
            {{$headername}} @lang('translation.create')
        @endslot
        @slot('li_1')
            <a href="/resources">@lang('translation.menu_resources')</a>
        @endslot
        @slot('li_2')
            <a href="{{route('getmodel',['model'=>$model])}}">{{$headername}}</a>
        @endslot
    @endcomponent

    <div>
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <!--                    <button class="button" id="button1">{{$model}}</button> -->
                </div>
                <div class="card-body">
                    <form class="custom-validation"
                          action="{{ route('save_resource',['model'=>$model,'id'=>0]) }}" novalidate=""
                          method="POST"
                          id="editform">
                        @csrf
                        <input type="hidden" name="model" value="{{$model}}" id="model">
                        <div class="row">
                            <div class="form-group" col-lg-4>
                                <label>@lang('translation.type')</label>
                                <input name="type" id="type" size='44' type="text"
                                       class="form-control" placeholder="Type">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group mb-0  col-lg-12">
                                <div>
                                    <button type="button" id='submitbutton'
                                            class="btn btn-primary waves-effect waves-light mr-1">@lang('translation.submit')
                                    </button>
                                    <button type="reset"
                                            class="btn btn-secondary waves-effect">@lang('translation.reset')</button>


                                    <button type="button" id="cancelbutton" 
                                            class="btn btn-danger waves-effect">@lang('translation.cancel')</button>
                                </div>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection

@section('script-bottom')
    <script type="text/javascript">

        $(document).ready(function () {



            $("#cancelbutton").click(function (event) {

                window.location.href="{{route('getmodel',['model'=>$model])}}";

            });
            
            $("#submitbutton").click(function (event) {
                form = $("#editform");
                checkit(form);


            });


            function checkit(form) {

                if ($("#type").val() == '') {
                    alert('You must supply a media type.');
                    return;
                }

                form.submit();
                return;

            }

        });

    </script>
@endsection

@section('script')

    <!-- jquery.vectormap map -->
    <script src="{{ URL::asset('/assets/libs/jquery-vectormap/jquery-vectormap.min.js')}}"></script>

    <!-- Responsive examples -->
    <script src="{{ URL::asset('/assets/libs/datatables/datatables.min.js')}}"></script>

    <script src="{{ URL::asset('/assets/js/pages/dashboard.init.js')}}"></script>
@endsection

