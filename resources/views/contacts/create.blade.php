@extends('layouts.master')

@section('title') 3D Paving Contacts - New @endsection

@section('content')
    @component('components.breadcrumb')
        @slot('title') @lang('translation.create')  @lang('translation.Contacts') @endslot
        @slot('li_1') <a href="{{ route('dashboard') }}">@lang('translation.Dashboard')</a>@endslot
        @slot('li_2') <a href="{{ route('contact_list') }}">@lang('translation.Contacts')</a>@endslot
        @slot('li_3') @lang('translation.new') @endslot
    @endcomponent

    <div class="row admin-form">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('contact_store') }}" accept-charset="UTF-8" id="admin_form" class="admin-form">
                        @csrf
                        @include('contacts._form', ['cancel_caption' => Lang::get('translation.cancel'), 'submit_caption' => Lang::get('translation.save')])
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop




