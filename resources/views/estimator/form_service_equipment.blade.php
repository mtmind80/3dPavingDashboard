<!-- equipment sections -->

<!-- input fields header row -->
<div class="row">
    <div class="col-sm-6 mb2 fwb">
        <label class="control-label">Select Equipment<i class="field-required fa fa-asterisk" data-toggle="tooltip" title="@lang('translation.field_required')"></i></label>
    </div>
    <div class="col-sm-2 tc">
        <label class="control-label">Number<i class="field-required fa fa-asterisk" data-toggle="tooltip" title="@lang('translation.field_required')"></i></label>
    </div>
    <div class="col-sm-2 tc">
        <label class="control-label">Days<i class="field-required fa fa-asterisk" data-toggle="tooltip" title="@lang('translation.field_required')"></i></label>
    </div>
    <div class="col-sm-2 tc">
        <label class="control-label">Hours<i id="equipment_hours_required" class="field-required fa fa-asterisk" data-toggle="tooltip" title="@lang('translation.field_required')"></i></label>
    </div>
</div>

<!-- input fields values row -->
<form action="#" accept-charset="UTF-8" id="equipment_form" class="admin-form">
    @csrf
    <input type="hidden" name="proposal_detail_equipment_id" id="proposal_detail_equipment_id">
    <div class="row">
        <div class="col-sm-3 admin-form-item-widget">
            <label class="field select">
                <select name="equipment_id" id="equipment_id" class="form-control grayed">
                    <option value="0" selected="" disabled="">Select equipment</option>
                    @foreach ($equipmentCollection as $equipment)
                        <option value="{{ $equipment->id }}" data-rate_type="{{ $equipment->rate_type }}">{{ $equipment->html_name_and_rate_type }}</option>
                    @endforeach
                </select>
                <i class="arrow double"></i>
            </label>
        </div>
        <div class="col-sm-3 xs-hidden"></div>

        <div class="col-sm-2 tc admin-form-item-widget">
            <x-form-text name="number_of_units"
                 class="check-contact tc"
                 placeholder="enter value"
                 id="equipment_quantity"
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
                 id="equipment_days"
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
                 id="equipment_hours"
                 :params="[
                    'label' => 'none',
                    'iconClass' => 'none',
                    'value' => '',
                ]"
            ></x-form-text>
        </div>
    </div>
</form>

<!-- equipment header row -->
<div id="equipment_rows_header" class="row fwb pb4 border-bottom-solid{{ !empty($proposalDetail->equipment) && $proposalDetail->equipment->count() > 0 ? '' : ' hidden' }}">
    <div class="col-sm-5">Equipment</div>
    <div class="col-sm-1 tc">Quantity</div>
    <div class="col-sm-1 tc">Days</div>
    <div class="col-sm-1 tc">Hours</div>
    <div class="col-sm-1 tc">Rate</div>
    <div class="col-sm-1 tc">Total</div>
    <div class="col-sm-1 tc">Min Cost</div>
    <div class="col-sm-1 tc">Edit / Remove</div>
</div>

<!-- equipment row -->
<div id="equipment_rows_container" class="mb20">
    @if (!empty($proposalDetail->equipment) && $proposalDetail->equipment->count() > 0)
        @include('estimator._form_service_equipment', ['equipments' => $proposalDetail->equipment])
    @endif
</div>

