<!-- equipment sections -->
<div id="equipment_rows_header" class="row fwb pb4 border-bottom-solid{{ !empty($proposalDetail->equipment) && $proposalDetail->equipment->count() > 0 ? '' : ' hidden' }}">
    <div class="col-sm-5">Equipment Type</div>
    <div class="col-sm-1 tc">Quantity</div>
    <div class="col-sm-1 tc">Days</div>
    <div class="col-sm-1 tc">Hours</div>
    <div class="col-sm-1 tc">Total</div>

</div>

<!-- equipment row -->
<div id="equipment_rows_container" class="mb20">
    @if (!empty($proposalEquipment) && count($proposalEquipment) > 0)
        @foreach ($proposalEquipment as $equipment)
            <div id="proposal_detail_equipment_id_{{ $equipment['id'] }}" class="row equipment-row border-bottom-dashed">
                <div class="col-sm-5 equipment-type">{{ $equipment['equipment']['name']}} {{$equipment['equipment']['rate_type']}} </div>
                <div class="col-sm-1 tc equipment-quantity">{{ $equipment['number_of_units'] }}</div>
                <div class="col-sm-1 tc equipment-days">{{ $equipment['days'] }}</div>
                <div class="col-sm-1 tc equipment-hours">{{ $equipment['hours'] }}</div>
                <div class="col-sm-1 tc equipment-cost" data-cost="{{ $equipment['hours'] * $equipment['equipment']['rate'] }}">{{ $equipment['hours'] * $equipment['equipment']['rate'] }}</div>
            </div>
        @endforeach

    @endif
</div>

<!-- equipment footer row -->
<div class="row mt12">
    <div class="col-sm-2 pt8 m0">
        <label class="control-label">Total Equipment</label>

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

            var equipmentElTotalCost = $('#equipment_total_cost');


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


            }


        });
    </script>
@endpush
