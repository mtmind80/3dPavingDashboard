@extends('layouts.master')

@section('title') 3D Paving Contacts - New @endsection

@section('content')
    @component('components.breadcrumb')
        @slot('title') @lang('translation.equipment')@endslot
        @slot('li_1') <a href="{{ route('dashboard') }}">@lang('translation.Dashboard')</a>@endslot
        @slot('li_2') <a href="{{ route('show_workorder', ['id' => $proposalDetail->proposal_id]) }}">@lang('translation.show') @lang('translation.work_order')</a>@endslot
        @slot('li_3') @lang('translation.new') @endslot
    @endcomponent

    <div class="row admin-form">
        <div class="col-12">

            <!--  header -->

            <div class="card">
                @include('_partials._alert')
                <div class="card-body">
                    <h6 class="">@lang('translation.work_order'): <span class="ml8 fwn info-color">{{ $proposalDetail->proposal->name }}</span></h6>
                    <h6 class="mt-3">@lang('translation.work_order') @lang('translation.service'): <span class="ml8 fwn info-color">{{ $proposalDetail->service->name }}</span></h6>
                    @if (!empty($reportDate))
                        <h6 class="mt-3">@lang('translation.report') @lang('translation.date'): <span class="ml8 fwn info-color">{{ $reportDate->toFormattedDayDateString() }}</span></h6>
                    @endif
                </div>
            </div>
            <!--  entry form  -->

            <div class="card">
                <div class="card-body">
                    <h5 class="mb-4">@lang('translation.new')</h5>
                    <form method="POST" action="{{ route('workorder_equipment_store') }}" accept-charset="UTF-8" id="adminForm" class="admin-form">
                        @csrf
                        <input type="hidden" name="proposal_id" id="proposal_id" value="{{ $proposalDetail->proposal_id ?? null }}">
                        <input type="hidden" name="proposal_detail_id" id="proposal_detail_id" value="{{ $proposalDetail->id ?? null }}">

                        <div class="row">
                            <div class="col-lg-3 col-md-3 col-sm-6 admin-form-item-widget">
                                <x-form-date-picker
                                    name="report_date"
                                    :params="[
                                    'id' => 'report_date',
                                    'label' => 'Report day',
                                    'iconClass' => 'fas fa-calendar',
                                    'value' => $reportDate,
                                ]"
                                ></x-form-date-picker>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-6 admin-form-item-widget">
                                <x-form-select name="equipment_id"
                                    :items="$equipmentCB"
                                    selected=""
                                    :params="['label' => 'Equipment', 'required' => true]"
                                ></x-form-select>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-6 admin-form-item-widget">
                                <x-form-text name="hours" class="check-contact"
                                     :params="['label' => 'Hours', 'iconClass' => 'far fa-clock', 'required' => true]"
                                ></x-form-text>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-6 admin-form-item-widget">
                                <x-form-text name="number_of_units" class="check-contact"
                                     :params="['label' => 'Number of units', 'iconClass' => 'fas fa-bookmark', 'required' => true]"
                                ></x-form-text>
                            </div>
                        </div>

                        <div class="row buttons">
                            <div class="col-sm-12 tr">
                                <x-button id="cancel_button" class="btn-light"><i
                                        class="far fa-arrow-alt-circle-left "></i>{{ $cancel_caption ?? 'Cancel' }}</x-button>
                                <x-button id="submit_button" class="btn-dark" type="submit"><i class="fas fa-save"></i>{{ $submit_caption ?? 'Submit' }}
                                </x-button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- entry list -->
            @if (!empty($equipments))
                <div class="card">
                    <div class="card-body">
                        <h5 class="mb-4">@lang('translation.equipment')</h5>
                        <table class="list-table table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                            <tr>
                                <th class="tc">Equipment</th>
                                <th class="tc w200">Hours</th>
                                <th class="tc w200">Rate per Hour</th>
                                <th class="tc w200">Number of Units</th>
                                <th class="tc w100">@lang('translation.action')</th>
                            </tr>
                            </thead>

                            <tbody>
                                @foreach ($equipments as $equipment)
                                    <tr {{ !empty($equipment->status->color) ? ' style=background-color:#'.$equipment->status->color.' ' : '' }}data-id="{{ $equipment->id }}">
                                        <td class="tc">{{ $equipment->name }}</td>
                                        <td class="tc">{{ $equipment->hours }}</td>
                                        <td class="tc">{{ \App\Helpers\Currency::format($equipment->rate_per_hour) }}</td>
                                        <td class="tc">{{ $equipment->number_of_units }}</td>
                                        <td class="centered">
                                            <form action="{{ route('workorder_equipment_destroy',['workorder_equipment_id' => $equipment->id]) }}" method="post">
                                                @csrf
                                                <input name="_method" type="hidden" value="DELETE">
                                                <button class="btn p0 btn-danger tc" type="submit" data-toggle="tooltip" title="Delete item"><i class="far fa-trash-alt dib m0 plr5"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <x-paginator
                            :collection="$equipments"
                            route-name="workorder_equipment_list"
                            :params="[
                                    'route_params' => ['proposal_detail_id' => $proposalDetail->id],
                                    'pageLimits' => [25, 50, 100],
                                ]"
                        ></x-paginator>
                    </div>
                </div>
            @endif
        </div>
    </div>
@stop

@section('page-js')
    <script>
        $(document).ready(function () {
            $('#report_date').change(function(){
                let val = $(this).val();

                if (isUSDate(val)) {
                    window.location = "{{ $currentUrl }}" + '?report_date=' + val;
                }
            });

            $('#adminForm').validate({
                rules: {
                    report_date: {
                        required: true,
                        date    : true
                    },
                    equipment_id: {
                        required: true,
                        positive: true
                    },
                    hours: {
                        required: true,
                        float   : true
                    },
                    number_of_units: {
                        required: true,
                        positive: true
                    }
                },
                messages: {
                    report_date: {
                        required: "@lang('translation.field_required')",
                        date    : "@lang('translation.invalid_entry')"
                    },
                    equipment_id: {
                        required: "@lang('translation.field_required')",
                        positive: "@lang('translation.select_item')"
                    },
                    hours: {
                        required: "@lang('translation.field_required')",
                        float   : "@lang('translation.select_item')"
                    },
                    number_of_units: {
                        required: "@lang('translation.field_required')",
                        positive: "@lang('translation.invalid_entry')"
                    }
                },
                submitHandler: function (form) {
                    let errors = false;

                    if (!errors) {
                        form.submit();
                    }
                }
            });

            $('#cancel_button').click(function () {
                window.location = "{{ $returnTo }}";
            });
        });
    </script>
@stop
