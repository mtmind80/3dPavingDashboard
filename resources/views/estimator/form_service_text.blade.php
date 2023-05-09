

<form method="POST" action="#" accept-charset="UTF-8" id="service_text_form" class="admin-form">
    @csrf


    <div class="row">

    <div class="col-lg-6 col-sm-4 ">
        <label class="control-label">Service Description Template</label>
        <p>{!! $service->service_text_en !!}</p>
        <span id="reset_description" class="{{ $site_button_class }}">Reset Description</span>
    </div>
    <div class="col-lg-6 col-sm-4 ">
        <label class="control-label">Actual Service Description</label>
            <textarea
                id="proposaltext"
                name="proposaltext"
                class="form-control" disabled
            >{{ $proposalDetail->proposal_text }}</textarea>
     </div>
</div>
</form>
@push('partials-scripts')
    <script>
        $(document).ready(function () {
            var servicedesc = "Hello World";

            $("#reset_description").click(function() {
                $("#proposaltext").val(servicedesc);
                cancel.event;
            });
        });
    </script>
@endpush
