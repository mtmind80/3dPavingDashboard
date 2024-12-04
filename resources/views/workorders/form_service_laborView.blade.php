<!-- labor sections -->

<!-- labor header row -->
<div id="labor_rows_header"
     class="row fwb pb4 border-bottom-solid{{ !empty($proposalDetail->labor) && $proposalDetail->labor->count() > 0 ? '' : ' hidden' }}">
    <div class="col-sm-6">Labor Type</div>
    <div class="col-sm-1 tc">Quantity</div>
    <div class="col-sm-1 tc">Days</div>
    <div class="col-sm-1 tc">Hours</div>
    <div class="col-sm-1 tc">Rate</div>
    <div class="col-sm-1 tc">Total</div>

</div>

<!-- labor row -->
<div id="labor_rows_container" class="mb20">

    @if (!empty($proposalDetail->labor) && $proposalDetail->labor->count() > 0)
        @foreach ($proposalDetail->labor as $labor)
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

<!-- labor footer row -->
<div class="row mt12">

    <div class="col-sm-2 pt8 m0">
        <label class="control-label">Total Labor</label>
        <div class="admin-form-item-widget">
            <x-form-show
                class="show-check-contact"
                :params="[
                    'id' => 'labor_total_cost',
                ]">
            </x-form-show>
        </div>
    </div>
    <div class="col-sm-5 xs-hidden"></div>
</div>

@push('partials-scripts')
    <script>
        $(document).ready(function () {

            var laborElTotalCost = $('#labor_total_cost');

            laborUpdateTotalCost();

            function laborUpdateTotalCost() {
                let laborCosts = $('.labor-cost');
                let totalCost = 0;
                let currrencyTotalCost = 0;

                laborCosts.each(function (index) {
                    let el = $(this);
                    totalCost += Number(el.data('cost'));
                });

                currrencyTotalCost = currencyFormat(totalCost);
                laborElTotalCost.html(currrencyTotalCost);

            }

        });
    </script>
@endpush


