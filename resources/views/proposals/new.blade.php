@extends('layouts.master')

@section('title')
    3D Paving Proposals
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('title')
            @lang('translation.newproposal')
        @endslot
        @slot('li_1')
            <a href="/dashboard">@lang('translation.Dashboard')</a>
        @endslot
        @slot('li_2')
            <a href="/proposals">@lang('translation.Proposals')</a>
        @endslot
    @endcomponent

    <div class="row admin-form">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row"  style="margin:10px;padding:10px;">
                        &nbsp;
                        <h5>@lang('translation.client')</h5>
                        &nbsp;
                    </div>
                    <form class="custom-validation"  
                          action="{{route('create_proposal',['id'=>$id])}}" novalidate=""
                          method="POST"
                          id="proposalform">
                        
                        <input type="hidden" name="id" value="{{$id}}">
                       
                        <div class="row"  style="margin:10px;padding:10px;">
                            <div class="form-group col-lg-6">
                                <label><span style="color:Red;">*</span> @lang('translation.fname')</label>
                                <input name="first_name" id="first_name"  type="text"
                                       data-parsley-required="true"
                                       class="form-control" placeholder="First name required" value="{{$contact['first_name']}}">

                            </div>
                            <div class="form-group col-lg-6">
                                <label>@lang('translation.lname')</label>
                                <input name="last_name" id="last_name"  type="text"
                                       data-parsley-required="false"
                                       class="form-control" placeholder="Last name optional" value="{{$contact['last_name']}}">
                            </div>

                        </div>

                        <div  class="row border-bottom border-dark" style="margin:10px;padding:10px;">
                            <div class="form-group col-lg-6">
                                <label><span style="color:Red;">*</span> @lang('translation.phone')</label>
                                <input name="phone" id="phone"  type="text"
                                       data-parsley-required="true"
                                       class="form-control" placeholder="Phone required" value="{{$contact['phone']}}">

                            </div>
                            <div class="form-group col-lg-6">
                                <label><span style="color:Red;">*</span> @lang('translation.email')</label>
                                <input name="email" id="email"  type="text"
                                       data-parsley-required="true"
                                       data-parsley-type="email"
                                       class="form-control" placeholder="Email required" value="{{$contact['email']}}">

                            </div>
                            
                        </div>
                        <div  class="row" style="margin:10px;padding:10px;">
                            <div class="form-group col-lg-12">
                            <h5>@lang('translation.proposallocation')</h5>
                            </div>&nbsp;
                        </div>
                        <div  class="row" style="margin:10px;padding:10px;">
                            <div class="form-group col-lg-12">
                                <label><span style="color:Red;">*</span> @lang('translation.proposalname')</label>
                                <input name="name" id="name"  type="text"
                                       data-parsley-required="true"
                                       class="form-control" placeholder="Proposal Name is required" value="">

                            </div>

                            
                            
                        </div>
                        <div  class="row" style="margin:10px;padding:10px;">
                            <div class="form-group col-lg-6">

                                <label><span style="color:Red;">*</span> @lang('translation.locationtype')</label>

                                @component('components.widget')
                                    @slot('name')
                                        location_type_id
                                    @endslot
                                    @slot('model')
                                        LocationType
                                    @endslot
                                    @slot('value')
                                        type
                                    @endslot

                                    @slot('default')
                                        0
                                    @endslot
                                @endcomponent



                            </div>

                            <div class="form-group col-lg-6">
                                <label><span style="color:Red;">*</span> @lang('translation.manager')</label>
                                <select name="manager_id" class="form-control">
                                    <option value="{{$contact['id']}}" selected>{{$contact['first_name']}} {{$contact['last_name']}}</option>
                                    @if($staff)
                                        @foreach($staff as $s)
                                            <option value="{{$s['id']}}">{{$s['first_name']}} {{$s['last_name']}}</option>
                                        @endforeach
                                    @endif
                                </select>

                            </div>
                        </div>

                        <div  class="row" style="margin:10px;padding:10px;">
                            <div class="form-group col-lg-6">
                                <label><span style="color:Red;">*</span> @lang('translation.address')</label>
                                <input name="address1" id="address1"  type="text"
                                       data-parsley-required="true"
                                       class="form-control" placeholder="Address line 1" value="{{ $contact['address1']}}">

                            </div>
                            <div class="col-lg-6">
                                <label>@lang('translation.address')</label>
                                <input name="address2" id="address2"  type="text"
                                       class="form-control" placeholder="Address line 1" value="{{ $contact['address2']}}">
                            </div>

                        </div>

                        <div  class="row" style="margin:10px;padding:10px;">
                            <div class="col-lg-4">
                                <label><span style="color:Red;">*</span> @lang('translation.city')</label>
                                <input name="city" id="city"  type="text"
                                       data-parsley-required="true"
                                       class="form-control" placeholder="City" value="{{ $contact['city']}}">

                            </div>
                            <div class="col-lg-4">
                                <label><span style="color:Red;">*</span> @lang('translation.state')</label>
                                <select name="state" id="state"  
                                        class="form-control">
                                    <option value="{{ $contact['state']}}">{{ $contact['state']}}</option>
                                    @foreach($states as $state)
                                        <option value="{{ $state['state'] }}">{{ $state['state'] }}</option>
                                @endforeach
                                </select>
                            </div>

                            <div class="col-lg-4">
                                <label><span style="color:Red;">*</span> @lang('translation.county')</label>

                                @component('components.advanced_widget')
                                    @slot('distinct')
                                        1
                                    @endslot
                                        @slot('field')
                                            county
                                        @endslot

                                    @slot('name')
                                        county
                                    @endslot
                                    @slot('model')
                                        County
                                    @endslot
                                    @slot('value')
                                        county
                                    @endslot

                                    @slot('default')
                                        {{$contact['county']}}
                                    @endslot
                                @endcomponent

                            </div>

                        </div>

                        <div  class="row border-bottom border-dark" style="margin:10px;padding:10px;">

                            <div class="col-lg-6">
                                <label><span style="color:Red;">*</span> @lang('translation.zipcode')</label>

                                <input name="postal_code" id="postal_code"  type="text"
                                       data-parsley-required="true"
                                       class="form-control" placeholder="Postal Code" value="{{ $contact['postal_code']}}">
                            </div>

                            <div class="col-lg-6">
                                <label>@lang('translation.parcel')</label>

                                <input name="parcel_number" id="parcel_number"  type="text"
                                       data-parsley-required="false"
                                       class="form-control" placeholder="Parcel Number if known" value="">
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
@stop

