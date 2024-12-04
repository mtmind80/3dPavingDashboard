<!-- subcontractor sections -->

<!-- subcontractor header row -->
<div id="subcontractor_rows_header"
     class="row fwb pb4 border-bottom-solid{{ !empty($proposalDetail->subcontractors) && $proposalDetail->subcontractors->count() > 0 ? '' : ' hidden' }}">
    <div class="col-sm-3">Subcontractor</div>
    <div class="col-sm-3 tc">Description</div>
    <div class="col-sm-1 tc">% Overhead</div>
    <div class="col-sm-1 tc">Quoted Cost</div>
    <div class="col-sm-1 tc">Total Cost</div>

</div>

<!-- subcontractor row -->
<div id="subcontractor_rows_container" class="mb20">
    @if (!empty($proposalDetailSubcontractors) && count($proposalDetailSubcontractors) > 0)
        @foreach ($proposalDetailSubcontractors as $subcontractor)
            <div id="proposal_detail_subcontractor_id_{{ $subcontractor['id'] }}"
                 class="row subcontractor-row border-bottom-dashed"
                 data-total_cost="{{ $subcontractor['total_cost'] }}"
            >
                <div class="col-sm-3 subcontractor-name can-be-bold">{{ $subcontractor['contractor']['name'] }}
                    </br>{{ $subcontractor['contractor']['contact'] }}
                    </br>{{ $subcontractor['contractor']['phone'] }}</div>
                <div class="col-sm-3 tc subcontractor-description can-be-bold">{{ $subcontractor['description'] }}</div>
                <div class="col-sm-1 tc subcontractor-overhead can-be-bold">{{ $subcontractor['overhead'] }}</div>
                <div class="col-sm-1 tc subcontractor-cost can-be-bold"
                     data-cost="{{ $subcontractor['cost'] }}">{{ \App\Helpers\Currency::format($subcontractor['cost'] ?? '0.0')  }}</div>
                <div
                    class="col-sm-1 tc subcontractor-total_cost can-be-bold" data-cost="{{ $subcontractor['total_cost'] }}">{{ \App\Helpers\Currency::format($subcontractor['total_cost'] ?? '0.0')  }}</div>

            </div>
        @endforeach
    @endif
</div>

<!-- subcontractor footer row -->
<div class="row mt12">
    <div class="col-sm-2 pt8 m0">
        <label class="control-label">Total Subcontractors</label>
        <div class="admin-form-item-widget">
            <x-form-show
                class="show-check-contact"
                :params="[
                    'id' => 'subcontractor_total_cost',
                ]">
            </x-form-show>
        </div>
    </div>
    <div class="col-sm-5 xs-hidden"></div>
</div>

@push('partials-scripts')
    <script>
        $(document).ready(function () {


            subcontractorUpdateTotalCost();


            function subcontractorUpdateTotalCost() {
                let subcontractorcost = $('.subcontractor-total_cost');
                let subcontractorElTotalCost = $('#subcontractor_total_cost');
                let totalCost = 0;
                let currrencyTotalCost = '$0.00';

                subcontractorcost.each(function (index) {
                    let el = $(this);
                    totalCost += Number(el.data('cost'));
                });

                currrencyTotalCost = currencyFormat(totalCost);

                subcontractorElTotalCost.html(currrencyTotalCost);
            }

        });
    </script>
@endpush

