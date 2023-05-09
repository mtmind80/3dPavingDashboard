@extends('layouts.master')

@section('title')
    3D Paving Employees
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('title')
            <a href="{{route("users")}}">Edit Employee</a>
        @endslot
        @slot('li_1')
            <a href="/dashboard">Home</a>
        @endslot
        @slot('li_2')
            Edit Employee
        @endslot
    @endcomponent

    <div>
        <div class="card">
            <div class="card-header">

            </div>
            <div class="card-body">
                <form class="custom-validation"
                      action="{{ route('update_user',['id'=>$id]) }}" novalidate=""
                      method="POST"
                      id="newform">
                    @csrf
                    <input type="hidden" name="id" id="id" value="$id">
                    <div class="row">
                        <div class="form-group col-lg-6">
                            <label><span style="color:red;">*</span> @lang('translation.fname')</label>
                            <input name="fname" id="fname" size='34' 
                                   data-parsley-type="alphanum" 
                                   data-parsley-required="true" type="text"
                                   class="form-control" placeholder="First Name"
                                   value="{{ $record['fname'] }}">
                        </div>
                        <div class="form-group col-lg-6">
                            <label><span style="color:red;">*</span> @lang('translation.lname')</label>
                            <input name="lname" id="lname" size='34'  
                                   data-parsley-type="alphanum" 
                                   data-parsley-required="true"
                                   type="text"
                                   class="form-control" placeholder="Last name"
                                   value="{{ $record['lname'] }}">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="form-group col-lg-6">
                            <label><span style="color:red;">*</span> @lang('translation.email')</label>
                            <input name="email" id="email" type="email"
                                   data-parsley-type="email" size='54' type="text"
                                   data-parsley-required="true"
                                   class="form-control" placeholder="Email"
                                   value="{{ $record['email'] }}">
                        </div>
                        <div class="form-group col-lg-6">
                            <label><span style="color:red;">*</span> @lang('translation.phone')</label>
                            <input name="phone" id="phone"  
                                   size='24' type="text"
                                   data-parsley-required="true"
                                   class="form-control" placeholder="Phone"
                                   value="{{ $record['phone'] }}">
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-lg-6">
                            <label>@lang('translation.role')</label></br>
                            @component('components.widget')
                                @slot('name')
                                    role_id
                                @endslot
                                @slot('model')
                                    Role
                                @endslot
                                @slot('value')
                                    role
                                @endslot

                                @slot('default')
                                    {{$record['role_id']}}
                                @endslot
                            @endcomponent
                        </div>
                        <div class="form-group col-lg-6">
                            <label>@lang('translation.language')</label>
                            <select name="language" id="language"
                                    class="form-control" >
                                <option value="{{ $record['language'] }}" selected>{{ $record['language'] }}</option>
                                <option value="en">English</option>
                                <option value="es">Spanish</option>
                            </select>
                        </div>

                    </div>

                    <div class="row">
                        <div class="form-group col-lg-3">
                            <label>@lang('translation.active')</label>
                            <input name="status" id="2status"
                                   type="checkbox" 
                                   class="form-control"
                                   value="1"
                            @if($record['status'])
                                checked
                            @endif
                            >
                        </div>
                        <div class="form-group col-lg-4">
                            <label>@lang('translation.rate') (Field workers only *Labor role)</label>
                            <input name="rate_per_hour" id="rate_per_hour" type="text"
                                   data-parsley-type="number"
                                   class="form-control input-sm" placeholder="Rate Per Hour"
                                   value="{{ $record['rate_per_hour'] }}">
                        </div>
                        <div class="form-group col-lg-5">
                            &nbsp;        <label>@lang('translation.salesgoals')</label>
                                <input name="sales_goals" id="sales_goals" type="text"
                                       data-parsley-type="number"
                                       class="form-control input-sm" placeholder="Sales Goals"
                                       value="{{ $record['sales_goals'] }}">
                            
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

@endsection

@section('script-bottom')
    <script type="text/javascript">

        $(document).ready(function () {


            $("#cancelbutton").click(function (event) {
                window.location.href='{{route('users')}}';
                return true;

            });
            
            $("#submitbutton").click(function (event) {
                form = $("#newform");
                checkit(form);

            });

            function checkit(form) {

                if ($("#fname").val() == '') {
                    alert("You must fill in a first name");
                    return;
                }
                if ($("#lname").val() == '') {
                    alert("You must fill in a last name");
                    return;

                }
                if ($("#email").val() =='') {
                    alert("You must fill in an email");
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

