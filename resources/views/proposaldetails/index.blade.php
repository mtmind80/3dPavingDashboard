@extends('layouts.master')

@section('title')
    3D Paving
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('title')
            {{$headername}}
        @endslot
        @slot('li_1')
            <a href="{{route('show_proposal', ['id'=>$id])}}">@lang('translation.proposal')</a>
        @endslot
        @slot('li_2')
            @lang('translation.selectservice')
        @endslot
    @endcomponent

    <div>
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    
                </div>
                <div class="card-body">

                    <form 
                          action="{{ route('create_detail',['proposal_id'=>$proposal_id]) }}" novalidate=""
                          method="POST"
                          id="editform">
                        @csrf
                        <div class="row">
                            <div class="form-group col-lg-12">
                                <label>@lang('translation.select')</label>

                                @component('components.widget')
                                    @slot('name')
                                        service_id
                                    @endslot
                                    @slot('model')
                                        Service
                                    @endslot
                                    @slot('value')
                                        name
                                    @endslot

                                    @slot('default')
                                        1
                                    @endslot
                                @endcomponent

                            </div>

                        </div>
                        <div class="row">
                            <div class="form-group mb-0  col-lg-12">
                                <div>
                                    <button type="button" id='submitbutton'
                                            class="btn btn-primary waves-effect waves-light mr-1">@lang('translation.submit')
                                    </button>
                                    <button type="button" id="cancel"
                                            class="btn btn-secondary waves-effect">@lang('translation.reset')</button>

                                    <button type="button" id="cancelbutton"
                                            class="btn btn-danger waves-effect">@lang('translation.cancel')</button>
                                </div>
                            </div>
                        </div>
                    </form>


                </div>
            </div>
        </div>
    </div>
@endsection

