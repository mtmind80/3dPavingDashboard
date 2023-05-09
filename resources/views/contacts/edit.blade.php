@extends('layouts.master')

@section('title') 3D Paving Contacts - Edit @endsection

@section('content')
    @component('components.breadcrumb')
        @slot('title') @lang('translation.edit')   @lang('translation.Contacts') @endslot
        @slot('li_1') <a href="{{ route('dashboard') }}">@lang('translation.Dashboard')</a>@endslot
        @slot('li_2') <a href="{{ route('contact_list') }}">@lang('translation.Contacts')</a>@endslot
        @slot('li_3') @lang('translation.edit') @endslot
    @endcomponent

    <div class="row admin-form">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('contact_update', ['contact' => $contact->id]) }}" accept-charset="UTF-8" id="admin_form" class="admin-form">
                        @csrf
                        {{ method_field('PATCH') }}
                        <input type="hidden" name="id" value="{{ $contact->id }}">
                        @include('contacts._form', ['cancel_caption' => Lang::get('translation.cancel'), 'submit_caption' => Lang::get('translation.save')])
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop


