<div class="modal fade modal-wider info" id="modalDataByCity" tabindex="-1" role="dialog" aria-labelledby="modalDataByCityTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0" id="modalDataByCityTitle"><span id="county_name"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="card">
                    <div class="card-body pb0">
                        <div class="table-responsive">
                            <table class="table mb-0">
                                <thead>
                                <tr>
                                    <th class="tc">@lang('translation.city')</th>
                                    <th class="tc">@lang('translation.proposals')</th>
                                    <th class="tc">@lang('translation.work_orders')</th>
                                    <th class="tc">@lang('translation.sales')</th>
                                </tr>
                                </thead>
                                <tbody id="tbodyDataByCity"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
