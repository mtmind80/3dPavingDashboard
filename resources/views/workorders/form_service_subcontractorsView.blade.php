<!-- subcintractor sections -->

<!-- subcontractor header row -->
<div id="subcontractor_rows_header" class="row fwb pb4 border-bottom-solid{{ !empty($proposalDetail->subcontractors) && $proposalDetail->subcontractors->count() > 0 ? '' : ' hidden' }}">
    <div class="col-sm-4">Subcontractor</div>
    <div class="col-sm-1 tc">Overhead</div>
    <div class="col-sm-1 tc">Quoted Cost</div>
    <div class="col-sm-1 tc">Total Cost</div>
    <div class="col-sm-3 tc">Attached Bid</div>
    <div class="col-sm-1 tc">Accepted</div>
</div>

<!-- subcontractor row -->
<div id="subcontractor_rows_container" class="mb20">
    @include('estimator._subcontractors_gridView', ['partialSubcontractors' => $proposalDetail->subcontractors])
</div>

<!-- subcontractor footer row -->
<div class="row mt12">
    <div class="col-sm-3">

     </div>
    <div class="col-sm-2 pt8 m0">
        <label class="control-label">Total Subcontractors</label>
    </div>
    <div class="col-sm-2">
        <div class="admin-form-item-widget">
            <x-form-show
                class="show-check-contact"
                :params="[
                    'id' => 'subcontractor_total_cost',
                ]">
            </x-form-show>
        </div>
    </div>
    <div class="col-sm-5 xs-hidden"></div>
</div>

