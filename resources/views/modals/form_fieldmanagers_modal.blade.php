<!-- Modal -->
<div class="modal fade modal-medium info" id="formFieldManagersModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="formManagersModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content admin-form">
            <form method="POST" action="#" accept-charset="UTF-8" id="admin_form_fieldmanagers_modal" class="admin-form">
                @csrf
                <input id="form_managers_modal_return_to" name="returnTo" type="hidden">
                <input id="form_managers_lead_id" name="lead_to" type="hidden">
                <div class="modal-header">
                    <h5 class="modal-title" id="formFieldManagersModalLabel"><b><span></span></b> - @lang('translation.assign') @lang('translation.SalesManager')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body plr10 pt20 pb0">
                    <h6 id="formManagersLabel">@lang('translation.assign') @lang('translation.manager'):</h6>
                    <div class="form-group">
                        <select class="form-control" name="assigned_to" id="assigned_to">
                            <option value="0" disabled>@lang('translation.select') @lang('translation.SalesManager')</option>
                            @foreach ($fieldmanagersCB as $id => $fullName)
                                <option value="{{ $id }}">{{ $fullName }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light waves-effect" data-dismiss="modal">@lang('translation.close')</button>
                    <button type="submit" class="btn btn-dark waves-effect waves-light">@lang('translation.assign')</button>
                </div>
            </form>
        </div>
    </div>
</div>
