<!-- contractor section -->

<!-- sub contractor header row -->
<div class="row fwb pb4 border-bottom-solid mt15">
    @if (auth()->user()->isAdmin())
        <div class="col-sm-4">Sub contractor</div>
        <div class="col-sm-2 tc">Description</div>
        <div class="col-sm-2 tc">% Overhead</div>
        <div class="col-sm-2 tc">Quoted Cost</div>
        <div class="col-sm-2 tc">Total Cost</div>
    @else
        <div class="col-sm-6">Sub contractor</div>
        <div class="col-sm-3 tc">Description</div>
        <div class="col-sm-3 tc">% Overhead</div>
    @endif
</div>

<div class="mb20">
    <div class="row equipment-row border-bottom-dashed">
        @if (auth()->user()->isAdmin())
            <div class="col-sm-4">{{ $acceptedSubcontractor->name }}</div>
            <div class="col-sm-2 tc">{{ $acceptedSubcontractor->description }}</div>
            <div class="col-sm-2 tc">{{ $acceptedSubcontractor->overhead }}</div>
            <div class="col-sm-2 tc">{{ $acceptedSubcontractor->html_cost }}</div>
            <div class="col-sm-2 tc">{{ $acceptedSubcontractor->html_total_cost }}</div>
        @else
            <div class="col-sm-6">{{ $acceptedSubcontractor->name }}</div>
            <div class="col-sm-3 tc">{{ $acceptedSubcontractor->description }}</div>
            <div class="col-sm-3 tc">{{ $acceptedSubcontractor->overhead }}</div>
        @endif
    </div>
</div>

@if (auth()->user()->isAdmin())
    <div class="row">
        <div class="col-sm-2 pt8 m0">
            <label class="control-label">Total Sub contractor</label>
        </div>
        <div class="col-sm-2">
            <div class="admin-form-item-widget">
                <x-form-show class="show-check-contact">
                    {!! $acceptedSubcontractor->html_total_cost !!}
                </x-form-show>
            </div>
        </div>
        <div class="col-sm-5 xs-hidden"></div>
    </div>
@endif


