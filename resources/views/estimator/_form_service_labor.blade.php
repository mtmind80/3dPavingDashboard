<!-- labor row -->
<div id="labor_rows_container" class="mb20">
    @if (!empty($labors) && $labors->count() > 0)
        @foreach ($labors as $labor)
            <div id="proposal_detail_labor_id_{{ $labor->id }}" class="row labor-row border-bottom-dashed">
                <div class="col-sm-6 labor-type">{{ $labor->labor_name }}</div>
                <div class="col-sm-1 tc labor-quantity">{{ $labor->number }}</div>
                <div class="col-sm-1 tc labor-days">{{ $labor->days }}</div>
                <div class="col-sm-1 tc labor-hours">{{ $labor->hours }}</div>
                <div class="col-sm-1 tc labor-rate">{{ $labor->html_rate_per_hour }}</div>
                <div class="col-sm-1 tc labor-cost" data-cost="{{ $labor->cost }}">{{ $labor->html_cost }}</div>
                <div class="col-sm-1 tc">
                    <button
                        class="btn p0 mr10 btn-info tc labor-edit-button"
                        type="button"
                        data-toggle="tooltip"
                        title="edit item"
                        data-labor_id="{{ $labor->labor_id }}"
                        data-number="{{ $labor->number }}"
                        data-days="{{ $labor->days }}"
                        data-hours="{{ $labor->hours }}"
                        data-proposal_detail_labor_id="{{ $labor->id }}"
                    >
                        <i class="far fa-edit dib m0 plr5"></i>
                    </button>
                    <button
                        class="btn p0 btn-danger tc labor-remove-button"
                        type="button"
                        data-toggle="tooltip"
                        title="remove item"
                        data-proposal_detail_labor_id="{{ $labor->id }}"
                    >
                        <i class="far fa-trash-alt dib m0 plr5"></i>
                    </button>
                </div>
            </div>
        @endforeach
    @endif
</div>
