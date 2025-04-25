<!-- Modal -->
<div class="modal fade modal-medium info" id="formNoteModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="formNoteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content admin-form">
            <form method="POST" action="#" accept-charset="UTF-8" id="admin_form_note_modal" class="admin-form">
                @csrf
                <input id="form_note_modal_return_to" name="returnTo" type="hidden">
                <input id="form_note_modal_tab" name="tab" type="hidden">
                <input id="form_note_contact_id" name="contact_id" type="hidden">
                <div class="modal-header">
                    <h5 class="modal-title" id="formNoteModalLabel"><b><span></span></b> - @lang('translation.add') @lang('translation.note') Or @lang('translation.memo')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body plr10 pt10 pb0">
                    <x-form-textarea style="height:135px" name="note" :params="['label' =>  __('translation.note'), 'iconClass' => 'fas fa-sticky-note', 'required' => false]"></x-form-textarea>
                </div>
                <div class="modal-footer">
                    <button
                        type="button"
                        class="btn btn-light waves-effect"
                        data-dismiss="modal"
                    >
                        @lang('translation.close')
                    </button>
                    <button
                        type="submit"
                        class="btn btn-dark waves-effect waves-light"
                    >
                        @lang('translation.save')
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
