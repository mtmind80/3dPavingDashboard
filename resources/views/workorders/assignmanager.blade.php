@extends('layouts.master')

@section('title')
    3D Paving Work Orders
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('title')
            @lang('translation.assignfieldmanager')
        @endslot
        @slot('li_1')
            <a href="/workorders">@lang('translation.menu_workorders')</a>
        @endslot
        @slot('li_2')
            <a href="#">@lang('translation.fieldmanager')</a>
        @endslot
    @endcomponent

    <div class="row admin-form">

        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <h3>Work Order: {{$proposal->name}}}</h3>
                            <h4>Field Manager For Service: {{$service->service_name}}</h4>
                        </div>
                        <form method="post"
                              action="{{route('doassignmanager', ['id'=>$proposal->id, 'detail_id'=>$detail_id])}}"
                              id="admin_form">
                            @csrf
                            
                            <input type="hidden" name="id" value="{{$proposal->id}}">
                            <input type="hidden" name="detail_id" value="{{$detail_id}}">
                        <table width="100%" class="table-centered table-bordered font-size-20">
                            <td class="tc">
                                Assign Field Manager
                            </td>
                            <td class="tc">
                                <select class="form-control" id="fieldmanager_id" name="fieldmanager_id">
                                    <option value='0'>Select a Field Manager</option>
                                    @foreach($managers as $manager)
                                        <option value='{{$manager['id']}}'>{{$manager['fname']}} {{$manager['lname']}}</option>
                                    @endforeach
                                </select>
                            </td>
                        </table>

                    </div>
                    <div class="row">
                        <div class="col-4">
                            <input class="btn btn-info waves-effect w100" type="button" value="Submit"
                                   id="submitbutton">
                            <input class="btn btn-primary waves-effect w100" type="button" value="Cancel"
                                   id="cancelbutton">
                        </div>
                        <div class="col-8">

                        </div>

                    </div>
                    </form>
                </div>
            </div>
        </div>


        @stop

        @section('page-js')
            <script>

                $(document).ready(function () {

                    var body = $('body');
                    var form = $("#admin_form");
                    $('#submitbutton').click(function () {
                    
                        if(checkit(form)){
                            form.submit();
                        };

                    });
                    
                    function checkit(form){
                        return true;
                    }


                    $('#cancelbutton').click(function () {

                       window.location.href= "{{route('show_workorder',['id'=>$proposal->id])}}";

                    });


                });
            </script>
@stop

