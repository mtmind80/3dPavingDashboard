@extends('layouts.master')

@section('title') 3D Paving Schedule Service @endsection


@section('content')
    @component('components.breadcrumb')
        @slot('title')
            @lang('translation.permit') {{$proposal->name}}
        @endslot
        @slot('li_1')
            <a href="{{ route('dashboard') }}" xmlns="http://www.w3.org/1999/html">@lang('translation.Dashboard')</a>
        @endslot
        @slot('li_3')
            <a href="{{ route('show_workorder', ['id'=>$proposal->id]) }}">@lang('translation.work_order')</a>
        @endslot

    @endcomponent

    <div class="row admin-form">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                                <form method="post" action="{{route('create_schedule',['proposal_detail'=>$service->id])}}"
                                      id="serviceform">
                                    @csrf

                                    <input type="hidden" name="created_by" value="{{auth()->user()->id}}">
                                    <input type="hidden" name="proposal_detail_id"
                                           value="{{$service->proposal_detail_id}}">
                                    <input type="hidden" name="proposal_id" value="{{$proposal->id}}">

                                    <div class="row">
                                        <div class="col-lg-4">
                                            <x-form-date-picker
                                                name="start_date"
                                                :params="[
                                    'id' => 'start_date',
                                    'label' => 'Start Date',
                                    'iconClass' => 'fas fa-calendar',
                                ]"
                                            ></x-form-date-picker>

                                        </div>

                                        <div class="col-lg-4">
                                            <x-form-date-picker
                                                name="end_date"
                                                :params="[
                                    'id' => 'end_date',
                                    'label' => 'End Date',
                                    'iconClass' => 'fas fa-calendar',
                                ]"
                                            ></x-form-date-picker>

                                        </div>
                                        <div class="col-lg-4">
                                        </div>
                                    </div>


                                    <div class="row">
                                        <div class="col-lg-12">
                                            <p></p>
                                            <input type="submit" value="Add Schedule" class="{{$site_button_class}}" />
                                            <input type="button" id="returntoworkorder" value="View Work Order" class="{{$site_button_class}}" />
                                        </div>
                                    </div>
                                </form>

                            </div>
                </div>
            </div>

@stop


@section('page-js')
    <script>
        $(document).ready(function(){
        $("#returntoworkorder").on('click', function(){
            window.location.href="{{route('show_workorder',['id'=>$proposal->id])}}";
        });

        $("#addschedule").on('click', function(){

                    window.location.href="{{route('create_schedule',['proposal_detail'=>$service->id])}}";

        });

        });

    </script>
@stop

