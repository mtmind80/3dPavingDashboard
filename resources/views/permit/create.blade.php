@extends('layouts.master')

@section('title') 3D Paving Permit Details @endsection


@section('content')
    @component('components.breadcrumb')
        @slot('title')
            @lang('translation.permit') {{$proposal->name}}
        @endslot
        @slot('li_1')
            <a href="{{ route('dashboard') }}" xmlns="http://www.w3.org/1999/html">@lang('translation.Dashboard')</a>
        @endslot
        @slot('li_2')
            <a href="{{ route('permits') }}">@lang('translation.all') @lang('translation.permits')</a>
        @endslot
        @slot('li_3')
            <a href="{{ route('show_workorder', ['id'=>$proposal->id]) }}">@lang('translation.work_order')</a>
        @endslot

    @endcomponent

    <div class="row admin-form">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <!-- Tab panes -->
                            <div class="row">
                                <form method="post" action="{{route('create_permit')}}"
                                      id="permitform">
                                    @csrf
                                    <input type="hidden" name="proposal_id" value="{{$proposal->id}}">
                                    <input type="hidden" name="created_by" value="{{auth()->user()->id}}">
                                    <input type="hidden" name="status_id" value="1">

                                    <div class="row">
                                        <div class="col-lg-4">
                                            <label>Status:</label>
                                            <select class="form-control" name="status">
                                                <option>Not Submitted</option>
                                                <option>Submitted</option>
                                                <option>Under Review</option>
                                                <option>Approved</option>
                                                <option>Comments</option>
                                            </select>
                                        </div>
                                        <div class="col-lg-4">
                                            <label>Type:</label>
                                            <select class="form-control" name="type">
                                                <option>Regular</option>
                                                <option>Special</option>
                                                <option>Other</option>
                                            </select>
                                        </div>
                                        <div class="col-lg-4">
                                            <x-form-text name="number"
                                                         :params="['label' => 'Permit Number', 'iconClass' => 'fas fa-folder']"></x-form-text>
                                        </div>
                                    </div>


                                    <div class="row">
                                        <div class="col-lg-4">
                                            <label>County:</label>
                                            <select name="county" id="county" class="form-control">
                                                @foreach($counties as $county)
                                                    <option>{{$county->county}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-lg-4">
                                            <x-form-text name="city" :
                                                         params="['label' => 'City', 'iconClass' => 'fas fa-file','required' => true]"></x-form-text>
                                        </div>
                                        <div class="col-lg-4">
                                            <x-form-date-picker
                                                name="expires_on"
                                                :params="[
                                    'id' => 'expires_on',
                                    'label' => 'Expires On',
                                    'iconClass' => 'fas fa-calendar',
                                ]"
                                            ></x-form-date-picker>

                                        </div>

                                    </div>


                                    <div class="row">
                                        <div class="col-lg-12">
                                            <p></p>
                                            <input type="submit" value="Save Permit" class="{{$site_button_class}}" />
                                        </div>
                                    </div>
                                </form>

                            </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('page-js')
    <script>
    </script>
@stop

