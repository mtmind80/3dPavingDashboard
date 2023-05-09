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
                    <!--                    <button class="button" id="button1">{{$model}}</button> -->
                </div>
                <div class="card-body">
                    <form class="custom-validation"
                          action="{{ route('save_resource',['model'=>$model,'id'=>$records['id']]) }}" novalidate=""
                          method="POST"
                          id="editform">
                        @csrf
                        <input type="hidden" name="active" id="active" value="">
                        <input type="hidden" name="id" value="{{$records['id']}}" id="id">
                        <input type="hidden" name="model" value="{{$model}}" id="model">
                        <div class="row">
                            <div class="form-group" col-lg-3>
                                <label>@lang('translation.Name')</label>
                                <input name="name" id="name" size='24' type="text"
                                       class="form-control" placeholder="Vehicle Name"
                                       value="{{$records['name']}}">
                            </div>
                            <div class="form-group" col-lg-4>
                                <label>@lang('translation.Description')</label>
                                <input name="description" id="description" size='34' type="text"
                                       class="form-control" placeholder="Vehicle Description"
                                       value="{{$records['description']}}">
                            </div>
                            
                            <div class="form-group col-lg-2">
                                <label>@lang('translation.type')</label>
                                    @component('components.widget')
                                        @slot('name')
                                            vehicle_types_id
                                        @endslot
                                        @slot('model')
                                    VehicleType
                                        @endslot
                                        @slot('value')
                                    name
                                        @endslot

                                            @slot('default')
                                                {{$records['vehicle_types_id']}}
                                            @endslot
                                    @endcomponent
                            </div>

                            <div class="form-group col-lg-2">
                                <label>@lang('translation.location')</label>
                                @component('components.widget')
                                    @slot('name')
                                        office_location_id
                                    @endslot
                                    @slot('model')
                                        OfficeLocations
                                    @endslot
                                    @slot('value')
                                        name
                                    @endslot

                                    @slot('default')
                                        {{$records['office_location_id']}}
                                    @endslot
                                @endcomponent
                            </div>
                            

                            <div class="form-group" col-lg-1>
                                <label>@lang('translation.active')</label>
                                <input name="isactive" id="isactive" type="checkbox"
                                       class="form-control" 
                                       value="1" @if($records['active']) checked @endif>
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
                    alert('You must supply a vehicle name.');
                    return;
                }
                if ($("#description").val() == '') {
                    alert('You must supply a description.');
                    return;

                }
                checked = document.getElementById("isactive").checked;
                if (checked) {
                    $("#active").val('1');
                } else {
                    $("#active").val('0');
                }
             //alert($("#active").val());
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

