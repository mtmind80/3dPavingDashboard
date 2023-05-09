@extends('layouts.master')

@section('title')
    3D Paving Employees
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('title')
            Employees
        @endslot
        @slot('li_1')
            <a href="/dashboard">Home</a>
        @endslot
        @slot('li_2')
            Employees
        @endslot
    @endcomponent

    <div >
        <div class="card">
            <div class="card-header">
                <button 
                        onClick="javascript:window.location.href='{{ route('create_user')}}';" 
                        class="btn-success" 
                        value="@lang('translation.new')">
                    <i class="fas fa-plus"></i>
                </button>

                <a href="{{route('create_user')}}"  class="btn-success"><i class="fas fa-plus"></i>@lang('translation.AddNew')</a>
            </div>

        <table class="table table-bordered">
            <tr>
                @foreach ($headers as $head)
                    <th>{{ $head }}</th>
                @endforeach

            </tr>
            @foreach ($datum as $data)
                <tr>
                    @foreach ($data as $column => $value)
                        @if(in_array($column,$columns))
                            <td>{{ $value }}</td>
                        @endif
                    @endforeach
                </tr>
            @endforeach
        </table>
    </div>

    </div>

@endsection

