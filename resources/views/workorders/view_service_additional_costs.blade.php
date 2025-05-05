<!-- additional costs header row -->
<div class="row fwb pb4 border-bottom-solid mt15">
    @if (auth()->user()->isAdmin())
        <div class="col-sm-4">Type</div>
        <div class="col-sm-2 tc">Cost</div>
        <div class="col-sm-6">Short Description</div>
    @else
        <div class="col-sm-6">Type</div>
        <div class="col-sm-6">Short Description</div>
    @endif
</div>

<div class="mb20">
    @foreach ($additionalCosts as $additionalCost)
        <div class="row equipment-row border-bottom-dashed">
            @if (auth()->user()->isAdmin())
                <div class="col-sm-4">{{ $additionalCost->type }}</div>
                <div class="col-sm-2 tc">{{ $additionalCost->html_cost }}</div>
                <div class="col-sm-6">{{ Str::limit($additionalCost->description, 100) }}</div>
            @else
                <div class="col-sm-6">{{ $additionalCost->type }}</div>
                <div class="col-sm-6">{{ Str::limit($additionalCost->description, 100) }}</div>
            @endif
        </div>
    @endforeach
</div>

@if (auth()->user()->isAdmin())
    <div class="row">
        <div class="col-sm-2 pt8 m0">
            <label class="control-label">Total Equipment</label>
        </div>
        <div class="col-sm-2">
            <div class="admin-form-item-widget">
                <x-form-show class="show-check-contact">
                    {!! $proposalDetail->html_total_additional_costs !!}
                </x-form-show>
            </div>
        </div>
        <div class="col-sm-5 xs-hidden"></div>
    </div>
@endif


