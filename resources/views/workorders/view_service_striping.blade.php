<!-- stripping section -->

<!-- stripping header row -->
<div class="row fwb pb4 border-bottom-solid mt15">
    @if (auth()->user()->isAdmin())
        <div class="col-sm-8">Description</div>
        <div class="col-sm-2 tc">Quantity</div>
        <div class="col-sm-2 tc">Cost</div>
    @else
        <div class="col-sm-10 col-xs-8">Description</div>
        <div class="col-sm-2 col-xs-4 tc">Quantity</div>
    @endif
</div>

<div class="mb20">
    @foreach ($services as $service)
        <div class="row stripping-row border-bottom-dashed">
            @if (auth()->user()->isAdmin())
                <div class="col-sm-8">{{ $service->description }}</div>
                <div class="col-sm-2 tc">{{ $service->quantity }}</div>
                <div class="col-sm-2 tc">{{ $service->html_cost }}</div>
            @else
                <div class="col-sm-10">{{ $service->description }}</div>
                <div class="col-sm-2 tc">{{ $service->quantity }}</div>
            @endif
        </div>
    @endforeach
</div>

@if (auth()->user()->isAdmin())
    <div class="row">
        <div class="col-sm-2 pt8 m0">
            <label class="control-label">Total Cost</label>
        </div>
        <div class="col-sm-2">
            <div class="admin-form-item-widget">
                <x-form-show class="show-check-contact" >
                    {!! $html_total_cost !!}
                </x-form-show>
            </div>
        </div>
        <div class="col-sm-5 xs-hidden"></div>
    </div>
@endif


