<!-- Modal -->
<div class="modal fade modal-medium info" id="formStatusModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="formStatusModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content admin-form">
            <form method="POST" action="#" accept-charset="UTF-8" id="admin_form_status_modal" class="admin-form">
                @csrf
                <input id="form_status_modal_return_to" name="returnTo" type="hidden">
                <input id="form_status_modal_tab" name="tab" type="hidden">
                <div class="modal-header">
                    <h5 class="modal-title" id="formStatusModalLabel">@lang('translation.permit') <b>#<span></span></b> - @lang('translation.change_status')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body plr10 pt10 pb0">
                    <p>@lang('translation.current_status'): <b><span id="current_status"></span></b></p>
                    <x-form-select name="new_status"
                       :items="$statusCB"
                       :params="['label' => __('translation.new') . ' ' . __('translation.status'), 'required' => true]">
                    </x-form-select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light waves-effect" data-dismiss="modal">@lang('translation.close')</button>
                    <button type="submit" class="btn btn-dark waves-effect waves-light">@lang('translation.save')</button>
                </div>
            </form>
        </div>
    </div>
</div>
