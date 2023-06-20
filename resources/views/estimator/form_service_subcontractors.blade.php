<!-- subcintractor sections -->

<!-- input fields values row -->
<form method="POST" action="#" accept-charset="UTF-8" id="subcontractor_form" class="admin-form mt10" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="proposal_detail_id" value="{{ $proposalDetail->id }}">
    <div class="row">
        <!-- left column -->
        <div class="col-sm-7">
            <!-- first left row -->
            <div class="row">
                <div class="col-sm-6 mb2 fwb">
                    <label class="control-label">Select a Sub Contractor<i class="field-required fa fa-asterisk" data-toggle="tooltip" title="@lang('translation.field_required')"></i></label>
                </div>
                <div class="col-sm-3 tc">
                    <label class="control-label">Overhead<i class="field-required fa fa-asterisk" data-toggle="tooltip" title="@lang('translation.field_required')"></i></label>
                </div>
                <div class="col-sm-3 tc">
                    <label class="control-label">Quoted Cost<i class="field-required fa fa-asterisk" data-toggle="tooltip" title="@lang('translation.field_required')"></i></label>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6 admin-form-item-widget">
                    <label class="field select">
                        <select name="subcontractor_id" id="subcontractor_id" class="form-control grayed">
                            <option value="0" selected="" disabled="">Select contractor</option>
                            @foreach ($contractors as $contractor)
                                <option value="{{ $contractor->id }}" data-overhead="{{ $contractor->overhead }}">{{ $contractor->name_and_overhead_percent }}</option>
                            @endforeach
                        </select>
                        <i class="arrow double"></i>
                    </label>
                </div>
                <div class="col-sm-3 tc admin-form-item-widget">
                    <x-form-text name="overhead"
                         class="check-contact tc"
                         placeholder="enter value"
                         id="subcontractor_overhead"
                         :params="[
                            'label' => 'none',
                            'iconClass' => 'none',
                        ]"
                    >0</x-form-text>
                </div>
                <div class="col-sm-3 tc admin-form-item-widget">
                    <x-form-text name="cost"
                         class="check-contact tc"
                         placeholder="enter value"
                         id="subcontractor_cost"
                         :params="[
                            'label' => 'none',
                            'iconClass' => 'none',
                        ]"
                    >0.00</x-form-text>
                </div>
            </div>

            <!-- second left row -->

            <div class="row">
                <div class="col-sm-10 admin-form-item-widget">
                    <x-form-file-upload name="attached_bid"
                        class="check-contact tl"
                        id="subcontractor_attached_bid"
                        :params="[
                            'label' => 'Attach Bid',
                            'hint' => '(allowed ext: '.$allowedFileExtensions.')',
                        ]"
                    ></x-form-file-upload>
                </div>
                <div class="col-sm-2 tc admin-form-item-widget">
                    <x-form-check-box name="accepted"
                          class="check-contact tc mt33"
                          id="subcontractor_accepted"
                          value="1"
                    >Accepted</x-form-check-box>
                </div>
            </div>
        </div>

        <!-- right column -->
        <div class="col-sm-5">
            <div class="row">
                <div class="col-sm-6 mb2 fwb">
                    <label class="control-label">Description<i class="field-required fa fa-asterisk" data-toggle="tooltip" title="@lang('translation.field_required')"></i></label>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 admin-form-item-widget">
                    <x-form-textarea name="description"
                         class="check-contact tl h140 pl20"
                         id="subcontractor_description"
                         :params="[
                            'label' => 'none',
                            'iconClass' => 'none',
                        ]"
                    ></x-form-textarea>
                </div>
            </div>
        </div>
    </div>
</form>

<!-- subcontractor header row -->
<div id="subcontractor_rows_header" class="row fwb pb4 border-bottom-solid{{ !empty($proposalDetail->subcontractors) && $proposalDetail->subcontractors->count() > 0 ? '' : ' hidden' }}">
    <div class="col-sm-4">Subcontractor</div>
    <div class="col-sm-1 tc">Overhead</div>
    <div class="col-sm-1 tc">Quoted Cost</div>
    <div class="col-sm-1 tc">Total Cost</div>
    <div class="col-sm-3 tc">Attached Bid</div>
    <div class="col-sm-1 tc">Accepted</div>
    <div class="col-sm-1 tc">Remove</div>
</div>

<!-- subcontractor row -->
<div id="subcontractor_rows_container" class="mb20">
    @include('estimator._subcontractors_grid', ['partialSubcontractors' => $proposalDetail->subcontractors])
</div>

<!-- subcontractor footer row -->
<div class="row mt12">
    <div class="col-sm-3">
        <a id="subcontractor_add_button" href="javascript:" class="{{ $site_button_class }}">Add Subcontractor</a>
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
                                showErrorAlert(response.message, 'Critical error has occurred.');
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
                        showErrorAlert(response.message, 'Critical error has occurred.');
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


