@extends('layouts.master')
@section('headscripts')
    <script src="https://cdn.tiny.cloud/1/jmt0st0lpkf5qqjya17xfrl95nx25h6826aqh6ecql6b9g7a/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
@endsection

@section('title')
    3D Paving Resources
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('title')
            {{$headername}} EDIT
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
                          action="{{ route('save_resource',['model'=>$model,'id'=>$records['id']]) }}" novalidate=""
                          method="POST"
                          id="editform">
                        @csrf

                        <input type="hidden" name="id" value="{{$records['id']}}" id="id">
                        <input type="hidden" name="model" value="{{$model}}" id="model">
                        <input type="hidden" name="section" value="{{$records['section']}}" id="section">
                        <input type="hidden" name="title" value="{{$records['title']}}" id="title">
                        <div class="row">
                            <div class="col-lg-12">
                                <h2>{{$records['title']}}</h2>
                            </div>

                        </div>

                        <div class="row">
                            <div class="form-group" col-lg-12>
                                <textarea name="text" id="text" cols="120" rows="23"
                                       class="form-control" placeholder="Terms"
                                >{{$records['text']}}</textarea>
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


            tinymce.init({
                selector: '#text'
            });


            $("#cancelbutton").click(function (event) {

                window.location.href="{{route('getmodel',['model'=>$model])}}";

            });

            $("#submitbutton").click(function (event) {
                form = $("#editform");
                checkit(form);


            });


            function checkit(form) {

                if ($("#text").val() == '') {
                    alert('You must supply some text.');
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

@endsection

