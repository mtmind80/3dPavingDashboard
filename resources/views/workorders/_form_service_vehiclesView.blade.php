@foreach ($vehicles as $vehicle)
    <div id="proposal_detail_vehicle_id_{{ $vehicle->id }}" class="row vehicle-row border-bottom-dashed">
        <div class="col-sm-6 vehicle-type">{{ $vehicle->vehicle_name }}</div>
        <div class="col-sm-1 tc vehicle-quantity">{{ $vehicle->number_of_vehicles }}</div>
        <div class="col-sm-1 tc vehicle-days">{{ $vehicle->days }}</div>
        <div class="col-sm-1 tc vehicle-hours" data-hours="{{ $vehicle->hours }}">{{ $vehicle->hours }}</div>
        <div class="col-sm-1 tc vehicle-rate" >{{ $vehicle->html_rate_per_hour }}</div>
        <div class="col-sm-1 tc vehicle-cost" data-cost="{{ $vehicle->cost }}">{{ $vehicle->html_cost }}</div>

    </div>
@endforeach
