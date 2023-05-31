<!-- vehicle sections -->

<!-- input fields header row -->
<div class="row">
    <div class="col-sm-6 mb2 fwb">
        <label class="control-label">Select Vehicle<i class="field-required fa fa-asterisk" data-toggle="tooltip" title="@lang('translation.field_required')"></i></label>
    </div>
    <div class="col-sm-2 tc">
        <label class="control-label">Number of Vehicles<i class="field-required fa fa-asterisk" data-toggle="tooltip" title="@lang('translation.field_required')"></i></label>
    </div>
    <div class="col-sm-2 tc">
        <label class="control-label">How Many Days<i class="field-required fa fa-asterisk" data-toggle="tooltip" title="@lang('translation.field_required')"></i></label>
    </div>
    <div class="col-sm-2 tc">
        <label class="control-label">Hours per day<i class="field-required fa fa-asterisk" data-toggle="tooltip" title="@lang('translation.field_required')"></i></label>
    </div>
</div>

<!-- input fields values row -->
<form method="POST" action="#" accept-charset="UTF-8" id="vehicle_form" class="admin-form">
    @csrf
    <div class="row">
        <div class="col-sm-3 admin-form-item-widget">
            <x-form-select name="vehicle_id"
                :items="$vehiclesCB"
                selected=""
                :params="[
                    'id' => 'vehicle_id',
                    'iconClass' => 'none',
                ]"
            ></x-form-select>
        </div>
        <div class="col-sm-3 xs-hidden"></div>
        <div class="col-sm-2 tc admin-form-item-widget">
            <x-form-text name="number_of_vehicles"
                 class="check-contact tc"
                 placeholder="enter value"
                 id="vehicle_quantity"
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
                 id="vehicle_days"
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
                 id="vehicle_hours"
                 :params="[
                    'label' => 'none',
                    'iconClass' => 'none',
                    'value' => '',
                ]"
            ></x-form-text>
        </div>
    </div>
</form>

<!-- vehicle header row -->
<div id="vehicle_rows_header" class="row fwb pb4 border-bottom-solid{{ !empty($proposalDetail->vehicles) && $proposalDetail->vehicles->count() > 0 ? '' : ' hidden' }}">
    <div class="col-sm-6">Vehicle Type</div>
    <div class="col-sm-1 tc">Quantity</div>
    <div class="col-sm-1 tc">Days</div>
    <div class="col-sm-1 tc">Hours</div>
    <div class="col-sm-1 tc">Rate</div>
    <div class="col-sm-1 tc">Total</div>
    <div class="col-sm-1 tc">Actions</div>
</div>

<!-- vehicles row -->
<div id="vehicle_rows_container" class="mb20">
    @if (!empty($proposalDetail->vehicles) && $proposalDetail->vehicles->count() > 0)
        @foreach ($proposalDetail->vehicles as $vehicle)
            <div id="proposal_detail_vehicle_id_{{ $vehicle->id }}" class="row vehicle-row border-bottom-dashed">
                <div class="col-sm-6 vehicle-type">{{ $vehicle->vehicle_name }}</div>
                <div class="col-sm-1 tc vehicle-quantity">{{ $vehicle->number_of_vehicles }}</div>
                <div class="col-sm-1 tc vehicle-days">{{ $vehicle->days }}</div>
                <div class="col-sm-1 tc vehicle-hours">{{ $vehicle->hours }}</div>
                <div class="col-sm-1 tc vehicle-rate">{{ $vehicle->html_rate_per_hour }}</div>
                <div class="col-sm-1 tc vehicle-cost" data-cost="{{ $vehicle->cost }}">{{ $vehicle->html_cost }}</div>
                <div class="col-sm-1 tc">
                    <button
                        class="btn p0 btn-danger tc vehicle-remove-button"
                        type="button"
                        data-toggle="tooltip"
                        title="remove item"
                        data-proposal_detail_vehicle_id="{{ $vehicle->id }}"
                    >
                        <i class="far fa-trash-alt dib m0 plr5"></i>
                    </button>
                </div>
            </div>
        @endforeach
    @endif
</div>

<!-- vehicle footer row -->
<div class="row mt12">
    <div class="col-sm-3">
        <a id="vehicle_add_button" href="javascript:" class="{{ $site_button_class }}">Add Vehicle</a>
    </div>
    <div class="col-sm-2 pt8 m0">
        <label class="control-label">Total Vehicles</label>
    </div>
    <div class="col-sm-2">
        <div class="admin-form-item-widget">
            <x-form-show
                class="show-check-contact"
                :params="[
                    'id' => 'vehicle_total_cost',
                ]">
            </x-form-show>
        </div>
    </div>
    <div class="col-sm-5 xs-hidden"></div>
</div>

