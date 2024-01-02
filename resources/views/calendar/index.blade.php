@extends('layouts.master')
@section('title') Calendarss @endsection

@section('content')
@component('components.breadcrumb')
    @slot('title') Calendar @endslot
    @slot('li_1') <a href="/dashboard">Home</a> @endslot
    @slot('li_2') Calendar @endslot
@endcomponent

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div id='calendar'>
                        <iframe src="https://calendar.google.com/calendar/embed?src=mtmind4media%40gmail.com&ctz=America%2FNew_York" style="border: 0" width="800" height="600" frameborder="0" scrolling="no"></iframe>

                    </div>

                </div>
            </div>
        </div>
    </div>

@endsection