<!-- equipment footer row -->
<div class="row mt12">
    <div class="col-sm-3">
        <a id="equipment_add_button" href="javascript:" class="{{ $site_button_class }} equipment-submit">Add Equipment</a>
        <a id="equipment_update_button" href="javascript:" class="btn btn-info hidden equipment-submit">Update Equipment</a>
        <a id="equipment_cancel_button" href="javascript:" class="btn btn-light hidden ml6">Cancel</a>
    </div>
    <div class="col-sm-2 pt8 m0">
        <label class="control-label">Total Equipment</label>
    </div>
    <div class="col-sm-2">
        <div class="admin-form-item-widget">
            <x-form-show
                class="show-check-contact"
                :params="[
                    'id' => 'equipment_total_cost',
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

            var equipmentElForm = $('#equipment_form');
            var equipmentElFormProposalDetailEquipmentId = $('#proposal_detail_equipment_id');
            var equipmentElFormEquipmentId = $('#equipment_id');
            var equipmentElFormNumberOfUnits = $('#equipment_quantity');
            var equipmentElFormDays = $('#equipment_days');
            var equipmentElFormHours = $('#equipment_hours');
            var equipmentElFormHoursRequired = $('#equipment_hours_required');

            var equipmentElRowsHeader = $('#equipment_rows_header');
            var equipmentElRowsContainer = $('#equipment_rows_container');

            var equipmentSubmitButton = $('.equipment-submit');
            var equipmentAddButton = $('#equipment_add_button');
            var equipmentUpdateButton = $('#equipment_update_button');
            var equipmentCancelButton = $('#equipment_cancel_button');
            var equipmentElTotalCost = $('#equipment_total_cost');
            var equipmentElEstimatorFormFieldTotalCost = $('#estimator_form_equipment_total_cost');
            var equipmentRateType;

            var equipmentAlert = $('#equipment_alert');

            equipmentAlert.on('click', function(ev){
                ev.stopPropagation();
                ev.preventDefault();
                closeAlert(equipmentAlert);
            });

            equipmentUpdateTotalCost();

            equipmentElFormEquipmentId.change(function(){
                let el = $(this);

                equipmentHasChanged(el);
            });

            function equipmentHasChanged(el)
            {
                let selected = el.find('option:selected');
                equipmentRateType = selected.data('rate_type');

                if (equipmentRateType === 'per day') {
                    equipmentElFormHoursRequired.addClass('hidden');
                    equipmentElFormHours.val(0);
                    equipmentElFormHours.prop('disabled', true);
                } else {
                    equipmentElFormHoursRequired.removeClass('hidden');
                    equipmentElFormHours.val('');
                    equipmentElFormHours.prop('disabled', false);
                }
            }

            equipmentSubmitButton.on('click', function(){
                equipmentElForm.validate({
                    rules: {
                        equipment_id: {
                            required: true,
                            positive: true
                        },
                        number_of_units: {
                            required: true,
                            positive: true
                        },
                        days: {
                            required: function(){
                                return equipmentElFormHours.val() === ''
                            },
                            float: true
                        },
                        hours: {
                            required: function(){
                                return equipmentElFormDays.val() === ''
                            },
                            float: true
                        }
                    },
                    messages: {
                        equipment_id: {
                            required: "@lang('translation.field_required')",
                            positive: "@lang('translation.select_item')"
                        },
                        number_of_units: {
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

                if (equipmentElForm.valid()) {
                    let formData = equipmentElForm.serializeObject();
                    let extraFormProperties = {
                        proposal_detail_id: proposalDetailId,
                        equipment_rate_type: equipmentRateType
                    };
                    $.extend(formData, extraFormProperties);

                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: formData,
                        type: "POST",
                        url: "{{ route('ajax_equipment_add_or_update') }}",
                        beforeSend: function (request){
                            showSpinner();
                        },
                        complete: function (){
                            hideSpinner();
                        },
                        success: function (response){
                            if (!response) {
                                showErrorAlert('Critical error has occurred.', equipmentAlert);
                            } else if (response.success) {
                                equipmentElRowsContainer.html(response.html);

                                equipmentUpdateTotalCost();
                                equipmentResetForm();
                                equipmentRateType = null;

                                equipmentAddButton.removeClass('hidden');
                                equipmentUpdateButton.addClass('hidden');
                                equipmentCancelButton.addClass('hidden');

                                if (response.message) {
                                    showSuccessAlert(response.message, equipmentAlert);
                                }

                            } else {
                                showErrorAlert(response.message, equipmentAlert);
                            }
                        },
                        error: function (response){
                            @if (env('APP_ENV') === 'local')
                                showErrorAlert(response.responseJSON.message, equipmentAlert);
                            @else
                                showErrorAlert('Critical error has occurred.', equipmentAlert);
                            @endif
                        }
                    });
                }
            });

            equipmentElRowsContainer.on('click', '.equipment-remove-button', function(){
                let el = $(this);
                let proposal_detail_equipment_id = el.data('proposal_detail_equipment_id');

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        proposal_detail_id: proposalDetailId,
                        proposal_detail_equipment_id: proposal_detail_equipment_id
                    },
                    type: "POST",
                    url: "{{ route('ajax_equipment_remove') }}",
                    beforeSend: function (request){
                        showSpinner();
                    },
                    complete: function (){
                        hideSpinner();
                    },
                    success: function (response){
                        if (!response) {
                            showErrorAlert('Critical error has occurred.', equipmentAlert);
                        } else if (response.success) {
                            equipmentElRowsContainer.html(response.html);

                            let equipmentRows = $('.equipment-row');

                            if (equipmentRows.length === 0) {
                                equipmentElRowsHeader.addClass('hidden');
                            }

                            equipmentUpdateTotalCost();

                            if (response.message) {
                                showSuccessAlert(response.message, equipmentAlert);
                            }
                        } else {
                            showErrorAlert(response.message, equipmentAlert);
                        }
                    },
                    error: function (response){
                        @if (env('APP_ENV') === 'local')
                            showErrorAlert(response.responseJSON.message, equipmentAlert);
                        @else
                            showErrorAlert('Critical error has occurred.', equipmentAlert);
                        @endif
                    }
                });
            });

            equipmentElRowsContainer.on('click', '.equipment-edit-button', function(){
                let el = $(this);
                equipmentElFormProposalDetailEquipmentId.val(el.data('proposal_detail_equipment_id'));
                equipmentElFormEquipmentId.val(el.data('equipment_id'));

                equipmentHasChanged(equipmentElFormEquipmentId);

                equipmentElFormNumberOfUnits.val(el.data('number_of_units'));
                equipmentElFormDays.val(el.data('days'));
                equipmentElFormHours.val(el.data('hours'));

                equipmentAddButton.addClass('hidden');
                equipmentUpdateButton.removeClass('hidden');
                equipmentCancelButton.removeClass('hidden');
            });

            equipmentCancelButton.click(function(){
                equipmentResetForm();

                equipmentAddButton.removeClass('hidden');
                equipmentUpdateButton.addClass('hidden');
                equipmentCancelButton.addClass('hidden');
            });

            function equipmentUpdateTotalCost()
            {
                let equipmentCosts = $('#equipment_rows_container .equipment-cost');
                let totalCost = 0;
                let currrencyTotalCost = 0;

                equipmentCosts.each(function(index){
                    let el = $(this);
                    totalCost += Number(el.data('cost'));
                });

                currrencyTotalCost = currencyFormat(totalCost);
                equipmentElTotalCost.html(currrencyTotalCost);
                equipmentElEstimatorFormFieldTotalCost.val(totalCost);

                headerElEquipmentCost.html(currrencyTotalCost);
                headerElEquipmentCost.data('equipment_total_cost', totalCost);
            }

            function equipmentResetForm()
            {
                equipmentElForm.trigger('reset');
                equipmentRateType = null;
            }
        });
    </script>
@endpush
