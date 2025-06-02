@extends('layouts.master')

@section('title') 3D Paving Contacts @endsection

@section('content')
    @component('components.breadcrumb')
        @slot('title') @lang('translation.fieldreports') @endslot
        @slot('li_1') <a href="{{ route('dashboard') }}">@lang('translation.Dashboard')</a>@endslot
        @slot('li_2') <a href="{{ route('show_workorder', ['id' => $proposalDetail->id]) }}">@lang('translation.work_order')</a>@endslot
        @slot('li_3') @lang('translation.fieldreports') @endslot
    @endcomponent

    <div class="row admin-form">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8 col-sm-6 mb20">
                            <a
                                href="javascript:"
                                class="action-new-report {{ $site_button_class2}}"
                            >
                                <span class="fas fa-sticky-note"></span>@lang('translation.add') @lang('translation.note')
                            </a>
                        </div>
                        <div class="col-md-4 col-sm-6 mb20">
                            <x-search
                                :needle="$needle"
                                search-route="{{ route('workorder_field_report_search', ['proposal_detail_id' => $proposalDetail->id]) }}"
                                cancel-route="{{ route('workorder_field_report_list', ['proposal_detail_id' => $proposalDetail->id]) }}"
                            ></x-search>
                        </div>
                    </div>
                    <table class="list-table table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                            <tr>
                                <th class="td-sortable tc w500">{!! \App\Traits\SortableTrait::link('workorder_field_reports.proposal_id|proposals.name', 'Workorder Name', ['proposal_detail_id' => $proposalDetail->id]) !!}</th>
                                <th class="td-sortable tc w200">{!! \App\Traits\SortableTrait::link('report_date', 'Report Date', ['proposal_detail_id' => $proposalDetail->id]) !!}</th>
                                <th class="td tc">Desciption</th>
                                <th class="actions">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($fieldReports as $fieldReport)
                                <tr data-id="{{ $fieldReport->id }}">
                                    <td class="tc">
                                        <a href="{{ route('workorder_field_report_details', ['proposal_detail_id' => $fieldReport->proposal_detail_id]) }}">
                                            {{ $fieldReport->full_name }}
                                        </a>
                                        <p>{{ !empty($fieldReport->proposal->name) }}</p>
                                    </td>
                                    <td class="tc">{!! $fieldReport->report_date->format('m/d/Y') !!}</td>
                                    <td class="tc">{!! Str::limit($fieldReport->description, 100) !!}</td>
                                     <td class="centered actions">
                                        <ul class="nav navbar-nav">
                                            <li class="dropdown">
                                                <a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="fa fa-angle-down"></i></a>
                                                <ul class="dropdown-menu animated animated-short flipInX" role="menu">
                                                    <li>
                                                        <a href="javascript:" class="action" data-action="route" data-route="{{ route('contact_edit', ['contact' => $fieldReport->id]) }}">
                                                            <span class="far fa-edit"></span>@lang('translation.edit')
                                                        </a>
                                                    </li>
                                                </ul>
                                            </li>
                                        </ul>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <x-paginator :collection="$fieldReports" :params="['route_params' => ['proposal_detail_id' => $proposalDetail->id]]" route-name="workorder_field_report_list" :needle="$needle"></x-paginator>
                </div>
            </div>
        </div>
    </div>f

    <!-- Modal -->
    <div class="modal fade modal-medium info" id="formFieldReportDateModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="formFieldReportDateModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content admin-form">
                <form method="POST" action="{{ route('workorder_field_report_store') }}" accept-charset="UTF-8" class="admin-form" id="field_report_date_form">
                    @csrf
                    <input type="hidden" name="proposal_id" value="{{ $proposal->id }}">
                    <input type="hidden" name="proposal_detail_id" value="{{ $proposalDetail->id }}">
                    
                    <div class="modal-header">
                        <h5 class="modal-title" id="formFieldReportDateModalLabel">@lang('translation.new') @lang('translation.fieldreport')</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body plr10 pt10 pb0">
                        <x-form-date-picker name="report_date" :value="now()" :params="['label' =>  __('translation.date'), 'iconClass' => 'fas fa-calendar', 'required' => true]"></x-form-date-picker>
                    </div>
                    <div class="modal-footer">
                        <button
                            type="button"
                            class="btn btn-light waves-effect"
                            data-dismiss="modal"
                        >
                            @lang('translation.close')
                        </button>
                        <button
                            type="submit"
                            class="btn btn-dark waves-effect waves-light"
                        >
                            @lang('translation.save')
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

@section('page-js')
    <script>
        $(document).ready(function(){
            var fieldReportModal = $('#formFieldReportDateModal');
            var fieldReportDateForm = $('#field_report_date_form');

            $('body').on('click', '.action-new-report', function(){
                fieldReportModal.modal('show');
            });

            fieldReportModal.on('show.bs.modal', function(){
                fieldReportDateForm.find('em.state-error').remove();
                fieldReportDateForm.find('.field.state-error').removeClass('state-error');
            })

            fieldReportModal.on('hidden.bs.modal', function(){
                fieldReportDateForm.find('em.state-error').remove();
                fieldReportDateForm.find('.field.state-error').removeClass('state-error');
            })

            fieldReportDateForm.validate({
                rules: {
                    report_date: {
                        required: true,
                        date: true
                    }
                },
                messages: {
                    note: {
                        required : "@lang('translation.field_required')",
                        date: "@lang('translation.invalid_entry')"
                    }
                },
                submitHandler: function(form){
                    let errors = false;

                    if (!errors) {
                        form.submit();
                    }
                }
            });
        });
    </script>
@stop

