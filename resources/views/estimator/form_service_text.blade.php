

<form method="POST" action="#" accept-charset="UTF-8" id="service_text_form" class="admin-form">
    @csrf


    <div class="row">

    <div class="col-lg-6 col-sm-4 ">
        <h5>@lang('translation.service_description')</h5>

        <label class="control-label">Service Description Template</label>
        <p><span id="service_proposal_text">{!! $service->service_template !!}</span></p>
        <span id="reset_description" class="{{ $site_button_class }}">Reset Description</span>
    </div>
    <div class="col-lg-6 col-sm-4 ">
        <label class="control-label">Actual Service Description</label>
            <textarea
                id="proposaltext"
                name="proposaltext"
                class="form-control" disabled
            >{!! $proposalDetail->proposal_text !!}</textarea>
     </div>
</div>
</form>

@push('partials-scripts')
    <script src="{{ URL::asset('/assets/js/tinymce/tinymce.min.js')}}"></script>

    <script>
        tinymce.init({
            selector: '#proposaltext',
            promotion: false,
            setup: function (editor) {
                editor.on('init', function (e) {
                    editor.setContent('{!! $proposalDetail->proposal_text !!}');
                });
            },
            height : 300,
            statusbar: false
        });
    </script>

    <script>
        $(document).ready(function () {
            var servicedesc = decodeURIComponent(encodeURIComponent("{!! $service->service_template !!}")); 

            var cubic_yards;
            var square_feet;
            var depth;
            var tons;
            
            if (serviceCategoryId == 1) {

                {{-- asphalt --}}
                if (proposalDetailId == 19) {
                    {{-- Asphalt Milling --}}
                    square_feet = $("#square_feet").val();
                    console.log('sq' + square_feet);
    

                } else {

                    cubic_yards = $("#cubic_yards").val();
                    servicedesc = servicedesc.replace('@@TONS@@', cubic_yards);

                }
            }


            if (serviceCategoryId == 2) {

                {{-- concrete --}}
                {{--IF $details.cmpServiceID < 12- *curb mix* --}}

                if (serviceId < 12) {

                    cubic_yards = $("#cubic_yards").val();
                    servicedesc = servicedesc.replace('@@TONS@@', cubic_yards);
                    
                    
                } else if (serviceId >= 12) {

                    cubic_yards = $("#cubic_yards").val();
                    depth = $("#depth").val();
                    servicedesc = servicedesc.replace('@@TONS@@', cubic_yards);
                    servicedesc = servicedesc.replace('@@INCHES@@', depth);
                    
                }
            }

            if (serviceCategoryId == 3) {
                {{--Drainage and Catchbasins--}}

                var catchbasins = $("#catchbasins").val();
                servicedesc = servicedesc.replace('@@BASINS@@', catchbasins);
            
            }

            if (serviceCategoryId == 4) {
                {{-- 4	Excavation --}}
                tons = $("#tons").text();
                servicedesc = servicedesc.replace('@@TONS@@', tons);
            }



            if (serviceCategoryId == 5) {

                {{--  Other --}}
                // no changes                


            }
            {{-- end Other --}}

            if (serviceCategoryId == 6) {

                {{--  Paver Brick --}}
                square_feet = $("#square_feet").val();
                tons = $("#tons").text();
                servicedesc = servicedesc.replace('@@SQFT@@', square_feet);
                servicedesc = servicedesc.replace('@@TONS@@', tons);

            }

            if (serviceCategoryId == 7) {
                {{--  Rock --}}
                var depth = $("#depth").val();
                servicedesc = servicedesc.replace('@@INCHES@@', depth);
                
            }


            if (serviceCategoryId == 8) {

                {{--  Seal Coating  these are the user imput fields that need to be filled in validated--}}

                var square_feet = $("#square_feet").val();
                var phase = $("#phases").val();
                servicedesc = servicedesc.replace('@@SQFT@@', square_feet);
                servicedesc = servicedesc.replace('@@PHASES@@', phases);
                

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
