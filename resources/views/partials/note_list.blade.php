<div class="modal-content admin-form">
    <div class="modal-header">
        <h5 class="modal-title" id="listNotesModalLabel">@lang('translation.permit') <b>#<span>{{ $permit->number }}</span></b></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body plr10 pt10 pb0">
        <h4>Notes:</h4>
        <div id="listNotesModalContainer">
            @foreach ($permit->notes as $note)
                <div class="note-box">
                    <p class="note-date-fee clearfix">
                        <span class="note-date">{{ $note->date_creator }}</span>
                        <span class="note-fee">Fee: {{ $note->currency_fee }}</span>
                    </p>
                    <div class="note-content">
                        {!! $note->note !!}
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-light waves-effect" data-dismiss="modal">@lang('translation.close')</button>
        <label id="listNotesModalTotalFees" class="text-color">Total fees: <span>{{ $permit->currency_total_fees }}</span></label>

    </div>
</div>