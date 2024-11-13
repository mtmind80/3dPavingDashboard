@extends('layouts.master')

@section('title')
    @lang('translation.Dashboard')
@endsection

@section('css')
    <!-- DataTables -->
    <link href="{{ URL::asset('/assets/libs/jquery-vectormap/jquery-vectormap.min.css')}}" rel="stylesheet"
          type="text/css"/>
    <!-- Responsive datatable examples -->
    <link href="{{ URL::asset('/assets/libs/datatables/datatables.min.css')}}" rel="stylesheet" type="text/css"/>
@endsection

@section('content')
    @section('content')
        @component('components.breadcrumb')
            @slot('title')
                @lang('translation.dashboard1')
            @endslot
            @slot('li_1')
                3D Paving @lang('translation.dashboard')
            @endslot
            @slot('li_2')
                @lang('translation.dashboard1')
            @endslot
        @endcomponent
        <div class="row admin-form">
            <div class="col-md-12">
                <div class="card tabs-container">
                    <div class="card-body">
                        <!-- Nav tabs -->
                        @include('layouts.nav_tabs')
                        <!-- Tab panes -->
                        <div class="tab-content active text-muted">
                            <div class="tab-pane active" id="dashboard_1" role="tabpanel">

                                @if (auth()->user()->role_id <= 4 || auth()->user()->role_id == 7 )
                                    <div class="row">
                                        <div class="col-md-12">
                                            @if (!empty($leads))
                                                <div class="card">
                                                    <div class="card-body">
                                                        <h4 class="card-title mb20">@lang('translation.Leads')</h4>
                                                        {{ $leads->links() }}
                                                        <div class="table-responsive">
                                                            <table class="table mb-0">
                                                                <thead>
                                                                <tr>
                                                                    <th class="tc">@lang('translation.created')</th>
                                                                    <th class="tc">@lang('translation.created_by')</th>
                                                                    <th class="tc">@lang('translation.lead_source')</th>
                                                                    <th class="tc">@lang('translation.contact')</th>
                                                                    <th class="tc">@lang('translation.previous_assigned_to')</th>
                                                                    <th class="tc">@lang('translation.assigned_to')</th>
                                                                    <th class="tc">@lang('translation.type_of_work_needed')</th>
                                                                    <th class="tc">@lang('translation.city')</th>
                                                                    @if (auth()->user()->isSuperAdmin())
                                                                        <th class="tc">@lang('translation.action')</th>
                                                                    @endif
                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                @foreach ($leads as $lead)
                                                                    <tr>
                                                                        <td class="tc">{{ $lead->created_at->format('m/d/Y') }}</td>
                                                                        <td class="tc">{{ $lead->creator->full_name }}</td>
                                                                        <td class="tc">{{ $lead->lead_source }}</td>
                                                                        <td class="tc">{{ $lead->full_name ?? null }}</td>
                                                                        <td class="tc">{{ $lead->previousAssignedTo->full_name ?? null }}</td>
                                                                        <td class="tc">{{ $lead->assignedTo->full_name ?? null }}</td>
                                                                        <td class="tc">{{ $lead->type_of_work_needed }}</td>
                                                                        <td class="tc">{{ $lead->city }}</td>
                                                                        @if (auth()->user()->isSuperAdmin())
                                                                            <td class="tc">
                                                                                <a href="javascript:" class="action"
                                                                                   data-action="assign-to"
                                                                                   data-route="{{ route('lead_assign_to', ['lead' => $lead->id]) }}"
                                                                                   data-lead_name="{{ $lead->full_name }}">Assign</a>
                                                                            </td>
                                                                        @endif
                                                                    </tr>
                                                                @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                                @if (auth()->user()->isSuperAdmin())
                                                    @include('modals.form_managers_modal')
                                                @endif
                                            @endif
                                        </div>
                                    </div>


                            </div>
                        </div>
                        @endif
                        <div class="row">
                            @if(auth()->user()->isAdmin())
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <h4 class="card-title mb20">@lang('translation.menu_workorders') @lang('translation.on_alert')</h4>
                                            <div class="table-responsive">
                                                <table class="table mb-0">
                                                    <thead>
                                                    <tr>
                                                        <th class="tc">@lang('translation.work_order') @lang('translation.Name')</th>
                                                        <th class="tc">@lang('translation.work_order') @lang('translation.number')</th>
                                                        <th class="tc">@lang('translation.reason')</th>
                                                        <th class="tc">@lang('translation.client')</th>
                                                        <th class="tc">@lang('translation.action')</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach ($onalert as $alert)
                                                        <tr>
                                                            <td class="tc">{{ $alert->name ?? null }}</td>
                                                            <td class="tc">{{ $alert->WorkOrderNumber ?? null }}</td>
                                                            <td class="tc">{{ $alert->alert_reason ?? null }}</td>
                                                            <td class="tc">{{ $alert->contact->full_name ?? null }}</td>
                                                            <td class="tc"><a
                                                                    href="{{ route('alert_proposal',['id'=>$alert->id]) }}">View</a>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            @endif
                        </div>

                    </div>
                </div>
                <div class="tab-content text-muted">
                    <div class="tab-pane" id="dashboard_2" role="tabpanel">
                    </div>
                </div>
                <div class="tab-content text-muted">
                    <div class="tab-pane" id="dashboard_3" role="tabpanel">
                    </div>
                </div>
                <div class="tab-content text-muted">
                    <div class="tab-pane" id="dashboard_4" role="tabpanel">
                    </div>
                </div>
            </div>
        </div>
        </div>
        </div>

        @include('modals.scrollable_modal')
    @endsection

    @section('script')
        <!-- Responsive examples -->
        <script src="{{ URL::asset('/assets/libs/datatables/datatables.min.js')}}"></script>
    @endsection

    @section('page-js')
        <script>
            var body = $('body');

            var assignedTo = $('#assigned_to');
            var returnTo = $('#form_managers_modal_return_to');
            returnTo.val("{{ route('dashboard') }}");

            var managersModal = $('#formManagersModal');
            var managersForm = $('#admin_form_managers_modal');

            body.on('click', '.action[data-action="assign-to"]', function () {
                let el = $(this);
                let leadNameContainer = $('#formManagersModalLabel').find('span');
                let url = el.data('route');
                let leadName = el.data('lead_name');

                managersForm.attr('action', url);
                leadNameContainer.text(leadName);
                managersModal.modal('show');
            });

            managersModal.on('show.bs.modal', function () {
                assignedTo.val('');
                managersForm.find('em.state-error').remove();
                managersForm.find('.field.state-error').removeClass('state-error');
            })

            managersModal.on('hidden.bs.modal', function () {
                assignedTo.val('');
                managersForm.find('em.state-error').remove();
                managersForm.find('.field.state-error').removeClass('state-error');
            })


            body.on('click', '.actions .action[data-action="archive"]', function () {
                uiConfirm({
                    callback: 'confirmArchive',
                    text: $(this).attr('data-text'),
                    params: [$(this).attr('data-route')]
                });
            });

            $('.component-breadcrumb-filters').on('change', 'select', function () {
                // window.location = $(this).find('option:selected').data('url')

                $(this).closest('.component-breadcrumb-filters').find('.submit-button').removeClass('hidden');
            });

            $('.component-breadcrumb-filters').on('click', '.submit-button', function () {
                let button = $(this)
                let container = button.closest('.component-breadcrumb-filters');
                let filters = container.find('select');
                let queryArray = [];
                let queryStr = '';
                let url = "{{ Request::url() }}";

                button.addClass('hidden');

                filters.each(function () {
                    let el = $(this);
                    let val = el.val();

                    console.log(val);

                    if (val !== 'all') {
                        queryArray.push(el.attr('name') + '=' + val);
                    }
                });

                queryStr = queryArray.join('&');

                if (queryStr !== '') {
                    url = url + '?' + queryStr;
                }

                window.location = url;
            });


        </script>
    @endsection


