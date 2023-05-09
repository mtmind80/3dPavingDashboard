@extends('layouts.master')

@section('title')
    vendor
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('title')
            @lang('translation.vendors')
        @endslot
        @slot('li_1')
            <a href="/resources">@lang('translation.menu_resources')</a>
        @endslot
        @slot('li_2')
            @lang('translation.vendors')
        @endslot
    @endcomponent

    <div class="row admin-form">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8 col-sm-6 mb20">
                            <a href="{{route('new_vendor') }}" title="Add Vendor" class="btn  btn-success"><i
                                        class="fas fa-plus"></i>@lang('translation.create')</a>

                        </div>
                        <div class="col-md-4 col-sm-6 mb20">
<!--
                            <x-search :needle="$needle" search-route="{{ route('search_vendor') }}"  cancel-route="{{ route('vendor_list') }}"></x-search>
                        -->
                        </div>

                    </div>


                    <table id='vendortable' class="list-table table table-bordered dt-responsive nowrap"
                           style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                                <th class="tc">Edit</th>
                                <th class="td-sortable tc">{!! \App\Traits\SortableTrait::link('name', 'Name') !!}</th>
                                <th class="tc">Address</th>
                                <th class="td-sortable tc">{!! \App\Traits\SortableTrait::link('vendor_type_id', 'Type') !!}</th>
                                <th class="tc">Email</th>
                        </thead>
                        </tr>

                        @foreach ($vendors as $vendor)
                            <tr>
                                <td id="tooltip-container8">
                                    <a href="{{ route('edit_vendor',['id'=>$vendor['id']]) }}"
                                       class="me-3 text-primary tc" title="Edit"
                                       data-bs-container="#tooltip-container8"
                                       data-bs-toggle="tooltip"
                                       data-bs-placement="top" title=""
                                       data-bs-original-title="Edit"
                                       aria-label="Edit"><i class="mdi mdi-pencil font-size-18"></i></a>
                                </td>
                                <td class="tc">{{ $vendor['name']}}</td>
                                <td class="tc">{{ $vendor['address_line1']}}</td>
                                <td class="tc">
                                    @if($vendor['vendor_type_id'])
                                        {{ App\Models\VendorType::find($vendor['vendor_type_id'])->type }}
                                    @endif
                                    
                                </td>
                                <td class="tc">{{ $vendor['email']}}</td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>

        </div>
    </div>

@endsection

