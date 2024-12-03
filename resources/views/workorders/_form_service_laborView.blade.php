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

            </div>
        @endforeach
    @endif
</div>
