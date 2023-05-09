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
                <td>
                    <a  class="ri-record-circle-line" href="{{ route('showreport', ['name' => 'sales']) }}"> RUN</a>
                </td>
                <td>
                    <a class="resourceLink"
                       href="{{ route('showreport', ['name' => 'sales']) }}">@lang('translation.salesreport')</a><br/>
                </td>
                <td>
                    This sales report can be customized for date range and salesperson.
                </td>
            </tr>
        </table>

    </div>


    @endsection

