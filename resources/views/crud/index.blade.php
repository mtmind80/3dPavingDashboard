@extends('layouts.master')

@section('title') 3D Paving Resources @endsection

@section('content')
    @component('components.breadcrumb')
        @slot('title') {{$headername}} @endslot
        @slot('li_1') <a href="/resources">@lang('translation.menu_resources')</a> @endslot
        @slot('li_2') {{$headername}} @endslot
    @endcomponent

    <div >
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                 
                </div>
                <div class="card-body">

        <table class="table table-bordered">
            <tr>

                @foreach ($headers as $header)
                    <td class="header-item">{{ $header }}</td>
                @endforeach
            </tr>

            @if ($p)
                @foreach ($datum as $data)
                    <tr>
                        @foreach ($data->toArray() as $column => $value)
                            <td>{{ $value }}</td>
                        @endforeach
                    </tr>
                @endforeach
            @else
                @foreach ($datum as $data)
                    <tr>
                        @foreach ($data as $key=>$value)
                            @if(in_array($key,$columns))
                                @if($key =='id')
                                    <td><a href="{{ route('edit_resource', ['model'=>$headername,'id'=>$value]) }}">{{ $value }}</a></td>
                                @else
                            <td>{{ $value }}</td>
                                    @endif
                            @endif
                        @endforeach
                    </tr>
                @endforeach

            @endif
        </table>


                </div>
            </div>
    </div>
    </div>
    @if ($p)
        {{ $datum->links() }}
    @endif
    
    @endsection

