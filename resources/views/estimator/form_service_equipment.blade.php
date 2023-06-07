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
    <div class="col-sm-1 tc">Remove</div>
</div>

<!-- equipment row -->
<div id="equipment_rows_container" class="mb20">
    @if (!empty($proposalDetail->equipment) && $proposalDetail->equipment->count() > 0)
        @foreach ($proposalDetail->equipment as $equipment)
            <div id="proposal_detail_equipment_id_{{ $equipment->id }}" class="row equipment-row border-bottom-dashed">
                <div class="col-sm-5 equipment-type">{{ $equipment->equipment->html_name_and_rate_type }}</div>
                <div class="col-sm-1 tc equipment-quantity">{{ $equipment->number_of_units }}</div>
                <div class="col-sm-1 tc equipment-days">{{ $equipment->days }}</div>
                <div class="col-sm-1 tc equipment-hours">{{ $equipment->hours }}</div>
                <div class="col-sm-1 tc equipment-rate">{{ $equipment->html_rate }}</div>
                <div class="col-sm-1 tc equipment-cost" data-cost="{{ $equipment->cost }}">{{ $equipment->html_cost }}</div>
                <div class="col-sm-1 tc equipment-rate"><span class="status {{ $equipment->cost < $equipment->equipment->min_cost ? 'danger' : '' }}">{{ $equipment->equipment->html_min_cost }}</span></div>
                <div class="col-sm-1 tc">
                    <button
                        class="btn p0 btn-danger tc equipment-remove-button"
                        type="button"
                        data-toggle="tooltip"
                        title="remove item"
                        data-proposal_detail_equipment_id="{{ $equipment->id }}"
                    >
                        <i class="far fa-trash-alt dib m0 plr5"></i>
                    </button>
                </div>
            </div>
        @endforeach
    @endif
</div>

<!-- equipment footer row -->
<div class="row mt12">
    <div class="col-sm-3">
        <a id="equipment_add_button" href="javascript:" class="{{ $site_button_class }}">Add Equipment</a>
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
            var equipmentElForm = $('#equipment_form');
            var equipmentElRowsHeader = $('#equipment_rows_header');
            var equipmentElRowsContainer = $('#equipment_rows_container');
            var equipmentEl = $('#equipment_id');
            var equipmentElDays = $('#equipment_days');
            var equipmentElHours = $('#equipment_hours');
            var equipmentElHoursRequired = $('#equipment_hours_required');
            var equipmentAddButton = $('#equipment_add_button');
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

            equipmentEl.change(function(){
                let el = $(this);
                let selected = el.find('option:selected');
                equipmentRateType = selected.data('rate_type');

                if (equipmentRateType === 'per day') {
                    equipmentElHoursRequired.addClass('hidden');
                    equipmentElHours.val(0);
                    equipmentElHours.prop('disabled', true);
                } else {
                    equipmentElHoursRequired.removeClass('hidden');
                    equipmentElHours.val('');
                    equipmentElHours.prop('disabled', false);
                }
            });

            equipmentAddButton.on('click', function(){
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
                                return equipmentElHours.val() === ''
                            },
                            float   : true
                        },
                        hours: {
                            required: function(){
                                return equipmentElDays.val() === ''
                            },
                            float   : true
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
                        url: "{{ route('ajax_equipment_add_new') }}",
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
                                let data = response.data;
                                let html  = '';
                                let equipmentRows = $('.equipment-row');

                                if (equipmentRows.length === 0) {
                                    equipmentElRowsHeader.removeClass('hidden');
                                }

                                html += '<div id="proposal_detail_equipment_id_'+ data. proposal_detail_equipment_id +'" class="row equipment-row border-bottom-dashed">';
                                html += '   <div class="col-sm-5 equipment-name">'+ data.formatted_name_and_rate_type +'</div>';
                                html += '   <div class="col-sm-1 tc equipment-number_of_equipment">'+ data.number_of_units +'</div>';
                                html += '   <div class="col-sm-1 tc equipment-days">'+ data.days +'</div>';
                                html += '   <div class="col-sm-1 tc equipment-hours">'+ data.hours +'</div>';
                                html += '   <div class="col-sm-1 tc equipment-rate">'+ data.formatted_rate +'</div>';
                                html += '   <div class="col-sm-1 tc equipment-cost" data-cost="'+ data.cost +'">'+ data.formatted_cost +'</div>';
                                html += '   <div class="col-sm-1 tc equipment-min_cost">'+ data.formatted_min_cost +'</div>';
                                html += '   <div class="col-sm-1 tc">';
                                html += '       <button class="btn p0 btn-danger tc equipment-remove-button" type="button" data-proposal_detail_equipment_id="'+ data. proposal_detail_equipment_id +'"><i class="far fa-trash-alt dib m0 plr5"></i></button>';
                                html += '   </div>';
                                html += '</div>';

                                equipmentElRowsContainer.append(html);
                                equipmentUpdateTotalCost();
                                equipmentResetForm();
                                equipmentRateType = null;

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
                            showErrorAlert(response.message, 'Critical error has occurred.');
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

                            $('#proposal_detail_equipment_id_' + response.data.proposal_detail_equipment_id).remove();

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
                        showErrorAlert(response.message, 'Critical error has occurred.');
                        @endif
                    }
                });
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
            }
        });
    </script>
@endpush
