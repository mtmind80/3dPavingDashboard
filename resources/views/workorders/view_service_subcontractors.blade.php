<!-- equipment section -->

<!-- equipment header row -->
<div class="row fwb pb4 border-bottom-solid mt15">
    @if (auth()->user()->isAdmin())
        <div class="col-sm-4">Subcontractor</div>
        <div class="col-sm-2 tc">Description</div>
        <div class="col-sm-2 tc">% Overhead</div>
        <div class="col-sm-2 tc">Quoted Cost</div>
        <div class="col-sm-2 tc">Total Cost</div>
    @else
        <div class="col-sm-6">Subcontractor</div>
        <div class="col-sm-3 tc">Description</div>
        <div class="col-sm-3 tc">% Overhead</div>
    @endif
</div>

<div class="mb20">
    @foreach ($subcontractors as $subcontractor)
        <div class="row equipment-row border-bottom-dashed">
            @if (auth()->user()->isAdmin())
                <div class="col-sm-4">{{ $subcontractor->name }}</div>
                <div class="col-sm-2 tc">{{ $subcontractor->description }}</div>
                <div class="col-sm-2 tc">{{ $subcontractor->overhead }}</div>
                <div class="col-sm-2 tc">{{ $subcontractor->html_cost }}</div>
                <div class="col-sm-2 tc">{{ $subcontractor->total_cost }}</div>
            @else
                <div class="col-sm-6">{{ $subcontractor->name }}</div>
                <div class="col-sm-3 tc">{{ $subcontractor->number_of_units }}</div>
                <div class="col-sm-3 tc">{{ $subcontractor->days }}</div>
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
                    {!! $proposalDetail->html_total_cost_equipment !!}
                </x-form-show>
            </div>
        </div>
        <div class="col-sm-5 xs-hidden"></div>
    </div>
@endif


