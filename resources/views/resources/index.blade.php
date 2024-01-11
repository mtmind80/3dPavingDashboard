@extends('layouts.master')

@section('title')
    3D Paving @lang('translation.menu_resources')
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('title')
            @lang('translation.menu_resources')
        @endslot
        @slot('li_1')
            <a href="/dashboard">@lang('translation.Dashboard')</a>
        @endslot
        @slot('li_2')
            @lang('translation.menu_resources')
        @endslot
    @endcomponent

    <div id="resourcelist">
        <table class="table table-bordered table-centered table-responsive-lg">
            <tr>
                <td class="tc resourceLink">
                    Resource (click to edit)
                </td>
                <td class="tc resourceLink">
                    Description
                </td>
            </tr>

            <tr>
                <td class="tc">
                    <a class="resourceLink"
                       href="{{ route('getmodel', ['model' => 'Equipment'], false) }}">@lang('translation.equipment')</a><br/>
                </td>
                <td class="tc">
                    These are types of equipment and their rates.
                </td>
            </tr>
            <tr>
                <td class="tc">
                    <a class="resourceLink"
                       href="{{ route('getmodel', ['model' => 'Material'], false) }}">@lang('translation.materials')</a><br/>

                </td>
                <td class="tc">
                    These are types of materials purchased and their cost used in calculations on estimates.
                </td>
            </tr>
            <tr>
                <td class="tc">
                    <a class="resourceLink"
                       href="{{ route('getmodel', ['model' => 'Vehicle'], false) }}">@lang('translation.vehicle')</a><br/>
                </td>
                <td class="tc">
                    These are Vehicles we own and can be assigned to a job.
                </td>
            </tr>

            <tr>
                <td class="tc">
                    <a class="resourceLink"
                       href="{{ route('vendor_list') }}">@lang('translation.vendors')</a><br/>
                </td>
                <td class="tc">
                    Vendors we use to purchase materials.
                </td>
            </tr>

            <tr>
                <td class="tc">
                    <a class="resourceLink"
                       href="{{ route('contractor_list') }}">@lang('translation.sub') @lang('translation.contractors')</a><br/>
                </td>
                <td class="tc">
                    These are subs we contract with for special purposes and services.
                </td>
            </tr>

            <tr>
                <td class="tc">
                    <a class="resourceLink"
                       href="{{ route('getmodel', ['model' => 'LeadSource'], false) }}">@lang('translation.leadsource')</a><br/>
                </td>
                <td class="tc">
                    These are values that will be displayed in the lead intake form for lead source.
                </td>
            </tr>
            <tr>
                <td class="tc">
                    <a class="resourceLink"
                       href="{{ route('getmodel', ['model' => 'MediaType'], false) }}">@lang('translation.mediatype')</a><br/>

                </td>
                <td class="tc">
                    These are some standard types of documents that are attached to a proposal.
                </td>
            </tr>

            <tr>
                <td class="tc">
                    <a class="resourceLink"
                       href="{{ route('getmodel', ['model' => 'VehicleType'], false) }}">@lang('translation.vehicletype')</a><br/>

                </td>
                <td class="tc">
                    These are types of vehicles.
                </td>
            </tr>
            <tr>
                <td class="tc">
                    <a class="resourceLink"
                       href="{{ route('getmodel', ['model' => 'LaborRate'], false) }}">@lang('translation.labor_rates')</a><br/>
                </td>
                <td class="tc">
                    These are types of workers we can assign to a job and their hourly rates.
                </td>
            </tr>
            <tr>
                <td class="tc">
                    <a class="resourceLink"
                       href="{{ route('getmodel', ['model' => 'StripingCost'], false) }}">@lang('translation.striping_cost')</a><br/>
                </td>
                <td class="tc">
                    These are the cost of different Striping Services, used in estimations.
                </td>
            </tr>
            <tr>
                <td class="tc">
                    <a class="resourceLink"
                       href="{{ route('getmodel', ['model' => 'StripingService'], false) }}">@lang('translation.striping_service')</a><br/>
                </td>
                <td class="tc">
                    These are types of striping services.
                </td>
            </tr>
            <tr>
                <td class="tc">
                    <a class="resourceLink"
                       href="{{ route('getmodel', ['model' => 'OfficeLocations'], false) }}">@lang('translation.office')</a><br/>
                </td>
                <td class="tc">
                    These are our office locations used to set location of vehicles.
                </td>
            </tr>
            <tr>
                <td class="tc">
                    <a class="resourceLink"
                       href="{{ route('getmodel', ['model' => 'WebConfig'], false) }}">@lang('translation.webconfig')</a>
                    (*system
                    file)<br/>
                </td>
                <td class="tc">
                    These are settings and data used in this application. Like the company address and other data used throughout this application. (Edit with Care).
                </td>
            </tr>
            <tr>
                <td class="tc">
                    <a class="resourceLink" href="Javascript:AREYOUSURE('This action will restore all web configurations to their original settings!\nAre you sure?', "");">"Restore" Web
                        Configurations</a><br/>
                </td>
                <td class="tc">
                    This link will restore all web configurations to their original settings.
                </td>
            </tr>
            <tr>
                <td class="tc">
                    <a class="resourceLink"
                       href="{{ route('getmodel', ['model' => 'Term'], false) }}">@lang('translation.terms')</a><br/>
                </td>
                <td class="tc">
                    Edit the Global Terms and Conditions used in all proposals.
                </td>
            </tr>
            <tr>
                <td class="tc">
                    <a class="resourceLink"
                       href="{{ route('getmodel', ['model' => 'TermsOfService'], false) }}">@lang('translation.serviceterms')</a><br/>
                </td>
                <td class="tc">
                    Edit the Global Service Terms.
                </td>
            </tr>
<!--
            <tr>
                <td class="tc">
                    <a class="resourceLink"
                       href="{{ route('getmodel', ['model' => 'Service'], false) }}">@lang('translation.services')</a><br/>
                </td>
                <td class="tc">
                    Edit the current overhead percentage for each service.
                </td>
            </tr>
-->
        </table>

    </div>

@endsection

@push('partials-scripts')

    <script>

        function AREYOUSURE(memo, Durl){

            var doit = confirm(memo);
            if(doit){
                window.location.href=Durl;
            }
            return false;
        }


    </script>
@endpush
