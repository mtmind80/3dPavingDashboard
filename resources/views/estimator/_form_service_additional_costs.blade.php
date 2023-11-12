@if (!empty($additionalCosts) && $additionalCosts->count() > 0)
    @foreach ($additionalCosts as $additionalCost)
        <div id="proposal_detail_additional_cost_id_{{ $additionalCost->id }}" class="row additional-cost-row border-bottom-dashed">
            <div class="col-sm-4 additional-cost-type">{{ $additionalCost->type }}</div>
            <div class="col-sm-1 tc additional-cost-cost" data-cost="{{ $additionalCost->cost }}">{{ $additionalCost->html_cost }}</div>
            <div class="col-sm-5 tc additional-cost-short-description">{{ Str::limit($additionalCost->description, 100) }}</div>
            <div class="col-sm-2 tr pr20">
                <button
                    class="btn p0 btn-success tc additional-cost-show-description-button mr10"
                    type="button"
                    data-toggle="tooltip"
                    title="Show full description"
                    data-proposal_detail_additional_cost_id="{{ $additionalCost->id }}"
                    data-bs-toggle="modal"
                    data-bs-target="#modal_full_description_{{ $additionalCost->id }}"
                >
                    <i class="far fa-eye dib m0 plr5"></i>
                    <div class="additional-cost-description hidden">{{ $additionalCost->description }}</div>
                </button>
                <button
                    class="btn p0 mr10 btn-info tc additional-cost-edit-button"
                    type="button"
                    data-toggle="tooltip"
                    title="edit item"
                    data-cost_type="{{ $additionalCost->type }}"
                    data-amount="{{ $additionalCost->amount }}"
                    data-description="{{ $additionalCost->description }}"
                    data-proposal_detail_additional_cost_id="{{ $additionalCost->id }}"
                >
                    <i class="far fa-edit dib m0 plr5"></i>
                </button>
                <button
                    class="btn p0 btn-danger tc additional-cost-remove-button"
                    type="button"
                    data-toggle="tooltip"
                    title="remove item"
                    data-proposal_detail_additional_cost_id="{{ $additionalCost->id }}"
                >
                    <i class="far fa-trash-alt dib m0 plr5"></i>
                </button>
            </div>

        </div>
    @endforeach
@endif
