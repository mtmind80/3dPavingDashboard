@foreach ($equipments as $equipment)
    <div id="proposal_detail_equipment_id_{{ $equipment->id }}" class="row equipment-row border-bottom-dashed">
        <div class="col-sm-5 equipment-type">{{ $equipment->equipment->html_name_and_rate_type }}</div>
        <div class="col-sm-1 tc equipment-quantity">{{ $equipment->number_of_units }}</div>
        <div class="col-sm-1 tc equipment-days">{{ $equipment->days }}</div>
        <div class="col-sm-1 tc equipment-hours">{{ $equipment->hours }}</div>
        <div class="col-sm-1 tc equipment-rate">{{ $equipment->html_rate }}</div>
        <div class="col-sm-1 tc equipment-cost" data-cost="{{ $equipment->cost }}">{{ $equipment->html_cost }}</div>
        <div class="col-sm-1 tc equipment-rate"><span class="status {{ $equipment->cost < $equipment->equipment->min_cost ? 'danger' : '' }}">{{ $equipment->equipment->html_min_cost }}</span></div>
        <div class="col-sm-1 tc">
            <button
                class="btn p0 mr10 btn-info tc equipment-edit-button"
                type="button"
                data-toggle="tooltip"
                title="edit item"
                data-equipment_id="{{ $equipment->equipment_id }}"
                data-number_of_units="{{ $equipment->number_of_units}}"
                data-days="{{ $equipment->days }}"
                data-hours="{{ $equipment->hours }}"
                data-rate_type="{{ $equipment->rate_type }}"
                data-proposal_detail_equipment_id="{{ $equipment->id }}"
            >
                <i class="far fa-edit dib m0 plr5"></i>
            </button>
            <button
                class="btn p0 btn-danger tc equipment-remove-button"
                type="button"
                data-toggle="tooltip"
                title="remove item"
                data-proposal_detail_equipment_id="{{ $equipment->id }}"
            >
                <i class="far fa-trash-alt dib m0 plr5"></i>
            </button>
        </div>
    </div>
@endforeach
