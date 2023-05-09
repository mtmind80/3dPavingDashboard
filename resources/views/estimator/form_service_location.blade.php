<div class="row">
    <div class="col-lg-6 col-sm-4 ">
        <label class="control-label mb0 fwb"> <strong>@lang('translation.location')</strong></label>
        <p class="">{!! $proposalDetail->location->full_location_two_lines !!}</p>
    </div>
    <div class="col-lg-6 col-sm-4 ">
        <span id="changelocation" class="{{ $site_button_class }}">Change Location</span>
    </div>
</div>


@push('partials-scripts')
    <script>

        $(document).ready(function () {

            $("#changelocation").click(function() {
                alert( "Handler for .click() called." );
            });
            
        });
    </script>
@endpush

