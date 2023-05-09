<!-- Modal -->
<div class="modal fade modal-medium info" id="formModal" data-backdrop="static" 
     tabindex="-1" role="dialog" aria-labelledby="formModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content admin-form">
            <form method="POST" action="#" accept-charset="UTF-8" id="admin_form_media_modal" class="admin-form">
                @csrf
                <input id="form_media_modal_return_to" name="returnTo" type="hidden">
                <input id="form_media_modal_tab" name="tab" type="hidden">
                <input id="form_media_proposal_id" name="proposal_id" type="hidden" value="{{$proposal['id']}}">
                <div class="modal-header">
                    <h5 class="modal-title" id="formModalLabel"><b><span></span></b> - @lang('translation.add') @lang('translation.media')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body plr10 pt10 pb0">
                    <x-form-textarea style="height:135px" name="media" :params="['label' =>  __('translation.media'), 'iconClass' => 'fas fa-sticky-media', 'required' => false]"></x-form-textarea>
                </div>

                <div class="modal-body plr10 pt10 pb0">
                        <x-form-check-box name="reminder" id="reminder" value="1"
                                          :checked="false">
                            @lang('translation.reminder')
                        </x-form-check-box>
                </div>
                <div class="modal-body plr10 pt10 pb0">
                    <x-form-date_picker name="reminder_date" :params="['label' =>  __('translation.reminderdate'), 'iconClass' => 'fas fa-calendar', 'required' => false]"></x-form-date_picker>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light waves-effect" data-dismiss="modal">@lang('translation.close')</button>
                    <button type="submit" class="btn btn-dark waves-effect waves-light">@lang('translation.save')</button>
                </div>
            </form>
        </div>
    </div>
</div>
