@extends('layouts.master')

@section('title')
    3D Paving Resources
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('title')
            {{$headername}}
        @endslot
        @slot('li_1')
            <a href="/resources">@lang('translation.menu_resources')</a>
        @endslot
        @slot('li_2')            <a href="/getmodel/{{$model}}">{{$headername}}</a>
      @endslot    @endcomponent

    <div>
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <a href="{{route('new_resource',['model'=>$model]) }}" title="Add Resource" class="btn  btn-success"><i class="fas fa-plus"></i>@lang('translation.create')</a>
                </div>
                <div class="card-body">

                    <table class="table table-bordered">
                        <tr>

                            @foreach ($headers as $header)
                                <td class="header-item">{{ $header }}</td>
                            @endforeach
                        </tr>

                        @foreach ($datum as $data)
                            <tr>
                                <td id="tooltip-container8">
                                    <a href="{{ route('edit_resource',['model'=>$model,'id'=>$data['id']]) }}"
                                       class="me-3 text-primary" title="Edit"
                                       data-bs-container="#tooltip-container8"
                                       data-bs-toggle="tooltip"
                                       data-bs-placement="top" title=""
                                       data-bs-original-title="Edit"
                                       aria-label="Edit"><i class="mdi mdi-pencil font-size-18"></i></a>
                                </td>
                                <td>{{ $data['type'] }}</td>
                            </tr>
                        @endforeach

                    </table>


                </div>
            </div>
        </div>
    </div>
@endsection

