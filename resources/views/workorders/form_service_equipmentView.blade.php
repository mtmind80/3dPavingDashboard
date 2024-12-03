<!-- equipment sections -->

<!-- input fields values row -->

<!-- equipment header row -->
<div id="equipment_rows_header" class="row fwb pb4 border-bottom-solid{{ !empty($proposalDetail->equipment) && $proposalDetail->equipment->count() > 0 ? '' : ' hidden' }}">
    <div class="col-sm-5">Equipment</div>
    <div class="col-sm-1 tc">Quantity</div>
    <div class="col-sm-1 tc">Days</div>
    <div class="col-sm-1 tc">Hours</div>
    <div class="col-sm-1 tc">Rate</div>
    <div class="col-sm-1 tc">Total</div>
    <div class="col-sm-1 tc">Min Cost</div>
</div>

<!-- equipment row -->
<div id="equipment_rows_container" class="mb20">
    @if (!empty($proposalDetail->equipment) && $proposalDetail->equipment->count() > 0)
        @include('estimator._form_service_equipmentView', ['equipments' => $proposalDetail->equipment])
    @endif
</div>

<!-- equipment footer row -->
<div class="row mt12">
    <div class="col-sm-3">
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


            equipmentUpdateTotalCost();


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


        });
    </script>
@endpush
