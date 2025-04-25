<!-- vehicle section -->

<!-- vehicle header row -->
<div class="row fwb pb4 border-bottom-solid">
    <div class="col-sm-6">Vehicle Type</div>
    @if (auth()->user()->isAdmin())
        <div class="col-sm-1 tc">Quantity</div>
        <div class="col-sm-1 tc">Days</div>
        <div class="col-sm-1 tc">Hours</div>
        <div class="col-sm-1 tc">Rate</div>
        <div class="col-sm-1 tc">Total</div>
    @else
        <div class="col-sm-2 tc">Quantity</div>
        <div class="col-sm-2 tc">Days</div>
        <div class="col-sm-2 tc">Hours</div>
    @endif
</div>

<div class="mb20">
    @foreach ($vehicles as $vehicle)
        <div class="row vehicle-row border-bottom-dashed">
            <div class="col-sm-6">{{ $vehicle->vehicle_name }}</div>
            @if (auth()->user()->isAdmin())
                <div class="col-sm-1 tc">{{ $vehicle->number_of_vehicles }}</div>
                <div class="col-sm-1 tc">{{ $vehicle->days }}</div>
                <div class="col-sm-1 tc">{{ $vehicle->hours }}</div>
                <div class="col-sm-1 tc">{{ $vehicle->rate_per_hours }}</div>
                <div class="col-sm-1 tc">{{ $vehicle->html_cost }}</div>
            @else
                <div class="col-sm-2 tc">{{ $vehicle->number_of_vehicles }}</div>
                <div class="col-sm-2 tc">{{ $vehicle->days }}</div>
                <div class="col-sm-2 tc">{{ $vehicle->hours }}</div>
            @endif
        </div>
    @endforeach
</div>

@if (auth()->user()->isAdmin())
    <div class="row">
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
@endif


