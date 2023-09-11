@extends('layouts.master')

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

                </div>
                <div class="card-body">
                    <form class="custom-validation"
                          action="{{ route('save_resource',['model'=>$model,'id'=>$records['id']]) }}" novalidate=""
                          method="POST"
                          id="editform">
                        @csrf
                        <input type="hidden" name="name" value="{{$records['name']}}" id="name">
                        <input type="hidden" name="id" value="{{$records['id']}}" id="id">
                        <input type="hidden" name="model" value="{{$model}}" id="model">
                        <div class="row">
                            <div class="form-group" col-lg-4>
                                <label>@lang('translation.Name')</label>
                                <input name="nameed" id="nameed" size='44' type="text"
                                       class="form-control" placeholder="Service Name"
                                       value="{{$records['name']}}" disabled>
                            </div>

                            <div class="form-group col-lg-4">
                                <label>@lang('translation.overhead') @lang('translation.percentage')</label>
                                <div>
                                    <input name="percent_overhead" id="percent_overhead" data-parsley-type="number"
                                           type="text"
                                           class="form-control" placeholder="Enter only numbers"
                                           value="{{$records['percent_overhead']}}">
                                </div>
                            </div>


                            <div class="form-group col-lg-4">
&nbsp;
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

                if ($("#name").val() == '') {
                    alert('You must supply a material name.');
                    return;
                }
                if ($("#cost").val() == '') {
                    alert('You must supply a material cost.');
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

