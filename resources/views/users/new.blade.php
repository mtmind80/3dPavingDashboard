@extends('layouts.master')

@section('title')
    3D Paving Employees
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('title')
            <a href="{{route("users")}}">Create Employee</a>
        @endslot
        @slot('li_1')
            <a href="/dashboard">Home</a>
        @endslot
        @slot('li_2')
            Create Employee
        @endslot
    @endcomponent

    <div>
        <div class="card">
            <div class="card-header">

            </div>
            <div class="card-body">
                <form class="custom-validation"
                      action="{{ route('create_user') }}" novalidate=""
                      method="POST"
                      id="newform">
                    <input type="hidden" name="status" value="1">
                    @csrf
                    
                    <div class="row">
                        <div class="form-group col-lg-6">
                            <label>@lang('translation.fname')</label>
                            <input name="fname" id="fname" size='34' 
                                   data-parsley-type="alphanum" 
                                   data-parsley-required="true" type="text"
                                   class="form-control" placeholder="First Name"
                                   value="">
                        </div>
                        <div class="form-group col-lg-6">
                            <label>@lang('translation.lname')</label>
                            <input name="lname" id="lname" size='34'  
                                   data-parsley-type="alphanum" 
                                   data-parsley-required="true"
                                   type="text"
                                   class="form-control" placeholder="Last name"
                                   value="">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="form-group col-lg-6">
                            <label>@lang('translation.email')</label>
                            <input name="email" id="email" type="email"
                                   data-parsley-type="email" size='54' type="text"
                                   data-parsley-required="true"
                                   class="form-control" placeholder="Email"
                                   value="">
                        </div>
                        <div class="form-group col-lg-6">
                            <label>@lang('translation.phone')</label>
                            <input name="phone" id="phone" data-parsley-type="number" 
                                   size='24' type="text"
                                   data-parsley-required="true"
                                   class="form-control" placeholder="Phone"
                                   value="">
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
                                    0
                                @endslot
                            @endcomponent
                        </div>
                        <div class="form-group col-lg-6">
                            <label>@lang('translation.language')</label>
                            <select name="language" id="language"
                                    class="form-control" >
                                <option value="en" selected>English</option>
                                <option value="es">Spanish</option>
                            </select>
                        </div>

                    </div>

                    <div class="row">
                        <div class="form-group col-lg-6">
                            <label>@lang('translation.password')</label>
                            <input name="password" id="password" size='34'
                                   type="text"
                                   class="form-control" placeholder="Password"
                                   value="">
                        </div>
                        <div class="form-group col-lg-6">
                            <label>@lang('translation.rate')</label>
                            <input name="rate_per_hour" id="rate_per_hour" size='34' type="text"
                                   data-parsley-type="number"
                                   class="form-control" placeholder="Rate Per Hour"
                                   value="0">
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

