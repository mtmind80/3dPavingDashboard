@extends('layouts.master')

@section('title') 3D Paving Reports @endsection

@section('content')
    @component('components.breadcrumb')
        @slot('title') Reports @endslot
        @slot('li_1') <a href="/dashboard">Home</a> @endslot
        @slot('li_2') Reports @endslot
    @endcomponent

    <div id="reportlist">
        <table class="table table-bordered">
            <tr>
                <td class="w-25">
                    <a class="resourceLink" 
                       href="{{ route('sales_report_index') }}">@lang('translation.salesreport')</a>
                    <br/>
                </td>
                <td  class="w-75">
                    This report is designed to display sales information and is based on the Sale Date. This sales report can be customized for date range and salesperson.
                </td>
            </tr>
            <tr>
                <td>
                    <a class="resourceLink"
                       href="{{ route('activity_by_status_report_index') }}">@lang('translation.activitybystatusreport')</a>
                    <br/>
                </td>
                <td>
                    This report is based on Proposal Created Date, and will show the current "Status" of all "Proposals Created" during that date range. This report can be customized for date range and salesperson.
                </td>
            </tr>
            <tr>
                <td>
                    <a class="resourceLink"
                       href="{{ route('activity_by_contact_type_report_index') }}">@lang('translation.activitybycontacttypereport')</a><br/>
                </td>
                <td>
                    This report can be customized for date range and salesperson. It will show activity by Customer type.
                </td>
            </tr>
            <tr>
                <td>
                    <a class="resourceLink"
                       href="{{ route('leads_report_index') }}">@lang('translation.leadreport')</a><br/>
                </td>
                <td>
                    This report will show lead sources, proposals created, and sales, it can be customized for year.
                </td>
            </tr>

            <!--
            <tr>
                <td>
                    <a class="resourceLink"
                       href="{{ route('showreport', ['name' => 'sales']) }}">@lang('translation.workerreport')</a><br/>
                </td>
                <td>
                    This sales report can be customized for date range and salesperson.
                </td>
            </tr>

            <tr>
                <td>
                    <a class="resourceLink"
                       href="{{ route('showreport', ['name' => 'sales']) }}">@lang('translation.activityreport')</a><br/>
                </td>
                <td>
                    This sales report can be customized for date range and salesperson.
                </td>
            </tr>
            -->
        </table>

    </div>


    @endsection

