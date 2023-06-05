

<form method="POST" action="#" accept-charset="UTF-8" id="service_text_form" class="admin-form">
    @csrf


    <div class="row">

    <div class="col-lg-6 col-sm-4 ">
        <label class="control-label">Service Description Template</label>
        <p><span id="service_proposal_text">{!! $service->service_text_en !!}</span></p>
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
    <script src="{{ URL::asset('/assets/js/tinymce/tinymce.min.js')}}"></script>

    <script>
        tinymce.init({
            selector: '#proposaltext',

            setup: function (editor) {
                editor.on('init', function (e) {
                    editor.setContent("{{ $proposalDetail->proposal_text }}");
                });
            },
            height : 300,
            statusbar: false
        });
    </script>

    <script>
        $(document).ready(function () {
            var servicedesc = "{!! $service->service_text_en !!}";

            if (serviceCategoryId == 1) {

                {{-- asphalt --}}
                if (proposalDetailId == 19) {
                    {{-- Asphalt Milling --}}

                } else {


                }
            }


            if (serviceCategoryId == 2) {

                {{-- concrete --}}
                {{--IF $details.cmpServiceID < 12- *curb mix* --}}

                if (service_id < 12) {

                } else if (service_id >= 12) {

                }
            }

            if (serviceCategoryId == 3) {
                {{--Drainage and Catchbasins--}}

            }

            if (serviceCategoryId == 4) {
                {{-- 4	Excavation --}}
                $("#tons").text();
                servicedesc = servicedesc.replace('@@TONS@@', tons);
            }



            if (serviceCategoryId == 5) {

                {{--  Other --}}


            }
            {{-- end Other --}}

            if (serviceCategoryId == 6) {

                {{--  Paver Brick --}}

            }

            if (serviceCategoryId == 7) {
                {{--  Rock --}}

            }


            if (serviceCategoryId == 8) {

                {{--  Seal Coating  these are the user imput fields that need to be filled in validated--}}


            }

            if (serviceCategoryId == 9) {

                {{--  striping  not used for this service--}}

            }

            if (serviceCategoryId == 10) {

                {{--  Sub Contractor --}}
            }

            $("#reset_description").click(function() {
                tinymce.activeEditor.setContent(servicedesc);
                return;
            });
        });
    </script>
@endpush
