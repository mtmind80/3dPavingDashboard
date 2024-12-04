<!-- vehicle sections -->

<!-- input fields header row -->

<!-- vehicle header row -->
<div id="vehicle_rows_header" class="row fwb pb4 border-bottom-solid{{ !empty($proposalDetail->vehicles) && $proposalDetail->vehicles->count() > 0 ? '' : ' hidden' }}">
    <div class="col-sm-6">Vehicle Type</div>
    <div class="col-sm-1 tc">Quantity</div>
    <div class="col-sm-1 tc">Days</div>
    <div class="col-sm-1 tc">Hours</div>
    <div class="col-sm-1 tc">Total</div>
</div>

<!-- vehicles row -->
<div id="vehicle_rows_container" class="mb20">
    @if (!empty($proposalDetail->vehicles) && $proposalDetail->vehicles->count() > 0)
        @foreach ($proposalDetail->vehicles as $vehicle)
            <div id="proposal_detail_vehicle_id_{{ $vehicle->id }}" class="row vehicle-row border-bottom-dashed">
                <div class="col-sm-6 vehicle-type">{{ $vehicle->vehicle_name }}</div>
                <div class="col-sm-1 tc vehicle-quantity">{{ $vehicle->number_of_vehicles }}</div>
                <div class="col-sm-1 tc vehicle-days">{{ $vehicle->days }}</div>
                <div class="col-sm-1 tc vehicle-hours" data-hours="{{ $vehicle->hours }}">{{ $vehicle->hours }}</div>
                <div class="col-sm-1 tc vehicle-cost" data-cost="{{ $vehicle->cost }}">{{ $vehicle->html_cost }}</div>

            </div>
        @endforeach
    @endif
</div>

<!-- vehicle footer row -->
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
            var proposalDetailId = Number('{{ $proposalDetail->id }}');
            var vehicleElForm = $('#vehicle_form');
            var vehicleElTotalCost = $('#vehicle_total_cost');
            vehicleUpdateTotalCost();


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

            }

        });
    </script>
@endpush