@section('page-js')
    <script>
        $(document).ready(function () {
            //

            var doit =1;

            window.Parsley.on('field:error', function() {
                // This global callback will be called for any field that fails validation.
                doitonce(this.$element);
                //console.log('Validation failed for: ', this.$element);
            });

            function doitonce(ele)
            {
                if(doit)
                {
                    alert("There were errors in submission, please check the form again.");
                    $(ele).focus();
                    doit = 0;
                    
                }
            }
            
            $("#submitbutton").click(function (event) {
                form = $("#proposalform");
                if(checkit(form)){
                    form.submit();
                };

            });

            function checkit(form) {

                if ($("#first_name").val() == '') {
                    alert("You must fill in a first name");
                    form.first_name.focus();
                    return false;
                }

                if ($("#phone").val() =='') {
                    alert("You must fill in a phone number.");
                    form.phone.focus();
                    return false;

                }
                if ($("#name").val() =='') {
                    alert("You must fill in a proposal name.");
                    return false;

                }
                if ($("#city").val() =='') {
                    alert("You must fill in a city.");
                    return false;

                }
                
                if ($("#email").val() =='') {
                    alert("You must fill in an email");
                    return false;

                }

                if ($("#address_line1").val() =='') {
                    alert("You must fill in a primary address");
                    return false;

                }
                
                
                //form.submit();
                return true;


            }


            
            
        });
    </script>


@stop