@push('partials-scripts')
    <script>
        $(document).ready(function () {
            var vehicleElForm = $('#vehicle_form');
            var vehicleElRowsHeader = $('#vehicle_rows_header');
            var vehicleElRowsContainer = $('#vehicle_rows_container');

            var vehicleAddButton = $('#vehicle_add_button');
            var vehicleElTotalCost = $('#vehicle_total_cost');
            var vehicleElEstimatorFormFieldTotalCost = $('#estimator_form_vehicle_total_cost');

            var vehicleAlert = $('#vehicle_alert');

            vehicleAlert.on('click', function(ev){
                ev.stopPropagation();
                ev.preventDefault();
                closeAlert(vehicleAlert);
            });

            vehicleUpdateTotalCost();

            vehicleAddButton.on('click', function(){
                vehicleElForm.validate({
                    rules: {
                        vehicle_id: {
                            required: true,
                            positive: true
                        },
                        number_of_vehicles: {
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
                        vehicle_id: {
                            required: "@lang('translation.field_required')",
                            positive: "@lang('translation.select_item')"
                        },
                        number_of_vehicles: {
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

                if (vehicleElForm.valid()) {
                    let formData = vehicleElForm.serializeObject();
                    let extraFormProperties = {proposal_detail_id: proposalDetailId};

                    $.extend(formData, extraFormProperties);

                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: formData,
                        type: "POST",
                        url: "{{ route('ajax_vehicle_add_new') }}",
                        beforeSend: function (request){
                            showSpinner();
                        },
                        complete: function (){
                            hideSpinner();
                        },
                        success: function (response){
                            if (!response) {
                                showErrorAlert('Critical error has occurred.', vehicleAlert);
                            } else if (response.success) {
                                let data = response.data;
                                let html  = '';

                                let vehicleRows = $('.vehicle-row');

                                if (vehicleRows.length === 0) {
                                    vehicleElRowsHeader.removeClass('hidden');
                                }

                                html += '<div id="proposal_detail_vehicle_id_'+ data. proposal_detail_vehicle_id +'" class="row vehicle-row border-bottom-dashed">';
                                html += '   <div class="col-sm-6 vehicle-name">'+ data.vehicle_name +'</div>';
                                html += '   <div class="col-sm-1 tc vehicle-number_of_vehicles">'+ data.number_of_vehicles +'</div>';
                                html += '   <div class="col-sm-1 tc vehicle-days">'+ data.days +'</div>';
                                html += '   <div class="col-sm-1 tc vehicle-hours">'+ data.hours +'</div>';
                                html += '   <div class="col-sm-1 tc vehicle-rate">'+ data.rate_per_hour +'</div>';
                                html += '   <div class="col-sm-1 tc vehicle-cost" data-cost="'+ data.cost +'">'+ data.formatted_cost +'</div>';
                                html += '   <div class="col-sm-1 tc">';
                                html += '       <button class="btn p0 btn-danger tc vehicle-remove-button" type="button" data-toggle="tooltip" title="remove item" data-proposal_detail_vehicle_id="'+ data. proposal_detail_vehicle_id +'"><i class="far fa-trash-alt dib m0 plr5"></i></button>';
                                html += '   </div>';
                                html += '</div>';

                                vehicleElRowsContainer.append(html);

                                vehicleUpdateTotalCost();

                                vehicleResetForm();

                                if (response.message) {
                                    showSuccessAlert(response.message, vehicleAlert);
                                }
                            } else {
                                showErrorAlert(response.message, vehicleAlert);
                            }
                        },
                        error: function (response){
                            @if (env('APP_ENV') === 'local')
                            showErrorAlert(response.responseJSON.message, vehicleAlert);
                            @else
                            showErrorAlert(response.message, 'Critical error has occurred.');
                            @endif
                        }
                    });
                }
            });

            vehicleElRowsContainer.on('click', '.vehicle-remove-button', function(){
                let el = $(this);
                let proposal_detail_vehicle_id = el.data('proposal_detail_vehicle_id');

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        proposal_detail_vehicle_id: proposal_detail_vehicle_id
                    },
                    type: "POST",
                    url: "{{ route('ajax_vehicle_remove') }}",
                    beforeSend: function (request){
                        showSpinner();
                    },
                    complete: function (){
                        hideSpinner();
                    },
                    success: function (response){
                        if (!response) {
                            showErrorAlert('Critical error has occurred.', vehicleAlert);
                        } else if (response.success) {

                            $('#proposal_detail_vehicle_id_' + response.data.proposal_detail_vehicle_id).remove();

                            let vehicleRows = $('.vehicle-row');

                            if (vehicleRows.length === 0) {
                                vehicleElRowsHeader.addClass('hidden');
                            }

                            vehicleUpdateTotalCost();

                            if (response.message) {
                                showSuccessAlert(response.message, vehicleAlert);
                            }
                        } else {
                            showErrorAlert(response.message, vehicleAlert);
                        }
                    },
                    error: function (response){
                        @if (env('APP_ENV') === 'local')
                        showErrorAlert(response.responseJSON.message, vehicleAlert);
                        @else
                        showErrorAlert(response.message, 'Critical error has occurred.');
                        @endif
                    }
                });
            });

            function vehicleUpdateTotalCost()
            {
                let vehicleCosts = $('.vehicle-cost');
                let totalCost = 0;
                let currrencyTotalCost = 0;

                vehicleCosts.each(function(index){
                    let el = $(this);
                    totalCost += Number(el.data('cost'));
                });

                currrencyTotalCost = currencyFormat(totalCost);

                vehicleElTotalCost.html(currrencyTotalCost);
                vehicleElEstimatorFormFieldTotalCost.val(totalCost);

                headerElVehiclesCost.html(currrencyTotalCost);
                headerElVehiclesCost.data('vehicle_total_cost', totalCost);
            }

            function vehicleResetForm()
            {
                vehicleElForm.trigger('reset');
            }
        });
    </script>
@endpush


