
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
            var servicedesc = '{!! $service->service_template !!}';

            var square_feet = $("#square_feet").val();
            var cubic_yards = $("#cubic_yards").val();
            var depth = $("#depth").val();
            var catchbasins = $("#catchbasins").val();
            var tons = $("#tons").text();
            var phase = $("#phases").val();

            if (serviceCategoryId == 1) {

                {{-- asphalt --}}
                if (serviceId == 19) {
                    {{-- Asphalt Milling --}}
                   square_feet = $("#square_feet").val();
                   servicedesc = servicedesc.replace('#SQFT#', square_feet);


                } else {
                    cubic_yards = $("#cubic_yards").val();
                    servicedesc = servicedesc.replace('#TONS#', cubic_yards);

                }
            }


            if (serviceCategoryId == 2) {

                {{-- concrete --}}
                {{--IF $details.cmpServiceID < 12- *curb mix* --}}

                if (serviceId < 12) {
                    var linear_feet = $("#linear_feet").val();
                    var cubic_yards = Math.ceil(linear_feet / 60);

                    /*
                    6    Concrete    Curb (Extruded) [New or Repairs]  (linear_feet/60)
                    7    Concrete    Curb (Type D) [New or Repairs]    (linear_feet/21)
                    8    Concrete    Curb (Type Mod D) [New or Repairs] (linear_feet/30)
                    9    Concrete    Curb (Type F) [New or Repairs]    (linear_feet/24)
                    10    Concrete    Curb (Valley Gutter) [New or Repairs] (linear_feet/15)
                    11    Concrete    Curb (Header) [New or Repairs]    (linear_feet/25)
                    */


                    if (serviceId == 6) {
                        cubic_yards = Math.ceil(linear_feet / 60);
                    }

                    if (serviceId == 7) {
                        cubic_yards = Math.ceil(linear_feet / 21);
                    }
                    if (serviceId == 8) {
                        cubic_yards = Math.ceil(linear_feet / 30);
                    }
                    if (serviceId == 9) {

                        cubic_yards = Math.ceil(linear_feet / 15);
                    }
                    if (serviceId == 10) {

                        cubic_yards = Math.ceil(linear_feet / 22);
                    }

                    if (serviceId == 11) {
                        cubic_yards = Math.ceil(linear_feet / 25);
                    }

                    
                    servicedesc = servicedesc.replace('#TONS#', cubic_yards);
                    
                    
                } else if (serviceId >= 12) {
                    var cubic_yards = $("#cubic_yards").val();
                    var depth = $("#depth").val();
                    servicedesc = servicedesc.replace('#TONS#', cubic_yards);
                    servicedesc = servicedesc.replace('#INCHES#', depth);
                    
                }
            }

            if (serviceCategoryId == 3) {
                {{--Drainage and Catchbasins--}}
                
                servicedesc = servicedesc.replace('#BASINS#', catchbasins);

                    
            }

            if (serviceCategoryId == 4) {
                {{-- 4	Excavation --}}
                servicedesc = servicedesc.replace('#TONS#', tons);
            }



            if (serviceCategoryId == 5) {

                {{--  Other --}}


            }
            {{-- end Other --}}

            if (serviceCategoryId == 6) {

                {{--  Paver Brick --}}
                servicedesc = servicedesc.replace('#SQFT#', square_feet);
                servicedesc = servicedesc.replace('#TONS#', tons);


            }

            if (serviceCategoryId == 7) {
                {{--  Rock --}}
                    servicedesc = servicedesc.replace('#INCHES#', depth);
                
            }


            if (serviceCategoryId == 8) {

                {{--  Seal Coating  these are the user imput fields that need to be filled in validated--}}

                servicedesc = servicedesc.replace('#SQFT#', square_feet);
                servicedesc = servicedesc.replace('#PHASES#', phases);
                

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
