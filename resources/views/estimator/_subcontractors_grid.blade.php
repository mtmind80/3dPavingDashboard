@if (!empty($partialSubcontractors) && $partialSubcontractors->count() > 0)
    @foreach ($partialSubcontractors as $subcontractor)
        <div id="proposal_detail_subcontractor_id_{{ $subcontractor->id }}" class="row subcontractor-row border-bottom-dashed{{ !empty($subcontractor->accepted) ? ' subcontractor-accepted' : '' }}">
            <div class="col-sm-4 subcontractor-name can-be-bold">{{ $subcontractor->contractor->name }}</div>
            <div class="col-sm-1 tc subcontractor-overhead can-be-bold">{{ $subcontractor->overhead_percent }}</div>
            <div class="col-sm-1 tc subcontractor-cost can-be-bold" data-cost="{{ $subcontractor->cost }}">{{ $subcontractor->html_cost }}</div>
            <div class="col-sm-1 tc subcontractor-total_cost can-be-bold" data-total_cost="{{ $subcontractor->total_cost }}">{{ $subcontractor->html_total_cost }}</div>
            <div class="col-sm-3 tc subcontractor-attached_bid">{!! $subcontractor->link_attached_bid !!}</div>
            <div class="col-sm-1 tc subcontractor-accepted" data-accepted="{{ $subcontractor->accepted }}">{!! !empty($subcontractor->accepted) ? '<i class="fa fa-check color-green"></i>' : '' !!}</div>
            <div class="col-sm-1 tc">
                <button
                    class="btn p0 btn-danger tc subcontractor-remove-button"
                    type="button"
                    data-toggle="tooltip"
                    title="remove contractor"
                    data-proposal_detail_subcontractor_id="{{ $subcontractor->id }}"
                >
                    <i class="far fa-trash-alt dib m0 plr5"></i>
                </button>
            </div>
        </div>
    @endforeach
@endif
