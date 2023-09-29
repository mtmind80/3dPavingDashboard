@extends('layouts.master')

@section('title')
    3D Paving Proposals
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('title') @lang('translation.active') @lang('translation.menu_workorders') @endslot
        @slot('li_1') <a href="{{route("dashboard")}}">@lang('translation.Dashboard')</a> @endslot
        @slot('li_2') @lang('translation.menu_workorders') @endslot
    @endcomponent

    <div class="row admin-form">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mt5">
                        <div class="col-md-8 col-sm-6 mb10">
                            {{ $workorders->links() }}
                        </div>
                        <div class="col-md-4 col-sm-6 mb10">
                            <x-search :needle="$needle" search-route="{{ route('workorder_search') }}" cancel-route="{{ route('workorders') }}" ></x-search>
                        </div>
                    </div>

                    @inject('SortableTrait', \App\Models\Proposal::class)

                    <div class="row">
                        <table id='proposaltable'
                               class="table list-table table-bordered dt-responsive nowrap table-striped"
                               style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead class="table-light">
                                <tr>
                                    <th class="tc w140">{!! $SortableTrait->alink('job_master_id', __('translation.workorderid')) !!}</th>
                                    <th class="sorting_disabled tc">{!! $SortableTrait->alink('name', __('translation.name')) !!}</th>
                                    <th class="tc">{!! $SortableTrait->alink('proposals.contact_id|contacts.first_name', __('translation.Customer')) !!}</th>
                                    <th class="sorting_disabled tc person-name">{!! $SortableTrait->alink('proposals.salesperson_id|users.fname', __('translation.SalesPerson')) !!}</th>
                                    <th class="sorting_disabled tc">@lang('translation.location')</th>
                                    <th class="sorting_disabled tc w180">{!! $SortableTrait->alink('proposals.proposal_statuses_id|proposal_statuses.status', __('translation.status')) !!}</th>
                                    <th class="actions">@lang('translation.actions')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (!$workorders)
                                    <tr class="even">
                                        <td colspan='6' class="text-dark fw-bold">
                                            @lang('translation.norecordsfound')
                                         </td>
                                    </tr>
                                @else
                                    @foreach ($workorders as $workorder)
                                        <tr>
                                            <td class="tc text-dark fw-bold">
                                                <a href="{{ route('show_workorder',['id'=>$workorder->id]) }}" title="@lang('translation.view')">
                                                    {{ $workorder->job_master_id }}
                                                </a>
                                            </td>
                                            <td class="tc">
                                                <a href="{{ route('show_workorder',['id'=>$workorder->id]) }}" title="@lang('translation.view')">
                                                    {{ $workorder->name }}
                                                </a>
                                            </td>
                                            <td class="tc">
                                                <a href="{{ route('contact_details', ['contact'=>$workorder->contact_id]) }}" title="@lang('translation.contact')">
                                                    {{ $workorder->contact->full_name }}
                                                </a>
                                            </td>
                                            <td class="tc text-dark fw-bold">{{ $workorder->salesPerson->full_name ?? 'No Manager Assigned' }}</td>
                                            <td class="tc text-dark fw-bold">{!! $workorder->location->short_location_two_lines ?? null !!}</td>
                                            <td class="tc text-dark fw-bold">{{ $workorder->status->status ?? null }}</td>
                                            <td class="centered actions">
                                                <ul class="nav navbar-nav">
                                                    <li class="dropdown">
                                                        <a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="fa fa-angle-down"></i></a>
                                                        <ul class="dropdown-menu animated animated-short flipInX" role="menu">
                                                            <li>
                                                                <a href="javascript:" class="action" data-action="add-note" data-route="{{ route('workorder_note_store', ['work_order' => $workorder->id]) }}" data-workorder_name="{{ $workorder->name }}"
                                                                   data-proposal_id="{{ $workorder->id }}">
                                                                    <span class="fas fa-sticky-note tc mr6"></span>@lang('translation.add') @lang('translation.note')
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="javascript:" class="action" data-action="route" data-route="{{ route('show_workorder', ['id' => $workorder->id]) }}">
                                                                    <span class="fas fa-eye tc mr6"></span>@lang('translation.view')
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="javascript:" class="action" data-action="route" data-route="{{ route('contact_details', ['contact' => $workorder->contact_id] ) }}">
                                                                    <span class="fas fa-user tc mr6"></span>@lang('translation.Customer') @lang('translation.details')
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </li>
                                                </ul>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>

                    <x-paginator :collection="$workorders" route-name="workorders" :needle="$needle" :params="['lang' => $lang]" class="mt10"></x-paginator>
                </div>
            </div>
        </div>
    </div>

    @include('modals.form_workorder_note_modal')
@endsection

@section('page-js')
    <script>
        $(document).ready(function(){
            var body = $('body');

            var noteModal = $('#formNoteModal');
            var noteForm = $('#admin_form_note_modal');
            var note = $('#note');
            var proposal_id = $("#proposal_id");

            body.on('click', '.actions .action[data-action="add-note"]', function(){
                let el = $(this);
                let workorderNameContainer = $('#formNoteModalLabel').find('span');
                let url = el.data('route');
                let workorderName = el.data('workorder_name');
                $("#proposal_id").val(el.data('proposal_id'));
                var q = $("#proposal_id").val();
                console.log(q);
                noteForm.attr('action', url);
                workorderNameContainer.text(workorderName);
                noteModal.modal('show');
            });

            noteModal.on('show.bs.modal', function(){
                noteForm.find('em.state-error').remove();
                noteForm.find('.field.state-error').removeClass('state-error');
            })

            noteModal.on('hidden.bs.modal', function(){
                noteForm.find('em.state-error').remove();
                noteForm.find('.field.state-error').removeClass('state-error');
            })

            noteForm.validate({
                rules: {
                    note: {
                        required : true,
                        plainText: true
                    }
                },
                messages: {
                    note: {
                        required : "@lang('translation.field_required')",
                        plainText: "@lang('translation.invalid_entry')"
                    }
                },
                submitHandler: function(form){
                    let errors = false;

                    if (!errors) {
                        noteForm.submit();
                    }
                }
            });
        });
    </script>
@stop
