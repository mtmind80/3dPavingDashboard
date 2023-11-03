@foreach ($vehicles as $vehicle)
    <div id="proposal_detail_vehicle_id_{{ $vehicle->id }}" class="row vehicle-row border-bottom-dashed">
        <div class="col-sm-6 vehicle-type">{{ $vehicle->vehicle_name }}</div>
        <div class="col-sm-1 tc vehicle-quantity">{{ $vehicle->number_of_vehicles }}</div>
        <div class="col-sm-1 tc vehicle-days">{{ $vehicle->days }}</div>
        <div class="col-sm-1 tc vehicle-hours" data-hours="{{ $vehicle->hours }}">{{ $vehicle->hours }}</div>
        <div class="col-sm-1 tc vehicle-rate" >{{ $vehicle->html_rate_per_hour }}</div>
        <div class="col-sm-1 tc vehicle-cost" data-cost="{{ $vehicle->cost }}">{{ $vehicle->html_cost }}</div>
        <div class="col-sm-1 tc">
            <button
                class="btn p0 mr10 btn-info tc vehicle-edit-button"
                type="button"
                data-toggle="tooltip"
                title="edit item"
                data-vehicle_id="{{ $vehicle->vehicle_id }}"
                data-number_of_vehicles="{{ $vehicle->number_of_vehicles }}"
                data-days="{{ $vehicle->days }}"
                data-hours="{{ $vehicle->hours }}"
                data-proposal_detail_vehicle_id="{{ $vehicle->id }}"
            >
                <i class="far fa-edit dib m0 plr5"></i>
            </button>
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
