<!-- vehicle sections -->

<!-- input fields header row -->
<div class="row">
    <div class="col-sm-3 mb2 fwb">
        <label class="control-label">Type<i class="field-required fa fa-asterisk" data-toggle="tooltip" title="@lang('translation.field_required')"></i></label>
    </div>
    <div class="col-sm-2 xs-hidden"></div>
    <div class="col-sm-2 tc">
        <label class="control-label">Cost<i class="field-required fa fa-asterisk" data-toggle="tooltip" title="@lang('translation.field_required')"></i></label>
    </div>
    <div class="col-sm-5 xs-hidden"></div>
</div>
<form method="POST" action="#" accept-charset="UTF-8" id="additional_cost_form" class="admin-form">
    @csrf
    <div class="row">
        <div class="col-sm-3 admin-form-item-widget">
            <x-form-select name="type"
                :items="$typesCB"
                selected="0"
                :params="[
                    'id' => 'additional_cost_type',
                    'iconClass' => 'none',
                ]"
            ></x-form-select>
        </div>
        <div class="col-sm-2 admin-form-item-widget xs-hidden"></div>
        <div class="col-sm-2 tc admin-form-item-widget">
            <x-form-text name="amount"
                class="check-contact tc"
                placeholder="enter value"
                id="additional_cost_amount"
                :params="[
                    'label' => 'none',
                    'iconClass' => 'none',
                    'value' => '',
                ]"
            ></x-form-text>
        </div>
        <div class="col-sm-5 admin-form-item-widget xs-hidden"></div>
    </div>
    <div class="row">
        <div class="col-sm-7 mb2 fwb">
            <label class="control-label">Description<i class="field-required fa fa-asterisk" data-toggle="tooltip" title="@lang('translation.field_required')"></i></label>
        </div>
        <div class="col-sm-5 tc admin-form-item-widget xs-hidden"></div>
    </div>
    <div class="row">
        <div class="col-sm-7 tc admin-form-item-widget">
            <x-form-textarea name="description"
                 class="check-contact tl h100 pl20"
                 id="additional_cost_description"
                 :params="[
                    'label' => 'none',
                    'iconClass' => 'none',
                    'value' => '',
                ]"
            ></x-form-textarea>
        </div>
        <div class="col-sm-5 tc admin-form-item-widget xs-hidden"></div>
    </div>
</form>

<!-- additional costs header row -->
<div id="additional_cost_rows_header" class="row fwb pb4 border-bottom-solid{{ !empty($proposalDetail->additionalCosts) && $proposalDetail->additionalCosts->count() > 0 ? '' : ' hidden' }}">
    <div class="col-sm-4">Type</div>
    <div class="col-sm-1 tc">Cost</div>
    <div class="col-sm-6 tc">Short Description</div>
    <div class="col-sm-1 tc">Remove</div>
</div>

<!-- additional costs rows -->
<div id="additional_cost_rows_container" class="mb20">
    @if (!empty($proposalDetail->additionalCosts) && $proposalDetail->additionalCosts->count() > 0)
        @foreach ($proposalDetail->additionalCosts as $additionalCost)
            <div id="proposal_detail_additional_cost_id_{{ $additionalCost->id }}" class="row additional-cost-row border-bottom-dashed">
                <div class="col-sm-4 additional-cost-type">{{ $additionalCost->type }}</div>
                <div class="col-sm-1 tc additional-cost-cost" data-cost="{{ $additionalCost->cost }}">{{ $additionalCost->html_cost }}</div>
                <div class="col-sm-6 tc additional-cost-short-description">{{ Str::limit($additionalCost->description, 100) }}</div>
                <div class="col-sm-1 tc">
                    <button
                        class="btn p0 btn-info tc additional-cost-show-description-button mr6"
                        type="button"
                        data-toggle="tooltip"
                        title="Show full description"
                        data-proposal_detail_additional_cost_id="{{ $additionalCost->id }}"
                        data-bs-toggle="modal"
                        data-bs-target="#modal_full_description_{{ $additionalCost->id }}"
                    >
                        <i class="far fa-eye dib m0 plr5"></i>
                        <div class="additional-cost-description hidden">{{ $additionalCost->description }}</div>
                    </button>
                    <button
                        class="btn p0 btn-danger tc additional-cost-remove-button"
                        type="button"
                        data-toggle="tooltip"
                        title="remove item"
                        data-proposal_detail_additional_cost_id="{{ $additionalCost->id }}"
                    >
                        <i class="far fa-trash-alt dib m0 plr5"></i>
                    </button>
                </div>

            </div>
        @endforeach
    @endif
