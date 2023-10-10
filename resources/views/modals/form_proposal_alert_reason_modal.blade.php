<!-- Modal -->
<div class="modal fade modal-medium info" id="formAlertReasonModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="formAlertReasonModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content admin-form">
            <form method="POST" action="{{ route('proposal_alert_set') }}" accept-charset="UTF-8" id="admin_form_alert_reason_modal" class="admin-form">
                @csrf
                <input id="form_alert_reason_proposal_id" name="proposal_id" value="{{ $proposal['id'] }}" type="hidden">
                <div class="modal-header">
                    <h5 class="modal-title" id="formAlertReasonModalLabel">@lang('translation.set') @lang('translation.alert') - <b>{{ $proposal['name']  }}</b></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body plr10 pt20 pb0">
                    <x-form-textarea style="height:135px" name="alert_reason" id="form_alert_reason_alert_reason" :params="['label' =>  __('translation.add').' '. __('translation.reason'), 'iconClass' => 'fas fa-sticky-note', 'required' => false]"></x-form-textarea>
                </div>
                <div class="modal-footer pt5">
                    <button type="button" class="btn btn-light waves-effect" data-dismiss="modal">@lang('translation.close')</button>
                    <button type="submit" class="btn btn-dark waves-effect waves-light mr0">@lang('translation.save')</button>
                </div>
            </form>
        </div>
    </div>
</div>
