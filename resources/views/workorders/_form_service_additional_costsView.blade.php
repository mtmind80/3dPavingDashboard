@if (!empty($additionalCosts) && $additionalCosts->count() > 0)
    @foreach ($additionalCosts as $additionalCost)
        <div id="proposal_detail_additional_cost_id_{{ $additionalCost->id }}" class="row additional-cost-row border-bottom-dashed">
            <div class="col-sm-4 additional-cost-type">{{ $additionalCost->type }}</div>
            <div class="col-sm-1 tc additional-cost-cost" data-cost="{{ $additionalCost->cost }}">{{ $additionalCost->html_cost }}</div>
            <div class="col-sm-5 tc additional-cost-short-description">{{ Str::limit($additionalCost->description, 100) }}</div>
        </div>
    @endforeach
@endif