@push('partials-scripts')
    <script>
        $(document).ready(function () {
            var subcontractorElForm = $('#subcontractor_form');
            var subcontractorElRowsHeader = $('#subcontractor_rows_header');
            var subcontractorElRowsContainer = $('#subcontractor_rows_container');
            var subcontractorEl = $('#subcontractor_id');
            var subcontractorElOverhead = $('#subcontractor_overhead');
            var subcontractorAddButton = $('#subcontractor_add_button');
            var subcontractorElTotalCost = $('#subcontractor_total_cost');
            var subcontractorElEstimatorFormFieldTotalCost = $('#estimator_form_subcontractor_total_cost');

            var subcontractorsAlert = $('#subcontractors_alert');

            subcontractorsAlert.on('click', function(ev){
                ev.stopPropagation();
                ev.preventDefault();
                closeAlert(subcontractorsAlert);
            });

            subcontractorUpdateTotalCost();

            subcontractorEl.change(function(){
                let el = $(this);
                let selected = el.find('option:selected');
                let subcontractorOverhead = selected.data('overhead');

                subcontractorElOverhead.val(subcontractorOverhead);
            });

            subcontractorAddButton.on('click', function(){
                subcontractorElForm.validate({
                    rules: {
                        subcontractor_id: {
                            required: true,
                            positive: true
                        },
                        overhead: {
                            required: true,
                            float: true,
                            rangelength: [0, 100]
                        },
                        cost: {
                            required: true,
                            float  : true
                        },
                        description: {
                            required: true,
                            text: true
                        }
                    },
                    messages: {
                        subcontractor_id: {
                            required: "@lang('translation.field_required')",
                            float: "@lang('translation.select_item')"
                        },
                        overhead: {
                            required: "@lang('translation.field_required')",
                            float: "@lang('translation.invalid_entry')"
                        },
                        cost: {
                            required: "@lang('translation.field_required')",
                            float: "@lang('translation.invalid_entry')"
                        },
                        description: {
                            required: "@lang('translation.field_required')",
                            text: "@lang('translation.invalid_entry')"
                        }
                    }
                });

                if (subcontractorElForm.valid()) {
                    let formData = new FormData(subcontractorElForm[0]);
                    let extraFormProperties = {
                        proposal_detail_id: proposalDetailId
                    };

                    $.extend(formData, extraFormProperties);

                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: formData,
                        contentType: false,
                        processData: false,
                        type: "POST",
                        url: "{{ route('ajax_subcontractor_add_new') }}",
                        beforeSend: function (request){
                            showSpinner();
                        },
                        complete: function (){
                            hideSpinner();
                        },
                        success: function (response){
                            if (!response) {
                                showErrorAlert('Critical error has occurred.', subcontractorsAlert);
                            } else if (response.success) {
                                let data = response.data;

                                let subcontractorRows = $('.subcontractor-row');

                                if (subcontractorRows.length === 0) {
                                    subcontractorElRowsHeader.removeClass('hidden');
                                }

                                subcontractorElRowsContainer.html(data.grid);

                                subcontractorUpdateTotalCost();

                                subcontractorResetForm(data.description);

                                if (response.message) {
                                    showSuccessAlert(response.message, subcontractorsAlert);
                                }
                            } else {
                                showErrorAlert(response.message, subcontractorsAlert);
                            }
                        },
                        error: function (response, status, error){
                            @if (env('APP_ENV') === 'local')
                                showErrorAlert(response.responseJSON.message, subcontractorsAlert);
                            @else
                                showErrorAlert('Critical error has occurred.', subcontractorsAlert);
                            @endif
                        }
                    });
                }
            });

            subcontractorElRowsContainer.on('click', '.subcontractor-remove-button', function(){
                let el = $(this);
                let proposal_detail_subcontractor_id = el.data('proposal_detail_subcontractor_id');

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        proposal_detail_subcontractor_id: proposal_detail_subcontractor_id,
                        description: $('#subcontractor_description').val()
                    },
                    type: "POST",
                    url: "{{ route('ajax_subcontractor_remove') }}",
                    beforeSend: function (request){
                        showSpinner();
                    },
                    complete: function (){
                        hideSpinner();
                    },
                    success: function (response){
                        if (!response) {
                            showErrorAlert('Critical error has occurred.', subcontractorsAlert);
                        } else if (response.success) {
                            let data = response.data;

                            let subcontractorRows = $('.subcontractor-row');

                            if (subcontractorRows.length === 0) {
                                subcontractorElRowsHeader.addClass('hidden');
                            }

                            subcontractorElRowsContainer.html(data.grid);

                            subcontractorUpdateTotalCost();

                            if (response.message) {
                                showSuccessAlert(response.message, subcontractorsAlert);
                            }
                        } else {
                            showErrorAlert(response.message, subcontractorsAlert);
                        }
                    },
                    error: function (response){
                        @if (env('APP_ENV') === 'local')
                            showErrorAlert(response.responseJSON.message, subcontractorsAlert);
                        @else
                            showErrorAlert('Critical error has occurred.', subcontractorsAlert);
                        @endif
                    }
                });
            });

            function subcontractorUpdateTotalCost()
            {
                let subcontractorElRowAccepted = $('.subcontractor-row.subcontractor-accepted');
                let subcontractorElTotalCost = $('#subcontractor_total_cost');
                let totalElCost;
                let totalCost = 0;
                let currrencyTotalCost = '$0.00';

                if (subcontractorElRowAccepted.length > 0) {
                    subcontractorElRowAccepted.each(function(index){
                        let el = $(this);
                        totalCost += Number(el.data('total_cost'));
                    });
                    currrencyTotalCost = currencyFormat(totalCost);
                }

                subcontractorElTotalCost.html(currrencyTotalCost);
                subcontractorElEstimatorFormFieldTotalCost.val(totalCost);

                subcontractorElTotalCost.html(currrencyTotalCost);

                headerElSubcontractorCost.html(currrencyTotalCost);
                headerElSubcontractorCost.data('subcontractor_total_cost', totalCost);
            }

            function subcontractorResetForm(description)
            {
                subcontractorElForm.trigger('reset');
                subcontractorElForm.find('textarea[name="description"]').html(description);
                subcontractorElForm.find('.remove-file-link').click();
            }
        });
    </script>
@endpush


