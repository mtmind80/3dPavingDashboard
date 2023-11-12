<!-- labor sections -->

<!-- input fields header row -->
<div class="row">
    <div class="col-sm-6 mb2 fwb">
        <label class="control-label">Select Labor<i class="field-required fa fa-asterisk" data-toggle="tooltip" title="@lang('translation.field_required')"></i></label>
    </div>
    <div class="col-sm-2 tc">
        <label class="control-label">Number<i class="field-required fa fa-asterisk" data-toggle="tooltip" title="@lang('translation.field_required')"></i></label>
    </div>
    <div class="col-sm-2 tc">
        <label class="control-label">Days<i class="field-required fa fa-asterisk" data-toggle="tooltip" title="@lang('translation.field_required')"></i></label>
    </div>
    <div class="col-sm-2 tc">
        <label class="control-label">Hours<i class="field-required fa fa-asterisk" data-toggle="tooltip" title="@lang('translation.field_required')"></i></label>
    </div>
</div>

<!-- input fields values row -->
<form method="POST" action="#" accept-charset="UTF-8" id="labor_form" class="admin-form">
    @csrf
    <input type="hidden" name="proposal_detail_labor_id" id="proposal_detail_labor_id">
    <div class="row">
        <div class="col-sm-3 admin-form-item-widget">
            <x-form-select name="labor_id"
                :items="$laborCB"
                selected="0"
                :params="[
                    'id' => 'labor_id',
                    'iconClass' => 'none',
                ]"
            ></x-form-select>
        </div>
        <div class="col-sm-3 xs-hidden"></div>
        <div class="col-sm-2 tc admin-form-item-widget">
            <x-form-text name="number"
                 class="check-contact tc"
                 placeholder="enter value"
                 id="labor_quantity"
                 :params="[
                    'label' => 'none',
                    'iconClass' => 'none',
                    'value' => '',
                ]"
            ></x-form-text>
        </div>
        <div class="col-sm-2 tc admin-form-item-widget">
            <x-form-text name="days"
                 class="check-contact tc"
                 placeholder="enter value"
                 id="labor_days"
                 :params="[
                    'label' => 'none',
                    'iconClass' => 'none',
                    'value' => '',
                ]"
            ></x-form-text>
        </div>
        <div class="col-sm-2 tc admin-form-item-widget">
            <x-form-text name="hours"
                 class="check-contact tc"
                 placeholder="enter value"
                 id="labor_hours"
                 :params="[
                    'label' => 'none',
                    'iconClass' => 'none',
                    'value' => '',
                ]"
            ></x-form-text>
        </div>
    </div>
</form>

<!-- labor header row -->
<div id="labor_rows_header" class="row fwb pb4 border-bottom-solid{{ !empty($proposalDetail->labor) && $proposalDetail->labor->count() > 0 ? '' : ' hidden' }}">
    <div class="col-sm-6">Labor Type</div>
    <div class="col-sm-1 tc">Quantity</div>
    <div class="col-sm-1 tc">Days</div>
    <div class="col-sm-1 tc">Hours</div>
    <div class="col-sm-1 tc">Rate</div>
    <div class="col-sm-1 tc">Total</div>
    <div class="col-sm-1 tc">Edit / Remove</div>
</div>

<!-- labor row -->
<div id="labor_rows_container" class="mb20">
    @if (!empty($proposalDetail->labor) && $proposalDetail->labor->count() > 0)
        @include('estimator._form_service_labor', ['labors' => $proposalDetail->labor])
    @endif
</div>

<!-- labor footer row -->
<div class="row mt12">
    <div class="col-sm-3">
        <a id="labor_add_button" href="javascript:" class="{{ $site_button_class }} labor-submit">Add Labor</a>
        <a id="labor_update_button" href="javascript:" class="btn btn-info hidden labor-submit">Update Labor</a>
        <a id="labor_cancel_button" href="javascript:" class="btn btn-light hidden ml6">Cancel</a>
    </div>
    <div class="col-sm-2 pt8 m0">
        <label class="control-label">Total Labor</label>
    </div>
    <div class="col-sm-2">
        <div class="admin-form-item-widget">
            <x-form-show
                class="show-check-contact"
                :params="[
                    'id' => 'labor_total_cost',
                ]">
            </x-form-show>
        </div>
    </div>
    <div class="col-sm-5 xs-hidden"></div>
</div>

