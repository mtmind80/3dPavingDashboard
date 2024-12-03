@foreach ($equipments as $equipment)
    <div id="proposal_detail_equipment_id_{{ $equipment->id }}" class="row equipment-row border-bottom-dashed">
        <div class="col-sm-5 equipment-type">{{ $equipment->equipment->html_name_and_rate_type }}</div>
        <div class="col-sm-1 tc equipment-quantity">{{ $equipment->number_of_units }}</div>
        <div class="col-sm-1 tc equipment-days">{{ $equipment->days }}</div>
        <div class="col-sm-1 tc equipment-hours">{{ $equipment->hours }}</div>
        <div class="col-sm-1 tc equipment-rate">{{ $equipment->html_rate }}</div>
        <div class="col-sm-1 tc equipment-cost" data-cost="{{ $equipment->cost }}">{{ $equipment->html_cost }}</div>
        <div class="col-sm-1 tc equipment-rate"><span class="status {{ $equipment->cost < $equipment->equipment->min_cost ? 'danger' : '' }}">{{ $equipment->equipment->html_min_cost }}</span></div>

    </div>
@endforeach
