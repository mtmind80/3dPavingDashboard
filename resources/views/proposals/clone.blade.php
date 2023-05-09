@extends('layouts.master')

@section('title')
    3D Paving Proposals
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('title')
            @lang('translation.Proposals')
        @endslot
        @slot('li_1')
            <a href="/dashboard">@lang('translation.Dashboard')</a>
        @endslot
        @slot('li_2')
            @lang('translation.Proposals')
        @endslot
    @endcomponent


    <div class="row admin-form">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8 col-sm-6 mb20">
                            <x-href-button url="{{ route('proposals') }}" class="btn-success"><i class="fas fa-plus"></i>@lang('translation.view') @lang('translation.proposals')</x-href-button>
                        </div>
                        <div class="col-md-4 col-sm-6 mb20">
                            <x-search :needle="$needle" search-route="{{ route('inactiveproposal_search') }}" cancel-route="{{ route('inactive_proposals') }}" ></x-search>
                        </div>

                    </div>
                    {{ $records->links() }}
                    <div class="row">


                        <table id='proposaltable' class="table list-table table-bordered dt-responsive nowrap table-striped"
                               style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                           
                            <thead class="table-light">
                            <tr>

                                <th class="sorting_disabled tc"  style="width: 32px;" >
                                    @lang('translation.View_More')
                                </th>

                                <th class="td-sortable tc" style="width: 60px;">
                                    @lang('translation.client')
                                </th>

                                <th class="td-sortable tc" style="width: 140px;">
                                    {!! \App\Traits\SortableTrait::link('name', 'Name') !!}
                                </th>
                                <th class="sorting_disabled tc"  style="width: 67px;" >
                                    @lang('translation.manager')
                                </th>
                                <th class="sorting_disabled tc"  style="width: 128px;" >
                                    @lang('translation.location')
                                </th>
                                <th class="sorting_disabled tc"  style="width: 32px;" >
                                    @lang('translation.status')
                                </th>
                                <th class="actions">Actions</th>
                            </tr>
                            </thead>
                            <tbody>

                                <tr class="even">
                                    <td colspan='7' class="text-dark fw-bold">
                                        @lang('translation.norecordsfound') 
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