@push('partials-scripts')
    <script>
        $(document).ready(function () {
            var proposalDetailId = Number('{{ $proposalDetail->id }}');

            var laborElForm = $('#labor_form');
            var laborElFormProposalDetailLaborId = $('#proposal_detail_labor_id');
            var laborElFormLaborId = $('#labor_id');
            var laborElFormNumber = $('#labor_quantity');
            var laborElFormDays = $('#labor_days');
            var laborElFormHours = $('#labor_hours');

            var laborElRowsHeader = $('#labor_rows_header');
            var laborElRowsContainer = $('#labor_rows_container');

            var laborSubmitButton = $('.labor-submit');
            var laborAddButton = $('#labor_add_button');
            var laborUpdateButton = $('#labor_update_button');
            var laborCancelButton = $('#labor_cancel_button');
            var laborElTotalCost = $('#labor_total_cost');
            var laborElEstimatorFormFieldTotalCost = $('#estimator_form_labor_total_cost');

            var laborAlert = $('#labor_alert');

            laborAlert.on('click', function(ev){
                ev.stopPropagation();
                ev.preventDefault();
                closeAlert(laborAlert);
            });

            laborUpdateTotalCost();

            laborSubmitButton.on('click', function(){
                laborElForm.validate({
                    rules: {
                        labor_id: {
                            required: true,
                            positive: true
                        },
                        number_of_labor: {
                            required: true,
                            positive: true
                        },
                        days: {
                            required: true,
                            float  : true
                        },
                        hours: {
                            required: true,
                            float  : true
                        }
                    },
                    messages: {
                        labor_id: {
                            required: "@lang('translation.field_required')",
                            positive: "@lang('translation.select_item')"
                        },
                        number_of_labor: {
                            required: "@lang('translation.field_required')",
                            positive: "@lang('translation.invalid_entry')"
                        },
                        days: {
                            required: "@lang('translation.field_required')",
                            positive: "@lang('translation.invalid_entry')"
                        },
                        hours: {
                            required: "@lang('translation.field_required')",
                            positive: "@lang('translation.invalid_entry')"
                        }
                    }
                });

                if (laborElForm.valid()) {
                    let formData = laborElForm.serializeObject();
                    let extraFormProperties = {
                        proposal_detail_id: proposalDetailId,
                    };

                    $.extend(formData, extraFormProperties);

                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: formData,
                        type: "POST",
                        url: "{{ route('ajax_labor_add_or_update') }}",
                        beforeSend: function (request){
                            showSpinner();
                        },
                        complete: function (){
                            hideSpinner();
                        },
                        success: function (response){
                            if (!response) {
                                showErrorAlert('Critical error has occurred.', laborAlert);
                            } else if (response.success) {
                                laborElRowsContainer.html(response.html);

                                laborUpdateTotalCost();
                                laborResetForm();

                                laborAddButton.removeClass('hidden');
                                laborUpdateButton.addClass('hidden');
                                laborCancelButton.addClass('hidden');

                                if (response.message) {
                                    showSuccessAlert(response.message, laborAlert);
                                }
                            } else {
                                showErrorAlert(response.message, laborAlert);
                            }
                        },
                        error: function (response){
                            @if (env('APP_ENV') === 'local')
                                showErrorAlert(response.responseJSON.message, laborAlert);
                            @else
                                showErrorAlert('Critical error has occurred.', laborAlert);
                            @endif
                        }
                    });
                }
            });

            laborElRowsContainer.on('click', '.labor-remove-button', function(){
                let el = $(this);
                let proposal_detail_labor_id = el.data('proposal_detail_labor_id');

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        proposal_detail_id: proposalDetailId,
                        proposal_detail_labor_id: proposal_detail_labor_id
                    },
                    type: "POST",
                    url: "{{ route('ajax_labor_remove') }}",
                    beforeSend: function (request){
                        showSpinner();
                    },
                    complete: function (){
                        hideSpinner();
                    },
                    success: function (response){
                        if (!response) {
                            showErrorAlert('Critical error has occurred.', laborAlert);
                        } else if (response.success) {
                            laborElRowsContainer.html(response.html);

                            let laborRows = $('.labor-row');

                            if (laborRows.length === 0) {
                                laborElRowsHeader.addClass('hidden');
                            }

                            laborUpdateTotalCost();

                            if (response.message) {
                                showSuccessAlert(response.message, laborAlert);
                            }
                        } else {
                            showErrorAlert(response.message, laborAlert);
                        }
                    },
                    error: function (response){
                        @if (env('APP_ENV') === 'local')
                            showErrorAlert(response.responseJSON.message, laborAlert);
                        @else
                            showErrorAlert('Critical error has occurred.', laborAlert);
                        @endif
                    }
                });
            });

            laborElRowsContainer.on('click', '.labor-edit-button', function(){
                let el = $(this);
                laborElFormProposalDetailLaborId.val(el.data('proposal_detail_labor_id'));
                laborElFormLaborId.val(el.data('labor_id'));
                laborElFormNumber.val(el.data('number'));
                laborElFormDays.val(el.data('days'));
                laborElFormHours.val(el.data('hours'));

                laborAddButton.addClass('hidden');
                laborUpdateButton.removeClass('hidden');
                laborCancelButton.removeClass('hidden');
            });

            laborCancelButton.click(function(){
                laborResetForm();

                laborAddButton.removeClass('hidden');
                laborUpdateButton.addClass('hidden');
                laborCancelButton.addClass('hidden');
            });

            function laborUpdateTotalCost()
            {
                let laborCosts = $('.labor-cost');
                let totalCost = 0;
                let currrencyTotalCost = 0;

                laborCosts.each(function(index){
                    let el = $(this);
                    totalCost += Number(el.data('cost'));
                });

                currrencyTotalCost = currencyFormat(totalCost);
                laborElTotalCost.html(currrencyTotalCost);
                laborElEstimatorFormFieldTotalCost.val(totalCost);

                headerElLaborCost.html(currrencyTotalCost);
                headerElLaborCost.data('labor_total_cost', totalCost);
            }

            function laborResetForm()
            {
                laborElForm.trigger('reset');
            }
        });
    </script>
@endpush