</div>
<!-- additional costs footer row -->
<div class="row mt12">
    <div class="col-sm-3">
        <a id="additional_cost_add_button" href="javascript:" class="{{ $site_button_class }}">Add Cost</a>
    </div>
    <div class="col-sm-2 pt8 m0">
        <label class="control-label">Total Additional Costs</label>
    </div>
    <div class="col-sm-2">
        <div class="admin-form-item-widget">
            <x-form-show
                class="show-check-contact"
                :params="[
                    'id' => 'additional_cost_total_cost',
                ]">
            </x-form-show>
        </div>
    </div>
    <div class="col-sm-5 xs-hidden"></div>
</div>

@push('partials-scripts')
    <script>
        $(document).ready(function () {
            var additionalCostElForm = $('#additional_cost_form');
            var additionalCostElRowsHeader = $('#additional_cost_rows_header');
            var additionalCostElRowsContainer = $('#additional_cost_rows_container');

            var additionalCostAddButton = $('#additional_cost_add_button');
            var additionalCostElTotalCost = $('#additional_cost_total_cost');
            var additionalCostElEstimatorFormFieldTotalCost = $('#estimator_form_additional_cost_total_cost');

            var additionalCostsAlert = $('#additional_costs_alert');

            console.log(additionalCostsAlert);

            additionalCostsAlert.on('click', function(ev){
                ev.stopPropagation();
                ev.preventDefault();
                closeAlert(additionalCostsAlert);
            });

            additionalCostUpdateTotalCost();

            additionalCostAddButton.on('click', function(){
                additionalCostElForm.validate({
                    rules: {
                        type: {
                            required: true,
                        },
                        amount: {
                            required: true,
                            currency: true
                        },
                        description: {
                            required: true,
                            text    : true
                        }
                    },
                    messages: {
                        type: {
                            required: "@lang('translation.field_required')",
                        },
                        amount: {
                            required: "@lang('translation.field_required')",
                            currency: "@lang('translation.invalid_entry')"
                        },
                        description: {
                            required: "@lang('translation.field_required')",
                            text: "@lang('translation.invalid_entry')"
                        }
                    }
                });

                if (additionalCostElForm.valid()) {
                    let formData = additionalCostElForm.serializeObject();
                    let extraFormProperties = {proposal_detail_id: proposalDetailId};

                    $.extend(formData, extraFormProperties);

                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: formData,
                        type: "POST",
                        url: "{{ route('ajax_additional_cost_add_new') }}",
                        beforeSend: function (request){
                            showSpinner();
                        },
                        complete: function (){
                            hideSpinner();
                        },
                        success: function (response){
                            if (!response) {
                                showErrorAlert('Critical error has occurred.', additionalCostsAlert);
                            } else if (response.success) {
                                let data = response.data;
                                let html  = '';

                                let additionalCostRows = $('.additional-cost-row');

                                if (additionalCostRows.length === 0) {
                                    additionalCostElRowsHeader.removeClass('hidden');
                                }

                                html += '<div id="proposal_detail_additional_cost_id_'+ data.proposal_detail_additional_cost_id +'" class="row additional-cost-row border-bottom-dashed">';
                                html += '   <div class="col-sm-4 additional-cost-type">'+ data.type +'</div>';
                                html += '   <div class="col-sm-1 tc additional-cost-cost" data-cost="'+ data.cost +'">'+ data.formatted_cost +'</div>';
                                html += '   <div class="col-sm-6 tc additional-cost-short_description">'+ data.short_description +'</div>';
                                html += '   <div class="col-sm-1 tc">';
                                html += '       <button class="btn p0 btn-danger tc additional-cost-remove-button" type="button" data-proposal_detail_additional_cost_id="'+ data. proposal_detail_additional_cost_id +'"><i class="far fa-trash-alt dib m0 plr5"></i><div class="additional-cost-description hidden">'+ data.short_description +'</div></button>';
                                html += '           <i class="far fa-trash-alt dib m0 plr5"></i>';
                                html += '           <div class="additional-cost-description hidden">'+ data.description +'</div>';
                                html += '       </button>';
                                html += '   </div>';
                                html += '</div>';

                                additionalCostElRowsContainer.append(html);

                                additionalCostUpdateTotalCost();

                                additionalCostResetForm();

                                if (response.message) {
                                    showSuccessAlert(response.message, additionalCostsAlert);
                                }
                            } else {
                                showErrorAlert(response.message, additionalCostsAlert);
                            }
                        },
                        error: function (response){
                            @if (env('APP_ENV') === 'local')
                                showErrorAlert(response.responseJSON.message, additionalCostsAlert);
                            @else
                                showErrorAlert('Critical error has occurred.', additionalCostsAlert);
                            @endif
                        }
                    });
                }
            });

            additionalCostElRowsContainer.on('click', '.additional-cost-show-description-button', function(){
                let el = $(this);
                let fullDescription = el.find('.additional-cost-description').html();

                showInfoAlert(fullDescription, additionalCostsAlert);
            });

            additionalCostElRowsContainer.on('click', '.additional-cost-remove-button', function(){
                let el = $(this);
                let proposalDetailAdditionalCostId = el.data('proposal_detail_additional_cost_id');

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        proposal_detail_additional_cost_id: proposalDetailAdditionalCostId
                    },
                    type: "POST",
                    url: "{{ route('ajax_additional_cost_remove') }}",
                    beforeSend: function (request){
                        showSpinner();
                    },
                    complete: function (){
                        hideSpinner();
                    },
                    success: function (response){
                        if (!response) {
                            showErrorAlert('Critical error has occurred.', additionalCostsAlert);
                        } else if (response.success) {

                            $('#proposal_detail_additional_cost_id_' + response.data.proposal_detail_additional_cost_id).remove();

                            let additionalCostRows = $('.additional-cost-row');

                            if (additionalCostRows.length === 0) {
                                additionalCostElRowsHeader.addClass('hidden');
                            }

                            additionalCostUpdateTotalCost();

                            if (response.message) {
                                showSuccessAlert(response.message, additionalCostsAlert);
                            }
                        } else {
                            showErrorAlert(response.message, additionalCostsAlert);
                        }
                    },
                    error: function (response){
                        @if (env('APP_ENV') === 'local')
                            showErrorAlert(response.responseJSON.message, additionalCostsAlert);
                        @else
                            showErrorAlert('Critical error has occurred.', additionalCostsAlert);
                        @endif
                    }
                });
            });

            function additionalCostUpdateTotalCost()
            {
                let additionalCostCost = $('.additional-cost-cost');
                let totalCost = 0;
                let currrencyTotalCost = 0;

                additionalCostCost.each(function(index){
                    let el = $(this);
                    totalCost += Number(el.data('cost'));
                });

                currrencyTotalCost = currencyFormat(totalCost);

                additionalCostElTotalCost.html(currrencyTotalCost);
                additionalCostElEstimatorFormFieldTotalCost.val(totalCost);

                headerElAdditionalCost.html(currrencyTotalCost);
                headerElAdditionalCost.data('additional_cost_total_cost', totalCost);
            }

            function additionalCostResetForm()
            {
                additionalCostElForm.trigger('reset');
            }
        });
    </script>
@endpush


