<!-- Modal -->
<div class="modal fade modal-medium info" id="formMediaModal" data-backdrop="static" tabindex="-1" role="dialog"
     aria-labelledby="formMediaModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content admin-form">
            <form method="POST" action="#" id="admin_form_media_modal" class="admin-form" enctype="multipart/form-data">
                @csrf
                <input id="form_media_modal_return_to" name="returnTo" type="hidden">
                <input id="form_media_modal_tab" name="tab" type="hidden">
                <input type="hidden" name="proposal_id" value="{{ $id }}">

                <div class="modal-header">
                    <h5 class="modal-title" id="formMediaModalLabel"><b><span></span></b>
                        - @lang('upload')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body plr10 pt10 pb0">
                    <table class="table table-bordered w-80">
                        <tr>
                            <td>
                                Media Type
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <select class="form-control" id="media_type_id" name="media_type_id">
                                    @foreach ($mediatypes as $mediatype)
                                        <option value="{{ $mediatype['id'] }}">{{ $mediatype['type'] }}</option>
                                    @endforeach
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>Service</td>
                        </tr>
                        <tr>
                            <td>
                                <select
                                    class="form-control"
                                    id="proposal_detail_id"
                                    name="proposal_detail_id"
                                >
                                    <option value="0">ANY</option>
                                    @foreach ($services as $service)
                                        <option value="{{ $service['id'] }}">{{ $service['service_name'] }}</option>
                                    @endforeach
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span class="ri-asterisk alert-danger"></span>Description
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <textarea 
                                    data-parsley-required="true"
                                    class="form-control" 
                                    placeholder="Enter a brief description" 
                                    rows="3"
                                    cols="60"
                                    id="description" 
                                    name="description"
                                ></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <input class="form-control" type="file" name="file" id="file">
                                Acceptable file formats : {{ $doctypes }}
                            </td>
                        </tr>
                    </table>
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
