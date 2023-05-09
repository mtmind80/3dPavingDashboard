@extends('layouts.master')

@section('title') 3D Paving Leads - Edit @endsection

@section('content')
    @component('components.breadcrumb')
        @slot('title') @lang('translation.edit')   @lang('translation.Leads') @endslot
        @slot('li_1') <a href="{{ route('dashboard') }}">@lang('translation.Dashboard')</a>@endslot
        @slot('li_2') <a href="{{ route('lead_list') }}">@lang('translation.Leads')</a>@endslot
        @slot('li_3') @lang('translation.edit') @endslot
    @endcomponent

    <div class="row admin-form">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('lead_update', ['lead' => $lead->id]) }}" accept-charset="UTF-8" id="admin_form" class="admin-form">
                        @csrf
                        {{ method_field('PATCH') }}
                        <input type="hidden" name="id" value="{{ $lead->id }}">
                        @include('leads._form', ['cancel_caption' => Lang::get('translation.cancel'), 'submit_caption' => Lang::get('translation.save')])
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop


