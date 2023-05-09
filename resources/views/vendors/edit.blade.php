@extends('layouts.master')

@section('title')
    3D Paving Resources
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('title')
            @lang('translation.vendors') @lang('translation.edit')
        @endslot
        @slot('li_1')
            <a href="/resources">@lang('translation.menu_resources')</a>
        @endslot
        @slot('li_2')
            <a href="{{route('vendor_list')}}">  @lang('translation.vendors')</a>
        @endslot
    @endcomponent

    <div>
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">

                </div>
                <div class="card-body">
                    <form class="custom-validation"
                          action="{{ route('save_vendor',['id'=>$id]) }}" novalidate=""
                          method="POST"
                          id="editform">
                        <input type="hidden" name="id" value="{{$id}}">
                        @csrf

                        <div class="row">

                            <div class="form-group col-lg-6">
                                <label>@lang('translation.Name')</label>
                                <i class="field-required fa fa-asterisk"></i>
                                <input name="name" id="name" size='54' type="text"
                                       class="form-control" required placeholder="vendor Name"
                                       value="{{$vendor['name']}}">
                            </div>
                            <div class="form-group col-lg-6">
                                <label>@lang('translation.type')</label>
                                <i class="field-required fa fa-asterisk"></i>
                                @component('components.widget')
                                    @slot('name')
                                        vendor_type_id
                                    @endslot
                                    @slot('model')
                                        VendorType
                                    @endslot
                                    @slot('value')
                                        type
                                    @endslot

                                    @slot('default')
                                        {{$vendor['vendor_type_id']}}
                                    @endslot
                                @endcomponent
                            </div>

                        </div>

                        <div class="row">
                            <div class="form-group col-lg-6">
                                <label>@lang('translation.address') Line 1</label>
                                <i class="field-required fa fa-asterisk"></i>
                                <input name="address_line1" id="address_line1" size='50' type="text"
                                       class="form-control" required placeholder="Address line 1"
                                       value="{{$vendor['address_line1']}}">
                            </div>

                            <div class="form-group col-lg-6">
                                <label>@lang('translation.address') Line 2</label>
                                <input name="address_line2" id="address_line2" size='50' type="text"
                                       class="form-control" placeholder="Address line 2"
                                       value="{{$vendor['address_line2']}}">
                            </div>

                        </div>
                        <div class="row">
                            <div class="form-group col-lg-4">
                                <label>@lang('translation.city')</label>
                                <i class="field-required fa fa-asterisk"></i>
                                <input name="city" id="city" size='50' type="text"
                                       class="form-control" required placeholder="City"
                                       value="{{$vendor['city']}}">
                            </div>

                            <div class="form-group col-lg-4">
                                <label>@lang('translation.state')</label>
                                @component('components.widget')
                                    @slot('name')
                                        state
                                    @endslot
                                    @slot('model')
                                        State
                                    @endslot
                                    @slot('value')
                                        state
                                    @endslot

                                    @slot('default')
                                        {{$vendor['state']}}
                                    @endslot
                                @endcomponent
                            </div>

                            <div class="form-group col-lg-4">
                                <label>@lang('translation.zipcode')</label>
                                <i class="field-required fa fa-asterisk"></i>
                                <input name="postal_code" id="postal_code" size='25' type="text"
                                       class="form-control" required placeholder="Zip Code"
                                       value="{{$vendor['postal_code']}}">
                            </div>

                        </div>


                        <div class="row">
                            <div class="form-group col-lg-6">
                                <label>@lang('translation.phone')</label>
                                <i class="field-required fa fa-asterisk"></i>
                                <input name="phone" id="phone" size='54' type="text"
                                       class="form-control" required placeholder="Phone"
                                       value="{{$vendor['phone']}}">
                            </div>

                            <div class="form-group col-lg-6">
                                <label>@lang('translation.contact')</label>
                                <input name="contact" id="contact" size='54' type="text"
                                       class="form-control" required placeholder="Contact Person"
                                       value="{{$vendor['contact']}}">
                            </div>

                        </div>
                        <div class="row">
                            <div class="form-group col-lg-6">
                                <label>@lang('translation.email')</label>
                                <i class="field-required fa fa-asterisk"></i>
                                <input name="email" id="email" size='54' type="text"
                                       class="form-control" required placeholder="Email"
                                       value="{{$vendor['email']}}">
                            </div>

                            <div class="form-group col-lg-6">
                                <label>@lang('translation.note')</label>
                                <textarea name="note" id="note" cols='64' rows='3'
                                          class="form-control" placeholder="vendor Note"
                                >{{$vendor['note']}}</textarea>
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

                window.location.href = "{{route('vendor_list')}}";

            });

            $("#submitbutton").click(function (event) {
                form = $("#editform");
                checkit(form);


            });


            function checkit(form) {

                if ($("#description").val() == '') {
                    alert('You must supply a description.');
                    return;
                }
                if ($("#rate").val() == '') {
                    alert('You must supply a rate.');
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

