<!-- vehicle sections -->

<!-- additional costs header row -->
<div id="additional_cost_rows_header" class="row fwb pb4 border-bottom-solid{{ !empty($proposalDetail->additionalCosts) && $proposalDetail->additionalCosts->count() > 0 ? '' : ' hidden' }}">
    <div class="col-sm-4">Type</div>
    <div class="col-sm-1 tc">Cost</div>
    <div class="col-sm-5 tc">Short Description</div>
</div>

<!-- additional costs rows -->
<div id="additional_cost_rows_container" class="mb20">
    @if (!empty($additionalCosts) && $additionalCosts->count() > 0)
        @foreach ($additionalCosts as $additionalCost)
            <div id="proposal_detail_additional_cost_id_{{ $additionalCost->id }}" class="row additional-cost-row border-bottom-dashed">
                <div class="col-sm-4 additional-cost-type">{{ $additionalCost->type }}</div>
                <div class="col-sm-1 tc additional-cost-cost" data-cost="{{ $additionalCost->cost }}">{{ $additionalCost->html_cost }}</div>
                <div class="col-sm-5 tc additional-cost-short-description">{{ Str::limit($additionalCost->description, 100) }}</div>
            </div>
        @endforeach
    @endif
</div>
<!-- additional costs footer row -->
<div class="row mt12">
    <div class="col-sm-2 pt8 m0">
        <label class="control-label">Total Additional Costs</label>

        <div class="admin-form-item-widget">
            <x-form-show
                class="show-check-contact"
                :params="[
                    'id' => 'additional_cost_total_cost',
                ]">
            </x-form-show>
        </div>
    </div>
    <div class="col-sm-5 xs-hidden"></div>
</div>

@push('partials-scripts')
    <script>
        $(document).ready(function () {

            var additionalCostElTotalCost = $('#additional_cost_total_cost');


            additionalCostUpdateTotalCost();


            function additionalCostUpdateTotalCost()
            {
                let additionalCostCost = $('.additional-cost-cost');
                let totalCost = 0;
                let currrencyTotalCost = 0;

                additionalCostCost.each(function(index){
                    let el = $(this);
                    totalCost += Number(el.data('cost'));
                });

                currrencyTotalCost = currencyFormat(totalCost);

                additionalCostElTotalCost.html(currrencyTotalCost);

            }

        });
    </script>
@endpush


