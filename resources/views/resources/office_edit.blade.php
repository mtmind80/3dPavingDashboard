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
                          action="{{ route('save_resource',['model'=>$model,'id'=>$id]) }}" novalidate=""
                          method="POST"
                          id="editform">
                        @csrf
                        <input type="hidden" name="model" value="{{$model}}" id="model">
                        <div class="row">
                            <div class="form-group" col-lg-4>
                                <label>@lang('translation.Name')</label>
                                <input name="name" id="name" size='34' type="text"
                                       class="form-control" placeholder="Office Name" value="{{$records['name']}}">
                            </div>
                            <div class="form-group" col-lg-4>
                                <label>@lang('translation.address')</label>
                                <input name="address" id="address" size='44' type="text"
                                       class="form-control" placeholder="Address" value="{{$records['address']}}">
                            </div>
                            <div class="form-group" col-lg-4>
                                <label>@lang('translation.city')</label>
                                <input name="city" id="city" size='44' type="text"
                                       class="form-control" placeholder="City" value="{{$records['city']}}">
                            </div>

                        </div>
                        <div class="row">
                            <div class="form-group" col-lg-3>
                                <label>@lang('translation.state')</label>
                                <input name="state" id="state" size='24' type="text"
                                       class="form-control" placeholder="State" VALUE="FL" value="{{$records['state']}}">
                            </div>
                            <div class="form-group" col-lg-3>
                                <label>@lang('translation.zipcode')</label>
                                <input name="zip" id="zip" size='12' type="text"
                                       class="form-control" placeholder="Postal Code" value="{{$records['zip']}}">
                            </div>
                            <div class="form-group col-lg-4">
                                <div class="form-group" col-lg-3>
                                    <label>@lang('translation.phone')</label>
                                    <input name="phone" id="phone" size='24' type="text"
                                           class="form-control" placeholder="Phone" value="{{$records['phone']}}">
                                </div>
                            </div>
                            <div class="form-group" col-lg-3>
                                <label>@lang('translation.manager')</label>
                                <input name="manager" id="manager" size='34' type="text"
                                       class="form-control" placeholder="Manager" value="{{$records['manager']}}">
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

                window.location.href = "{{route('getmodel',['model'=>$model])}}";

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
                if ($("#address").val() == '') {
                    alert('You must supply an address.');
                    return;

                }
                if ($("#state").val() == '') {
                    alert('You must supply a state.');
                    return;

                }
                if ($("#phone").val() == '') {
                    alert('You must supply a phone.');
                    return;

                }
                if ($("#zip").val() == '') {
                    alert('You must supply a postal code.');
                    return;

                }

                if ($("#alt_cost").val() == '') {
                    alert('You must supply an alternate cost. Used for special.');
                    // $("#rate_per_day").val() = 0;
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

