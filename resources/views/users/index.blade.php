@extends('layouts.master')

@section('title')
    Employees
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('title')
            Active Employees
        @endslot
        @slot('li_1')
            <a href="/dashboard">Home</a>
        @endslot
        @slot('li_2')
            Active Employees
        @endslot
    @endcomponent

    <div class="row admin-form">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
            <div class="row">
                    <div class="col-md-8 col-sm-6 mb20">
                        <a href="{{route('new_user') }}" title="Add User" class="{{$site_button_class}}"><i
                                    class="fas fa-plus"></i>@lang('translation.create') @lang('translation.Employee')</a>

                        <a href="{{route('allusers')}}" title="All User" class="{{$site_button_class}}"><i
                                    class="fas fa-circle-notch"></i>@lang('translation.showdisabled') @lang('translation.Employees')</a>
                    </div>
                    <div class="col-md-4 col-sm-6 mb20">
                        <x-search :needle="$needle" search-route="{{ route('search_user') }}"  cancel-route="{{ route('users') }}"></x-search>
                    </div>

                </div>

            {{ $datum->links() }}
            
            <table id='usertable' class="list-table table table-bordered dt-responsive nowrap"
                   style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                <thead>
                    @foreach ($headers as $key => $value)
                        @if($key != 'id')
                            <th class="td-sortable tc">{!! \App\Traits\SortableTrait::link($key, $value) !!}</th>
                        @else
                            <th class="tc">{{$value}}</th>
                        @endif
                    @endforeach
                </thead>
                </tr>
                
                @foreach ($datum as $data)
                    <tr @if(!$data['status']) class="alert-danger" @endif >
                        <td id="tooltip-container8">
                            <a href="{{ route('edit_user',['id'=>$data['id']]) }}"
                               class="me-3 text-primary tc" title="Edit"
                               data-bs-container="#tooltip-container8"
                               data-bs-toggle="tooltip"
                               data-bs-placement="top" title=""
                               data-bs-original-title="Edit"
                               aria-label="Edit"><i class="mdi mdi-pencil font-size-18"></i></a>
                        </td>

                        <td class="tc"><a href="{{ route('edit_user',['id'=>$data['id']]) }}"
                                          class="me-3 text-primary tc" title="Edit"
                                          data-bs-container="#tooltip-container7"
                                          data-bs-toggle="tooltip"
                                          data-bs-placement="top" title=""
                                          data-bs-original-title="Edit"
                                          aria-label="Edit">{{ $data['fname']}}</a></td>
                        <td class="tc">{{ $data['lname']}}</td>
                        <td class="tc">{{ $data['email']}}</td>
                        <td class="tc">{{ $data['phone']}}</td>
                        <td class="tc">
                            @if($data['status'])
                                ACTIVE
                            @else
                                DISABLED
                            @endif

                        </td>

                        <td class="tc">
                            @if($data['role_id'])
                                {{ App\Models\Role::find($data['role_id'])->role }}
                            @endif
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
            </div>

    </div>
    </div>

@endsection

