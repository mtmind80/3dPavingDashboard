<div class="mt20 mb10">
    <div class="row" style="background:{{$color->color}};">
        <h2>&nbsp; {{$color->name}}</h2>
    </div>
    <div class="row mt20">
    <div class="col-sm-2">
            Service Name
        </div>
        <div class="col-sm-6">
            <input class="form-control" type="text" name="service_name" id="service_name" value="{{ $proposalDetail->service_name }}" />
        </div>
        <div class="col-sm-4">
        </div>
    </div>
    <div class="row mt20">
        <div class="col-sm-12">

            <table class="table-centered table-light full-width">
                    <thead>
                    <tr>
                        <th class="w1-6">Customer Price</th>
                        <th class="w1-6 pr10">Profit <i class="field-required fa fa-asterisk" data-toggle="tooltip" title="@lang('translation.field_required')"></i></th>
                        <th class="w1-6 pr10">Breakeven</th>
                        <th class="w1-6">Striping Cost</th>
                        <th class="w1-6 pr10">Over Head
                            <span id="explain"></span>
                        </th>
                        <th class="w1-6">Additional Cost <i class="field-required fa fa-asterisk" data-toggle="tooltip" title="@lang('translation.field_required')"></i></th>

                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td class="w1-6 td-tt pr10">
                            <div class="admin-form-item-widget">
                                <x-form-show
                                    class="w180 show-check-contact"
                                    :params="[
                                        'id' => 'header_show_customer_price',
                                    ]">{{$proposalDetail->cost}}
                                </x-form-show>
                            </div>
                        </td>
                        <td class="w1-6 pr10">
                            <div class="admin-form-item-widget">
                                <x-form-text name="profit"
                                     class="check-contact"
                                     placeholder="enter value"
                                     id="form_header_profit"
                                     :params="[
                                        'label' => 'none',
                                        'iconClass' => 'none',
                                    ]"
                                >{{$proposalDetail->profit}}</x-form-text>
                            </div>
                        </td>
                        <td class="w1-6 pr10">
                            <div class="admin-form-item-widget">
                                <x-form-show
                                    class="w180 show-check-contact"
                                    :params="[
                                        'id' => 'form_header_break_even',
                                    ]"
                                >{{$proposalDetail->break_even}}</x-form-show>
                            </div>
                        </td>
                        <td class="w1-6 td-tt pr10">
                            <div class="admin-form-item-widget">
                                <x-form-show
                                    class="w180 show-check-contact"
                                    :params="[
                                        'id' => 'header_show_combined_costing',
                                    ]">
                                </x-form-show>
                            </div>
                        </td>
                        <td class="w1-6 pr10">
                            <div class="admin-form-item-widget">
                                <x-form-show
                                     class="w180 show-check-contact"
                                     :params="[
                                        'id' => 'form_header_over_head',
                                    ]">{{$proposalDetail->overhead}}</x-form-show>

                            </div>
                        </td>
                        <td class="w1-6 pr10">
                            <div class="admin-form-item-widget">
                                <x-form-text name="material_cost"
                                             class="check-contact"
                                             placeholder="enter value"
                                             id="header_show_materials_cost"
                                             :params="[
                                        'label' => 'none',
                                        'iconClass' => 'none',
                                    ]"
                                >{{$proposalDetail->material_cost}}</x-form-text>
                            </div>
                        </td>

                    </tr>
                    </tbody>
                </table>
        </div>
    </div>
</div>

@push('partials-scripts')
    <script>


/*
        Customer Price =header_show_customer_price
        Profit =form_header_profit
        Breakeven  =form_header_break_even
        Striping Cost =header_show_combined_costing
        Over Head = form_header_over_head
        Additional Cost =header_show_materials_cost
  */

        var headerElCustomerPrice = $('#header_show_customer_price'); //customer final price (cost)
        var headerElCombinedCosting= $('#header_show_combined_costing'); // striping

        var headerElForm = $('#estimator_form');
        var headerElProfit = $('#form_header_profit');
        var headerElOverHead= $('#form_header_over_head'); //determined by formula
        var headerElBreakEven= $('#form_header_break_even'); // striping cost + overhead
        var headerElMaterialsCost = $('#header_show_materials_cost'); //additional costs


        var headerCalculateCombinedCostingButton = $('#header_calculate_combined_costing_button');
        var headerCalculateCombinedCostingButton2 = $('#header_calculate_combined_costing_button2');
        var headerCalculateCombinedCostingButton3 = $('#header_calculate_combined_costing_button3');
        var headerCalculateCombinedCostingButton4 = $('#header_calculate_combined_costing_button4');


        var headerAlert = $('#header_alert');

        $(document).ready(function () {

            headerAlert.on('click', function(ev){
                ev.stopPropagation();
                ev.preventDefault();
                closeAlert(headerAlert);
            });


            headerCalculateCombinedCostingButton.on('click', function(){
                headerElForm.validate({
                    rules: {
                        profit: {
                            required: true,
                            float: true
                        },
                        overhead: {
                            required: true,
                            float: true
                        },
                        break_even: {
                            required: true,
                            float: true
                        }
                    },
                    messages: {
                        profit: {
                            required: "@lang('translation.field_required')",
                            float: "@lang('translation.invalid_entry')"
                        },
                        overhead: {
                            required: "@lang('translation.field_required')",
                            float: "@lang('translation.invalid_entry')"
                        },
                        break_even: {
                            required: "@lang('translation.field_required')",
                            float: "@lang('translation.invalid_entry')"
                        }
                    }
                });

                if (headerElForm.valid()) {

                    var dosave = 1;
                    calculate(dosave);

                    let formData = headerElForm.serializeObject();
                    let extraFormProperties = {proposal_detail_id: proposalDetailId};

                    $.extend(formData, extraFormProperties);

                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: formData,
                        type: "POST",
                        url: "{{ route('ajax_header_calculate_combined_costing') }}",
                        beforeSend: function (request){
                            showSpinner();
                        },
                        complete: function (){
                            hideSpinner();
                        },
                        success: function (response){
                            if (!response) {
                                showErrorAlert('Critical error has occurred.', headerAlert);
                            } else if (response.success) {
                                let data = response.data;

                                headerElCombinedCosting.html(data.formated_combined_costing);
                                headerElCombinedCosting.data('combined_costing_total_cost', data.combined_costing);

                                // headerResetForm();

                                if (response.message) {
                                    showSuccessAlert(response.message, headerAlert);
                                }
                            } else {
                                showErrorAlert(response.message, headerAlert);
                            }
                        },
                        error: function (response){
                            @if (env('APP_ENV') === 'local')
                                showErrorAlert(response.responseJSON.message, headerAlert);
                            @else
                                showErrorAlert(response.message, 'Critical error has occurred.');
                            @endif
                        }
                    });
                }
            });


            function headerResetForm()
            {
                headerElForm.trigger('reset');
            }
        });

    </script>
@endpush
