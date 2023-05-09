<!-- Modal -->
<div class="modal fade modal-medium info" id="listNotesModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="listNotesModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content admin-form">
            <div class="modal-header">
                <h5 class="modal-title" id="listNotesModalLabel">@lang('translation.permit') <b>#<span></span></b></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body plr10 pt10 pb0">
                <h4>Notes:</h4>
                <div id="listNotesModalContainer"></div>
            </div>
            <div class="modal-footer">
                <label id="listNotesModalTotalFees">Total fees: <span></span></label>
                <button type="button" class="btn btn-light waves-effect" data-dismiss="modal">@lang('translation.close')</button>
            </div>
        </div>
    </div>
</div>
