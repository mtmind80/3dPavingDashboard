<!-- equipment section -->

<!-- equipment header row -->
<div class="row fwb pb4 border-bottom-solid mt15">
    @if (auth()->user()->isAdmin())
        <div class="col-sm-4">Equipment Type</div>
        <div class="col-sm-2 tc">Quantity</div>
        <div class="col-sm-2 tc">Days</div>
        <div class="col-sm-2 tc">Hours</div>
        <div class="col-sm-2 tc">Total</div>
    @else
        <div class="col-sm-6">Equipment Type</div>
        <div class="col-sm-2 tc">Quantity</div>
        <div class="col-sm-2 tc">Days</div>
        <div class="col-sm-2 tc">Hours</div>
    @endif
</div>

<div class="mb20">
    @foreach ($equipments as $equipment)
        <div class="row equipment-row border-bottom-dashed">
            @if (auth()->user()->isAdmin())
                <div class="col-sm-4">{{ $equipment->equipment->name }}</div>
                <div class="col-sm-2 tc">{{ $equipment->number_of_units }}</div>
                <div class="col-sm-2 tc">{{ $equipment->days }}</div>
                <div class="col-sm-2 tc">{{ $equipment->hours }}</div>
                <div class="col-sm-2 tc">{{ $equipment->html_cost }}</div>
            @else
                <div class="col-sm-6">{{ $equipment->equipment->name }}</div>
                <div class="col-sm-2 tc">{{ $equipment->number_of_units }}</div>
                <div class="col-sm-2 tc">{{ $equipment->days }}</div>
                <div class="col-sm-2 tc">{{ $equipment->hours }}</div>
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


