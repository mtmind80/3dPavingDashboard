@extends('layouts.master')

@section('title')
    Contractors
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('title')
            @lang('translation.sub')  @lang('translation.contractors')
        @endslot
        @slot('li_1')
            <a href="/resources">@lang('translation.menu_resources')</a>
        @endslot
        @slot('li_2')
            @lang('translation.sub')  @lang('translation.contractors')
        @endslot
    @endcomponent

    <div class="row admin-form">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8 col-sm-6 mb20">
                            <a href="{{route('new_contractor') }}" title="Add Contractor" class="btn  btn-success"><i
                                        class="fas fa-plus"></i>@lang('translation.create')</a>

                        </div>
                        <div class="col-md-4 col-sm-6 mb20">
<!--
                            <x-search :needle="$needle" search-route="{{ route('search_contractor') }}"  cancel-route="{{ route('contractor_list') }}"></x-search>
                        -->
                        </div>

                    </div>


                    <table id='contractortable' class="list-table table table-bordered dt-responsive nowrap"
                           style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                                <th class="tc">Edit</th>
                                <th class="td-sortable tc">{!! \App\Traits\SortableTrait::link('name', 'Name') !!}</th>
                                <th class="tc">Address</th>
                                <th class="td-sortable tc">{!! \App\Traits\SortableTrait::link('contractor_type_id', 'Type') !!}</th>
                                <th class="tc">Email</th>
                        </thead>
                        </tr>

                        @foreach ($contractors as $contractor)
                            <tr>
                                <td id="tooltip-container8">
                                    <a href="{{ route('edit_contractor',['id'=>$contractor['id']]) }}"
                                       class="me-3 text-primary tc" title="Edit"
                                       data-bs-container="#tooltip-container8"
                                       data-bs-toggle="tooltip"
                                       data-bs-placement="top" title=""
                                       data-bs-original-title="Edit"
                                       aria-label="Edit"><i class="mdi mdi-pencil font-size-18"></i></a>
                                </td>
                                <td class="tc">{{ $contractor['name']}}</td>
                                <td class="tc">{{ $contractor['address_line1']}}</td>
                                <td class="tc">
                                    @if($contractor['contractor_type_id'])
                                        {{ App\Models\ContractorType::find($contractor['contractor_type_id'])->type }}
                                    @endif
                                    
                                </td>
                                <td class="tc">{{ $contractor['email']}}</td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>

        </div>
    </div>

@endsection

