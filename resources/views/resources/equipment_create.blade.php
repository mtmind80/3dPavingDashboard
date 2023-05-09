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
            <a href="/getmodel/{{$model}}">{{$headername}}</a>
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
                            <div class="form-group col-lg-3">
                                <label>@lang('translation.Name')</label>
                                <input name="name" id="name" size='44' type="text"
                                       class="form-control" placeholder="Enter Equipment Name">
                            </div>

                            <div class="form-group col-lg-3">
                                <label>@lang('translation.hourrate')</label>
                                <div>
                                    <input name="rate" id="rate" data-parsley-type="number"
                                           type="text"
                                           class="form-control" placeholder="Enter only numbers" value="0">
                                </div>
                            </div>


                            <div class="form-group col-lg-3">
                                <label>@lang('translation.donotuse')</label>
                                <div>
                                    <input name="do_not_use" id="do_not_use"  type="checkbox"
                                           class="form-control" value="1">
                                </div>
                            </div>

                            <div class="form-group col-lg-3">
                                <label>@lang('translation.Billed')</label>
                                <select name="rate_type" id="rate_type"
                                        class="form-control">
                                    <option value="per day" selected>Per Day</option>
                                    <option value="per hour">Per Hour</option>
                                </select>
                            </div>

                        </div>
                        <div class="row">
                            <div class="form-group mb-0  col-lg-12">
                                <div>
                                    <button type="button" id='submitbutton'
                                            class="btn btn-primary waves-effect waves-light mr-1">@lang('translation.submit')
                                    </button>
                                    <button type="button" id="cancel"
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
                    alert('You must supply a name.');
                    $("#name").focus();
                    return;
                }
                if ($("#rate").val() == '') {
                    alert('You must supply a rate per hour.');
                    $("#rate").focus();
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

