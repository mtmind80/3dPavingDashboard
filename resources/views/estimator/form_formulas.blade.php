<div class="mt20 mb10">
    <form action="#" id="cost_formula_form" class="admin-form">
        {{-- This form is for reference to calculate costs it it never submitted --}}

        {{--

                    Categories
                    1	Asphalt
                    2	Concrete
                    3	Drainage and Catchbasins
                    4	Excavation
                    5	Other
                    6	Paver Brick
                    7	Rock
                    8	Seal Coating
                    9	Striping
                    10	Sub Contractor

                    Services
                    3	1	Repairs
                    4	1	Asphalt Paving - (Over 3500 SY)
                    5	1	Paving (Under 3500 SY)
                    19	1	Milling
                    22	1	Milling and Paving- (Over 3500 SY)
                    6	2	Curb (Extruded)
                    7	2	Curb (Type D)
                    8	2	Curb (Type Mod D)
                    9	2	Curb (Type F)
                    10	2	Curb (Valley Gutter)
                    11	2	Curb (Header) [New or Repairs]
                    12	2	Slab
                    13	2	Ramp
                    14	2	Sidewalks
                    21	3	Drainage and Catchbasins
                    1	4	All Excavation
                    16	5	Other Service
                    20	6	Paver Brick
                    2	7	Rock Services
                    15	8	Sealcoating
                    18	9	Pavement Markings
                    17	10	Any Sub Contractor

                    --}}


        @if($service->service_category_id == 1)

            <!-- asphalt -->

            @if($proposalDetail->id == 19)
                {{-- Asphalt Milling --}}
                <div class="row">
                    <div class="col-sm-3">
                        <label  class="control-label">Size of project in SQ FT <i class="field-required fa fa-asterisk" data-toggle="tooltip" title="@lang('translation.field_required')"></i></label>

                        <x-form-text name="square_feet"
                                     class="check-contact tc"
                                     placeholder="enter value"
                                     id="square_feet"
                                     :params="[
                    'label' => 'none',
                    'iconClass' => 'none',
                    'value' => '{{$service->square_feet}}',
                ]"
                        ></x-form-text>
                    </div>
                    <div class="col-sm-3">
                        <label class="control-label">Depth In Inches <i class="field-required fa fa-asterisk" data-toggle="tooltip" title="@lang('translation.field_required')"></i></label>
                        <x-form-text name="depth"
                                     class="check-contact tc"
                                     placeholder="enter value"
                                     id="depth"
                                     :params="[
                    'label' => 'none',
                    'iconClass' => 'none',
                    'value' => '{{$service->depth}}',
                ]"
                        ></x-form-text>
                    </div>
                    <div class="col-sm-3">

                        <label class="control-label">Days of Milling <i class="field-required fa fa-asterisk" data-toggle="tooltip" title="@lang('translation.field_required')"></i></label>
                        <x-form-text name="days"
                                     class="check-contact tc"
                                     placeholder="enter value"
                                     id="days"
                                     :params="[
                    'label' => 'none',
                    'iconClass' => 'none',
                    'value' => '{{$service->days}}',
                ]"
                        ></x-form-text>

                    </div>

                    <div class="col-sm-3">
                        <label class="control-label">Cost Per Day <i class="field-required fa fa-asterisk" data-toggle="tooltip" title="@lang('translation.field_required')"></i></label>
                        <x-form-text name="cost_per_day"
                                     class="check-contact tc"
                                     placeholder="enter value"
                                     id="cost_per_day"
                                     :params="[
                    'label' => 'none',
                    'iconClass' => 'none',
                    'value' => '{{$service->cost_per_day}}',
                ]"
                        ></x-form-text>

                    </div>
                </div>
                <br/>
                <div class="row">

                    <div class="col-sm-3">
                        <label class="control-label">Locations <i class="field-required fa fa-asterisk" data-toggle="tooltip" title="@lang('translation.field_required')"></i></label>
                        <x-form-text name="locations"
                                     class="check-contact tc"
                                     placeholder="enter value"
                                     id="locations"
                                     :params="[
                    'label' => 'none',
                    'iconClass' => 'none',
                    'value' => '{{$service->locations}}',
                ]"
                        ></x-form-text>

                    </div>
                    <div class="col-sm-4">
                        <label class="control-label">SQ. Yards <i class="field-required fa fa-asterisk" data-toggle="tooltip" title="@lang('translation.field_required')"></i></label>
                        <x-form-text name="square_yards"
                                     class="check-contact tc"
                                     placeholder="enter value"
                                     id="square_yards"
                                     :params="[
                    'label' => 'none',
                    'iconClass' => 'none',
                    'value' => '{{$service->square_yards}}',
                ]"
                        ></x-form-text>

                    </div>
                    <div class="col-sm-4">
                        <label class="control-label">Loads <i class="field-required fa fa-asterisk" data-toggle="tooltip" title="@lang('translation.field_required')"></i></label>
                        <x-form-text name="loads"
                                     class="check-contact tc"
                                     placeholder="enter value"
                                     id="loads"
                                     :params="[
                    'label' => 'none',
                    'iconClass' => 'none',
                    'value' => '{{$service->loads}}',
                ]"
                        ></x-form-text>
                    </div>
                </div>

            @else
                {{-- * all other asphalt types --}}


                <br/>
                <div class="row">

                    <div class="col-sm-3">
                        <label class="control-label">Size of project in SQ FT <i class="field-required fa fa-asterisk" data-toggle="tooltip" title="@lang('translation.field_required')"></i></label>
                        <x-form-text name="square_feet"
                                     class="check-contact tc"
                                     placeholder="enter value"
                                     id="square_feet"
                                     :params="[
                    'label' => 'none',
                    'iconClass' => 'none',
                    'value' => '{{$service->square_feet}}',
                ]"></x-form-text>
                    </div>
                    <div class="col-sm-3">
                        <label class="control-label">Depth In Inches <i class="field-required fa fa-asterisk" data-toggle="tooltip" title="@lang('translation.field_required')"></i></label>
                        <x-form-text name="depth"
                                     class="check-contact tc"
                                     placeholder="enter value"
                                     id="depth"
                                     :params="[
                    'label' => 'none',
                    'iconClass' => 'none',
                    'value' => '{{$service->depth}}',
                ]"
                        ></x-form-text>
                    </div>

                    <div class="col-sm-3">

                        <x-form-select name="cost_per_linear_feet"
                                       :items="$asphaltMaterials"
                                       selected=""
                                       :params="['label' => 'Asphalt Cost per ton', 'required' => true]"
                        ></x-form-select>


                    </div>
                    <div class="col-sm-3">
                        <label class="control-label">Locations <i class="field-required fa fa-asterisk" data-toggle="tooltip" title="@lang('translation.field_required')"></i></label>
                        <x-form-text name="locations"
                                     class="check-contact tc"
                                     placeholder="enter value"
                                     id="locations"
                                     :params="[
                    'label' => 'none',
                    'iconClass' => 'none',
                    'value' => '{{$service->locations}}',
                ]"
                        ></x-form-text>

                    </div>
                    
                </div>
                <br/>
                <div class="row">

                    <div class="col-sm-3">
                        <label class="control-label">Tack Cost:(<i>$40{{--$mat['Tack (per gallon)']--}} per gal.</i>)
                        </label>
                        <input type="text" id="tack_cost" name="tack_cost"
                               class="form-control" value="" disabled>
                    </div>
                    <div class="col-sm-4">
                        <label class="control-label">SQ. Yards </label>
                        <input type="text" id="square_yards" name="square_yards"
                               class="form-control" value="{{$service->square_yards}}" disabled>
                    </div>
                    <div class="col-sm-4">
                        <label class="control-label">Tons </label>
                        <input type="text" id="tons" name="tons"
                               class="form-control" value="{{$service->tons}}" disabled>
                    </div>
                </div>

            @endif

        @endif
        {{-- END of Asphalt --}}

        @if($service->service_category_id == 2)

            <!-- concrete -->

            {{--IF $details.cmpServiceID < 12- *curb mix* --}}
            @if($service->id < 12)
                <br/>

                <div class="row">
                    <div class="col-sm-3">
                        <label class="control-label">Length In Feet (linear feet) <i class="field-required fa fa-asterisk" data-toggle="tooltip" title="@lang('translation.field_required')"></i></label>
                        <x-form-text name="linear_feet"
                                     class="check-contact tc"
                                     placeholder="enter value"
                                     id="linear_feet"
                                     :params="[
                    'label' => 'none',
                    'iconClass' => 'none',
                    'value' => '{{$service->linear_feet}}',
                ]"
                        ></x-form-text>
                    </div>

                    <div class="col-sm-2">
                        <label class="control-label">Locations <i class="field-required fa fa-asterisk" data-toggle="tooltip" title="@lang('translation.field_required')"></i></label>
                        <x-form-text name="locations"
                                     class="check-contact tc"
                                     placeholder="enter value"
                                     id="locations"
                                     :params="[
                    'label' => 'none',
                    'iconClass' => 'none',
                    'value' => '{{$service->locations}}',
                ]"
                        ></x-form-text>
                    </div>
                    <div class="col-sm-3">
                        <label class="control-label">CU YDS <i class="field-required fa fa-asterisk" data-toggle="tooltip" title="@lang('translation.field_required')"></i></label>
                        <x-form-text name="cubic_yards"
                                     class="check-contact tc"
                                     placeholder="enter value"
                                     id="cubic_yards"
                                     :params="[
                    'label' => 'none',
                    'iconClass' => 'none',
                    'value' => '{{$service->cubic_yards}}',
                ]"
                        ></x-form-text>
                    </div>
                    <div class="col-sm-4">
                        <label class="control-label">Curb Mix Cost Per CU. YD. <i class="field-required fa fa-asterisk" data-toggle="tooltip" title="@lang('translation.field_required')"></i></label>
                        <x-form-text name="cost_per_linear_feet"
                                     class="check-contact tc"
                                     placeholder="enter value"
                                     id="cost_per_linear_feet"
                                     :params="[
                    'label' => 'none',
                    'iconClass' => 'none',
                    'value' => '{{$service->cost_per_linear_feet}}',
                ]"
                        ></x-form-text>
                        {{--{$mat['Concrete (Curb Mix) per cubic yard']} --}}
                    </div>

                </div>

            @elseif ($service->id >= 12)

                {{--IF $details.cmpServiceID >= 12-- *drum mix* --}}
                <br/>
                <div class="row">
                    <div class="col-sm-2">
                        <label class="control-label">Square Feet <i class="field-required fa fa-asterisk" data-toggle="tooltip" title="@lang('translation.field_required')"></i></label>
                        <x-form-text name="square_feet"
                                     class="check-contact tc"
                                     placeholder="enter value"
                                     id="square_feet"
                                     :params="[
                    'label' => 'none',
                    'iconClass' => 'none',
                    'value' => '{{$service->square_feet}}',
                ]"></x-form-text>

                    </div>
                    <div class="col-sm-2">
                        <label class="control-label">Depth (inches) <i class="field-required fa fa-asterisk" data-toggle="tooltip" title="@lang('translation.field_required')"></i></label>
                        <x-form-text name="depth"
                                     class="check-contact tc"
                                     placeholder="enter value"
                                     id="depth"
                                     :params="[
                    'label' => 'none',
                    'iconClass' => 'none',
                    'value' => '{{$service->depth}}',
                ]"
                        ></x-form-text>
                    </div>
                    <div class="col-sm-2">
                        <label class="control-label">Locations <i class="field-required fa fa-asterisk" data-toggle="tooltip" title="@lang('translation.field_required')"></i></label>
                        <x-form-text name="locations"
                                     class="check-contact tc"
                                     placeholder="enter value"
                                     id="locations"
                                     :params="[
                    'label' => 'none',
                    'iconClass' => 'none',
                    'value' => '{{$service->locations}}',
                ]"
                        ></x-form-text>
                    </div>
                    <div class="col-sm-3">
                        <label class="control-label">CU YDS <i class="field-required fa fa-asterisk" data-toggle="tooltip" title="@lang('translation.field_required')"></i></label>
                        <x-form-text name="cubic_yards"
                                     class="check-contact tc"
                                     placeholder="enter value"
                                     id="cubic_yards"
                                     :params="[
                    'label' => 'none',
                    'iconClass' => 'none',
                    'value' => '{{$service->cubic_yards}}',
                ]"
                        ></x-form-text>
                    </div>
                    <div class="col-sm-3">
                        <label class="control-label">Drum Mix Cost <i class="field-required fa fa-asterisk" data-toggle="tooltip" title="@lang('translation.field_required')"></i></label>
                        <x-form-text name="cost_per_linear_feet"
                                     class="check-contact tc"
                                     placeholder="enter value"
                                     id="cost_per_linear_feet"
                                     :params="[
                    'label' => 'none',
                    'iconClass' => 'none',
                    'value' => '{{$service->cost_per_linear_feet}}',
                ]"
                        ></x-form-text>

                    </div>

                </div>
            @endif

        @endif

        @if($service->service_category_id == 3)

            <!--    3	Drainage and Catchbasins -->

            <br/>
            <div class="row">
                <div class="col-sm-5">
                    <label class="control-label">Number of Catch Basins <i class="field-required fa fa-asterisk" data-toggle="tooltip" title="@lang('translation.field_required')"></i></label>
                    <x-form-text name="catchbasins"
                                 class="check-contact tc"
                                 placeholder="enter value"
                                 id="catchbasins"
                                 :params="[
                    'label' => 'none',
                    'iconClass' => 'none',
                    'value' => '{{$service->catchbasins}}',
                ]"
                    ></x-form-text>
                    {{-- because it is an integer field 'Additive' is used in case of number of catch basins --}}

                </div>
                <div class="col-sm-4">
                    <label class="control-label">Cost <i class="field-required fa fa-asterisk" data-toggle="tooltip" title="@lang('translation.field_required')"></i></label>
                    <x-form-text name="cost_per_day"
                                 class="check-contact tc"
                                 placeholder="enter value"
                                 id="cost_per_day"
                                 :params="[
                    'label' => 'none',
                    'iconClass' => 'none',
                    'value' => '{{$service->cost_per_day}}',
                ]"
                    ></x-form-text>
                </div>

            </div>
            <br/>
            <div class="row">

                <div class="col-sm-9">
                    <label class="control-label">Job Description <i class="field-required fa fa-asterisk" data-toggle="tooltip" title="@lang('translation.field_required')"></i></label>
                    <x-form-textarea name="alt_desc"
                                     class="check-contact tc"
                                     placeholder="enter value"
                                     id="alt_desc"
                                     :params="[
                    'label' => 'none',
                    'iconClass' => 'none',
                    'value' => '{{$service->alt_desc}}',
                ]"
                    ></x-form-textarea>
                </div>
            </div>

        @endif

        @if($service->service_category_id == 4)

            <!--  4	Excavation -->

            <div class="row">
                <div class="col-sm-2 admin-form-item-widget">
                    <label class="control-label">Square Feet <i class="field-required fa fa-asterisk" data-toggle="tooltip" title="@lang('translation.field_required')"></i></label>
                    <x-form-text name="square_feet"
                                 class="check-contact"
                                 placeholder="enter value"
                                 id="square_feet"
                                 :params="[
                    'label' => 'none',
                    'iconClass' => 'none',
                ]"
                    >{{$proposalDetail->square_feet}}</x-form-text>
                </div>

                
                <div class="col-sm-2">
                    <label class='control-label'>Depth in Inches <i class="field-required fa fa-asterisk" data-toggle="tooltip" title="@lang('translation.field_required')"></i></label>

                    <x-form-text name="depth"
                                 class="check-contact tc"
                                 placeholder="enter value"
                                 id="depth"
                                 :params="[
                    'label' => 'none',
                    'iconClass' => 'none',
                ]"
                    >{{$proposalDetail->depth}}</x-form-text>
                </div>
                <div class="col-sm-2">
                    <label class='control-label'>Our Cost<i class="field-required fa fa-asterisk" data-toggle="tooltip" title="@lang('translation.field_required')"></i></label>
                    <x-form-text name="cost_per_day"
                                 class="check-contact tc"
                                 placeholder="enter value"
                                 id="cost_per_day"
                                 :params="[
                    'label' => 'none',
                    'iconClass' => 'none',
                ]"
                    >{{$proposalDetail->cost_per_day}}</x-form-text>
                </div>
                <div class="col-sm-2">
                    <x-form-show
                            class="w180 show-check-contact"
                            :params="[
                    'label' => 'Loads',
                           'placeholder'=>'calculated',
                                    'name'=>'loads',
                                    'id'=>'loads'
                    ]">{{$proposalDetail->loads}}</x-form-show>
                </div>
                <div class="col-sm-2">
                    <x-form-show
                            class="w180 show-check-contact"
                            :params="[
                           'placeholder'=>'calculated',
                    'label' => 'Tons',
                    'name'=>'ton',
                    'id'=>'tons'
                    ]">{{$proposalDetail->tons}}
                    </x-form-show>
                </div>


            </div>

            <br/>

        @endif


        @if($service->service_category_id == 5)

            <!--  Other -->


            <div class="row">

                <div class="col-sm-4">
                    <x-form-text name="cost_per_day"
                                 class="check-contact tc"
                                 placeholder="enter value"
                                 id="cost_per_day"
                                 :params="[
                    'label' => 'Our Cost',
                    'iconClass' => 'none',
                    'value' => '{{$service->cost_per_day}}',
                ]"
                    ></x-form-text>
                </div>

                <div class="col-sm-4">
                    <x-form-text name="locations"
                                 class="check-contact tc"
                                 placeholder="enter value"
                                 id="locations"
                                 :params="[
                    'label' => 'Locations',
                    'iconClass' => 'none',
                    'value' => '{{$service->locations}}',
                ]"
                    ></x-form-text>
                </div>

            </div>
            <div class="row">

                <div class="col-sm-10">
                    <x-form-textarea name="alt_desc"
                                     class="check-contact tc"
                                     placeholder="enter value"
                                     id="alt_desc"
                                     :params="[
                    'label' => 'Job Description',
                    'iconClass' => 'none',
                    'value' => '{{$service->alt_desc}}',
                ]"
                    ></x-form-textarea>
                </div>

            </div>

        @endif

        @if($service->service_category_id == 6)

            <!--  Paver Brick -->


            <br/>
            <div class="row">
                <div class="col-sm-4">
                    <x-form-text name="square_feet"
                                 class="check-contact tc"
                                 placeholder="enter value"
                                 id="square_feet"
                                 :params="[
                    'label' => 'Square Feet',
                    'iconClass' => 'none',
                    'value' => '{{$service->square_feet}}',
                ]"
                    ></x-form-text>

                </div>
                <div class="col-sm-4">
                    <x-form-text name="cost_per_day"
                                 class="check-contact tc"
                                 placeholder="enter value"
                                 id="cost_per_day"
                                 :params="[
                    'label' => 'Cost',
                    'iconClass' => 'none',
                    'value' => '{{$service->cost_per_day}}',
                ]"
                    ></x-form-text>
                </div>
                <div class="col-sm-4">
                    <div class='check-contact tc' id="tonnage"></div>
                    <x-form-text name="tons" id="tons"
                                 class="check-contact tc"
                                 placeholder="enter value"
                                 id="tons"
                                 :params="[
                             'hint' => 'This field is calculated',
                    'label' => 'Excavate Tons',
                    'iconClass' => 'none',
                    'value' => '{{$service->tons}}',
                ]"
                    ></x-form-text>
                </div>

            </div>
            <br/>
            <div class="row">

                <div class="col-sm-9">
                    <label class="control-label">Job Description</label>
                    <x-form-textarea name="alt_desc"
                                     class="check-contact tc"
                                     placeholder="enter value"
                                     id="alt_desc"
                                     :params="[
                    'label' => 'Job Description',
                    'iconClass' => 'none',
                    'value' => '{{$service->alt_desc}}',
                ]"
                    ></x-form-textarea>
                </div>
            </div>

        @endif
        @if($service->service_category_id == 7)

            <!--  Rock -->

            <div class="row">
                <div class="col-sm-3">
                    <x-form-text name="square_feet"
                                 class="check-contact tc"
                                 placeholder="enter value"
                                 id="square_feet"
                                 :params="[
                    'label' => 'Square Feet',
                    'iconClass' => 'none',
                    'value' => '{{$service->square_feet}}',
                ]"
                    ></x-form-text>
                </div>
                <div class="col-sm-3">
                    <x-form-text name="depth"
                                 class="check-contact tc"
                                 placeholder="enter value"
                                 id="depth"
                                 :params="[
                    'label' => 'Depth in Inches',
                    'iconClass' => 'none',
                    'value' => '{{$service->depth}}',
                ]"
                    ></x-form-text>
                </div>
                <div class="col-sm-3">
                    <x-form-show
                            class="w180 show-check-contact"
                            :params="[
                    'label' => 'Loads',
                           'placeholder'=>'calculated',
                    'value'=>'{{$service->loads}}',
                                    'name'=>'loads',
                                    'id'=>'loads'
                    ]">
                    </x-form-show>
                </div>
                <div class="col-sm-3">
                    <x-form-show
                            class="w180 show-check-contact"
                            :params="[
                           'placeholder'=>'calculated',
                    'label' => 'Tons',
                    'value'=>'{{$service->tons}}',
                    'name'=>'ton',
                    'id'=>'tons'
                    ]">
                    </x-form-show>
                </div>

            </div>
            <br/>
            <div class="row">
                <div class="col-sm-1">
                    <!-- Show rock cost -->
                    <input type="radio" id="cost_per_day" name="cost_per_day"
                           class="form-control" value="{{$materialsCB[7]}}"
                    >
                </div>

                <div class="col-sm-4">
                    <label class="control-label">Base Rock (Palm Beach)
                        {{ \App\Helpers\Currency::format($materialsCB[7] ?? '0.0') }}
                    </label>
                </div>
                <div class="col-sm-1">
                    <input type="radio" id="cost_per_day" name="cost_per_day"
                           class="form-control" value="{{$materialsCB[6]}}" checked
                    >
                </div>
                <div class="col-sm-4">
                    <label class="control-label">Base Rock (Broward & Dade)
                        {{ \App\Helpers\Currency::format($materialsCB[6] ?? '0.0') }}
                    </label>
                </div>


            </div>

        @endif

        @if($service->service_category_id == 8)

            <!--  Seal Coating -->


            <!-- row -->
            <div class="row">
                <div class="col-sm-3">
                    <div class="bordered" style='font-size:0.7EM;'>

                        <b></b>Yields<br/></b>
                        65 Very Old, Coarse Dry Lot<br/>
                        75 Old Dry Lot<br/>
                        85 Dry Lot<br/>
                        95 Previously Sealed Lot Coarse<br/>
                        105 Previously Sealed Tight<br/>
                        125 One Coat<br/>

                    </div>
                </div>
                <div class="col-sm-3">
                    <label class="control-label">Yield</label>

                    <select class="form-control" name="yield" id="yield">
                        <option value="{{$service->yield}}">{{$service->yield}}</option>
                        <option value="60">60</option>
                        <option value="65">65</option>
                        <option value="70">70</option>
                        <option value="75">75</option>
                        <option value="80">80</option>
                        <option value="85">85</option>
                        <option value="90">90</option>
                        <option value="95">95</option>
                        <option value="100">100</option>
                        <option value="105">105</option>
                        <option value="110">110</option>
                        <option value="115">115</option>
                        <option value="120">120</option>
                        <option value="125">125</option>
                        <option value="130">130</option>
                        <option value="135">135</option>
                        <option value="140">140</option>
                        <option value="145">145</option>
                    </select>
                </div>

                <div class="col-sm-3">
                    <x-form-text name="square_feet"
                                 class="check-contact tc"
                                 placeholder="enter value"
                                 id="square_feet"
                                 :params="[
                    'label' => 'Square Feet',
                    'iconClass' => 'none',
                    'value' => '{{$service->square_feet}}',
                ]"
                    ></x-form-text>
                </div>

            </div>
            <br/>

            <div class="row">
                <div class="col-sm-4">
                    <x-form-text name="primer"
                                 class="check-contact tc"
                                 placeholder="enter value"
                                 id="primer"
                                 :params="[
                    'label' => 'Oil Spot Primer (gals)',
                    'iconClass' => 'none',
                    'value' => '{{$service->primer}}',
                ]"
                    ></x-form-text>
                </div>
                <div class="col-sm-4">
                    <x-form-text name="fast_set"
                                 class="check-contact tc"
                                 placeholder="enter value"
                                 id="fast_set"
                                 :params="[
                    'label' => 'Fast Set (gals)',
                    'iconClass' => 'none',
                    'value' => '{{$service->fast_set}}',
                ]"
                    ></x-form-text>
                </div>
                <div class="col-sm-4">
                    <x-form-text name="phases"
                                 class="check-contact tc"
                                 placeholder="enter value"
                                 id="phases"
                                 :params="[
                    'label' => 'Phases',
                    'iconClass' => 'none',
                    'value' => '{{$service->phases}}',
                ]"
                    ></x-form-text>
                </div>

                {{--
    id
    proposal_id
    change_order_id
    services_id
    contractor_id
    status_id
    location_id
    fieldmanager_id
    second_fieldmanager_id
    service_name
    service_desc
    alt_desc
    linear_feet
    cost_per_linear_feet
    square_feet
    square_yards
    cubic_yards
    tons
    loads
    locations
    depth
    profit
    days
    cost_per_day
    break_even
    primer
    yield
    fast_set
    additive
    sealer
    sand
    phases
    overhead
    cost
    bill_after
    dsort
    proposal_text
    proposal_note
    proposal_field_note
    created_by
    scheduled_by
    completed_by
    completed_date
    start_date
    end_date
    created_at
    updated_at
    --}}


            </div>
            <br/>
            <div class="row">
                <div class="col-sm-3">
                    <label class="control-label">Materials Needed</label>
                </div>
                <div class="col-sm-3">

                    <x-form-text name="sealer"
                                 class="check-contact tc"
                                 placeholder="enter value"
                                 id="sealer"
                                 :params="[
                    'label' => 'Sealer',
                    'iconClass' => 'none',
                    'value' => '{{$service->sealer}}',
                ]"
                    ></x-form-text>

                </div>
                <div class="col-sm-3">
                    <x-form-text name="sand"
                                 class="check-contact tc"
                                 placeholder="enter value"
                                 id="sand"
                                 :params="[
                    'label' => 'Sand (SEALER * 2)',
                    'iconClass' => 'none',
                    'value' => '{{$service->sand}}',
                ]"
                    ></x-form-text>

                </div>
                <div class="col-sm-3">
                    <x-form-text name="additive"
                                 class="check-contact tc"
                                 placeholder="enter value"
                                 id="additive"
                                 :params="[
                    'label' => 'Additive (SEALER / 50)',
                    'iconClass' => 'none',
                    'value' => '{{$service->additive}}',
                ]"
                    ></x-form-text>
                </div>

            </div>
            <br/>

            <div class="row panel">

                <div class="row">
                    <div class="col-sm-2">
                        <label class="control-label">TOTALS </label>
                    </div>
                    <div class="col-sm-2">
                        <x-form-text name="SealerTotal"
                                     class="check-contact tc"
                                     placeholder="disabled"
                                     id="SealerTotal"
                                     :params="[
                    'label' => 'Sealer',
                    'iconClass' => 'none',
                    'value' => '',
                ]"
                        ></x-form-text>

                    </div>
                    <div class="col-sm-2">
                        <x-form-text name="SandTotal"
                                     class="check-contact tc"
                                     placeholder="disabled"
                                     id="SandTotal"
                                     :params="[
                    'label' => 'Sand',
                    'iconClass' => 'none',
                    'value' => '',
                ]"
                        ></x-form-text>


                    </div>
                    <div class="col-sm-2">
                        <x-form-text name="AdditiveTotal"
                                     class="check-contact tc"
                                     placeholder="disabled"
                                     id="AdditiveTotal"
                                     :params="[
                    'label' => 'Additive',
                    'iconClass' => 'none',
                    'value' => '',
                ]"
                        ></x-form-text>

                    </div>
                    <div class="col-sm-2">
                        <x-form-text name="PrimerTotal"
                                     class="check-contact tc"
                                     placeholder="disabled"
                                     id="PrimerTotal"
                                     :params="[
                    'label' => 'Primer',
                    'iconClass' => 'none',
                    'value' => '',
                ]"
                        ></x-form-text>

                    </div>
                    <div class="col-sm-2">
                        <x-form-text name="FastSetTotal"
                                     class="check-contact tc"
                                     placeholder="disabled"
                                     id="FastSetTotal"
                                     :params="[
                    'label' => 'FastSet',
                    'iconClass' => 'none',
                    'value' => '',
                ]"
                        ></x-form-text>
                    </div>

                </div>


                <div class="row">
                    <div class="col-sm-2">
                        <label class="control-label">COST</label>
                    </div>
                    <div class="col-sm-2">
                        <x-form-text name="SealerCost"
                                     class="check-contact tc"
                                     placeholder="disabled"
                                     id="SealerCost"
                                     :params="[
                    'label' => 'Sealer Cost',
                    'iconClass' => 'none',
                    'value' => '',
                ]"
                        ></x-form-text>
                    </div>
                    <div class="col-sm-2">
                        <x-form-text name="SandCost"
                                     class="check-contact tc"
                                     placeholder="disabled"
                                     id="SandCost"
                                     :params="[
                    'label' => 'Sand Cost',
                    'iconClass' => 'none',
                    'value' => '',
                ]"
                        ></x-form-text>
                    </div>
                    <div class="col-sm-2">
                        <x-form-text name="AdditiveCost"
                                     class="check-contact tc"
                                     placeholder="disabled"
                                     id="AdditiveCost"
                                     :params="[
                    'label' => 'Additive Cost',
                    'iconClass' => 'none',
                    'value' => '',
                ]"
                        ></x-form-text>
                    </div>
                    <div class="col-sm-2">
                        <x-form-text name="PrimerCost"
                                     class="check-contact tc"
                                     placeholder="disabled"
                                     id="PrimerCost"
                                     :params="[
                    'label' => 'Primer Cost',
                    'iconClass' => 'none',
                    'value' => '',
                ]"
                        ></x-form-text>
                    </div>
                    <div class="col-sm-2">
                        <x-form-text name="FastSetCost"
                                     class="check-contact tc"
                                     placeholder="disabled"
                                     id="FastSetCost"
                                     :params="[
                    'label' => 'FastSet Cost',
                    'iconClass' => 'none',
                    'value' => '',
                ]"
                        ></x-form-text>

                    </div>

                </div>

            </div>

        @endif

        @if($service->service_category_id == 9)

            <!--  striping -->
            You are on the wrong page!
        @endif

        @if($service->service_category_id == 10)

            <!--  Sub Contractor -->
            <div class="row">
                <div class="col-sm-6">
                    <x-form-select name="contractor_id"
                                   :items="$contractorsCB"
                                   selected=""
                                   :params="['label' => 'Contractor', 'required' => true]"
                    ></x-form-select>
                </div>
                <div class="col-sm-6">
                    <x-form-text name="cost_per_day"
                                 class="check-contact tc"
                                 placeholder="enter value"
                                 id="cost_per_day"
                                 :params="[
                    'label' => 'Cost',
                    'iconClass' => 'none',
                    'value' => '{{$service->cost_per_day}}',
                ]"
                    ></x-form-text>

                </div>

            </div>


            <div class="row">
                <div class="col-sm-6">
                    <x-form-textarea name="alt_desc"
                                     class="check-contact tc"
                                     placeholder="enter value"
                                     id="alt_desc"
                                     :params="[
                    'label' => 'Job Description',
                    'iconClass' => 'none',
                    'value' => '{{$service->alt_desc}}',
                ]"
                    ></x-form-textarea>
                </div>
                <!-- @todo what overhead is this -->
                <div class="col-sm-3">
                    <x-form-text name="contractor_overhead"
                                 class="check-contact tc"
                                 placeholder="disabled"
                                 id="contractor_overhead"
                                 :params="[
                    'label' => 'Contractor Over Head %',
                    'iconClass' => 'none',
                    'value' => '{{$service->overhead}}',
                ]"
                    ></x-form-text>
                </div>

                <div class="col-sm-3">
                    <x-form-text name="boh"
                                 class="check-contact tc"
                                 placeholder="disabled"
                                 id="boh"
                                 :params="[
                'label' => 'Sub Over Head %',
                'iconClass' => 'none',
                'value' => '',
                ]"
                    ></x-form-text>

                </div>

            </div>

        @endif


        @if($proposal->progressive_billing)
            <div class="row card-body">
                <div class="col-sm-8">
                    <strong> Progressive Billing:</strong> <br/>Do you want to bill the customer after this service is
                    completed?
                </div>
                <div class="col-sm-2">
                    <div class="admin-form-item-widget">
                        YES <input type="radio" class="form-control" name="bill_after" id="bill_after1" value="1"
                                   @if($proposalDetail->bill_after) checked @endif >
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="admin-form-item-widget">
                        NO <input type="radio" class="form-control" name="bill_after" id="bill_after2" value="0"
                                  @if(!$proposalDetail->bill_after) checked @endif >
                    </div>
                </div>
            </div>
        @else
            <input type="hidden" name="bill_after" value="0">
        @endif
        
    </form>

</div>

@push('partials-scripts')
    <script>

        $(document).ready(function () {

            // when the page loads we may need to repeat some calculations to determine total costs
            // and populate other display items on the page


            function calculate(cost_form, estimatorForm, services_id, proposal_detail_id, proposal_id, serviceCategoryId, dosave) {
                //cost_form has data used to calculate costs,
                //estimator form gets hidden values filled in and data gets sent via ajax on save
                //what service did we pick
                //alert(services_id);
                //alert(serviceCategoryName);
                //The Math.ceil() static method always rounds up and returns the smaller integer
                // greater than or equal to a given number.
                var estimatorform = $("#estimator_form");
                var profit = $("#form_header_profit").val();
                var overhead = $("#form_header_over_head").val();
                var breakeven = $("#form_header_break_even").val();
                var regex = "/^[0-9]+$/";  // numbers only
                var mcost = 0;


                if(parseFloat(profit) == 'NaN' || parseFloat(overhead) == 'NaN' ||parseFloat(breakeven) == 'NaN'){
                    showInfoAlert('You can only enter numbers for profit, overhead and break even.', headerAlert);
                };




                if (serviceCategoryId == 1) {

                    {{-- asphalt --}}
                    if (proposalDetailId == 19) {
                        {{-- Asphalt Milling --}}

                    } else {


                    }
                }

                {{-- END of Asphalt --}}

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

                    var square_feet = $("#square_feet").val();
                    var depth = $("#depth").val();
                    var ourcost = $("#cost_per_day").val();
                    
                    if (parseFloat(square_feet) == 'NaN' || parseFloat(depth) == 'NaN') { // check these are numbers
                        showInfoAlert('You can only enter numbers for square feet and depth.', headerAlert);
                        //return;
                    }

                    if (square_feet > 0 && depth > 0) {

                        var tontimes = (7 / 1080);
                        var tons = Math.ceil(square_feet * depth * tontimes);
                        var loads = Math.ceil(tons / 18);

                        $("#loads").text(loads);
                        $("#tons").text(tons);
                        var mcost = parseFloat(ourcost, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,").toString();
                        $("#header_show_materials_cost").text('$' + mcost);
                        
                        var bill_after = $('input[name="bill_after"]:checked').val();
                        var str = "{!! $service->service_text_en !!}";
                        var newstr = str.replace('@@TONS@@', tons);

                        var proposaltext = tinymce.activeEditor.getContent();
                            // ok so set square_feet, cost, loads, tons, depth, bill_after, profit, break_even, location_id, overhead, toncost, proposal_text
                        
                        //add it up
                        additup(mcost);

                        // set all relevant form values for update
                        $("#x_square_feet").val(square_feet);
                        $("#x_depth").val(depth);
                        $("#x_loads").val(loads);
                        $("#x_tons").val(tons);
                        $("#x_toncost").val(0);
                        $("#x_cost_per_day").val(ourcost);
                        
                        
                    }


                }
                {{-- END	Excavation --}}


                if (serviceCategoryId == 5) {

                    {{--  Other --}}


                }
                {{-- end Other --}}

                if (serviceCategoryId == 6) {

                    {{--  Paver Brick --}}

                }

                if (serviceCategoryId == 7) {
                    {{--  Rock --}}


                    var square_feet = $("#square_feet").val();
                    var depth = $("#depth").val();
                    var rockcost = $('input[name="cost_per_day"]:checked').val();

                    alert(depth);


                    if (!square_feet.match(regex) || !depth.match(regex)) { // check these are numbers
                        showInfoAlert('You can only enter numbers for square feet and depth.', headerAlert);

                        setTimeout(() => {
                            closeAlert(headerAlert);
                        }, 2000);

                        return;
                    }

                    if (square_feet > 0 && depth > 0) {

                        var str = "{!! $service->service_text_en !!}";

                        var newstr = str.replace('@@INCHES@@',depth);

                        $("#proposaltext").val(newstr);

                        var tontimes = (7 / 1080);
                        var tons = Math.ceil(square_feet * depth * tontimes);
                        var loads = Math.ceil(tons / 18);

                        $("#loads").text(loads);
                        $("#tons").text(tons);
                        //alert('Materials =' + tons + ' * ' + rockcost );
                        materials = (tons * rockcost);
                        var mcost = parseFloat(materials, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,").toString();
                        $("#header_show_materials_cost").text('$' + mcost);
                        $("#x_square_feet").val(square_feet);
                        $("#x_depth").val(depth);
                        $("#x_tons").val(tons);
                        $("#x_loads").val(loads);

                        alert("Loads = Tons / 18 and Tons = Square feet * depth * " + tontimes);
                        //$("#header_show_materials_cost").text('$' + ourcost);


                        //var profit = $("#profit").val();
                        //alert('tons ='.tons);

                    }


                }


                if (serviceCategoryId == 8) {

                    {{--  Seal Coating  these are the user imput fields that need to be filled in validated--}}

                        square_feet = $("#square_feet").val();
                    primer = $("#primaer").val();
                    fastset = $("#fastset").val();
                    if (square_feet == parseInt(square_feet)
                        && primer == parseInt(primer)
                        && fastset == parseInt(fastset)) {

                        var yield = $("#yield").val();


                        //calculate amounts

                        /*
                         SEALER  = Size/Yield  = GAL SEALER
                         AND SAND = GAL SEALER * 2
                         ADDITIVE = AND GAL SEALER / 50
                         */


                        var sand = Math.ceil(sealer * 2);
                        $("#sand").val(sand);

                        var sealer = Math.ceil(square_feet / yield);
                        $("#sealer").val(sealer);

                        var additive = Math.ceil(sealer / 50);
                        $("#additive").val(additive);


                        var sandtotal = Math.ceil(parseFloat(sandcost) * parseFloat(sand));
                        var fastsettotal = Math.ceil(parseFloat(fastsetcost) * parseFloat(fastset));
                        var primertotal = Math.ceil(parseFloat(primercost) * parseFloat(primer));
                        var additivetotal = Math.ceil(parseFloat(additivecost) * parseFloat(additive));
                        var sealertotal = Math.ceil(parseFloat(sealercost) * parseFloat(sealer));

                        $("#SandTotal").val('$' + sandtotal.toFixed(2));
                        cost_form.FastSetTotal.value = '$' + fastsettotal.toFixed(2);
                        cost_form.SealerTotal.value = '$' + sealertotal.toFixed(2);
                        cost_form.PrimerTotal.value = '$' + primertotal.toFixed(2);
                        cost_form.AdditiveTotal.value = '$' + additivetotal.toFixed(2);

                        var subtotal = Math.ceil(
                            parseFloat(sandtotal) +
                            parseFloat(fastsettotal) +
                            parseFloat(primertotal) +
                            parseFloat(additivetotal) +
                            parseFloat(sealertotal)
                        );

                        $("#mcost").val(subtotal);

                        //total up
                        var combinedcost = parseFloat($("#POVTotal").val()) + parseFloat($("#POequipTotal").val()) + parseFloat($("#POlaborTotal").val()) + parseFloat($("#POOtherTotal").val()) + parseFloat(cost_form.mcost.value);

                        var otcost = Math.ceil(parseFloat(combinedcost) + parseFloat(profit));
                        var overhead = Math.ceil((otcost / 0.7) - otcost);
                        $("#explain").html('calculated at 30%');
                        //var overhead = Math.ceil((parseFloat(combinedcost) +  parseFloat(profit)) * 30)/100;


                        var str = cost_form.jordProposalText.value;
                        var newstr = str.replace('@@SQFT@@', cost_form.jordSquareFeet.value);
                        var newstr = newstr.replace('@@PHASES@@', cost_form.jordPhases.value);
                        cost_form.jordProposalText.value = newstr;


                    }


                }

                if (serviceCategoryId == 9) {

                    {{--  striping  not used for this service--}}

                }

                if (serviceCategoryId == 10) {

                    {{--  Sub Contractor --}}
                }

                //set these fields for all services
                $("#x_proposal_text").val(proposaltext);
                $("#x_bill_after").val(bill_after);
                $("#x_profit").val(headerElProfit.val());
                $("#x_break_even").val(headerElBreakEven.val());
                $("#x_overhead").val(headerElOverHead.val());

                //then save it
                if(dosave) {
                    //saveit();
                }
                
            }


            var cost_form = $("#cost_formula_form");  // values to determine cost
            var estimatorForm = $("#estimator_form"); // form to set values for submit and save
            
            
            // when page loads we need to calculate some things without saving
            //calculate(cost_form, estimatorForm, serviceId, proposalDetailId, proposalId, serviceCategoryId, false);

            // when you want to calculate and save record 
            headerCalculateCombinedCostingButton2.on('click', function () {

                calculate(cost_form, estimatorForm, serviceId, proposalDetailId, proposalId, serviceCategoryId, true);

            });


            function additup(mcost)
            {
  
                var combinedcost = parseFloat($('#estimator_form_subcontractor_total_cost').val()); 
                    //+ parseFloat($('#estimator_form_equipment_total_cost').val()) + parseFloat($('#estimator_form_labor_total_cost').val()) + parseFloat($('#estimator_form_additional_cost_total_cost').val()) + parseFloat($('#estimator_form_vehicle_total_cost').val()) + parseFloat($("#cost_per_day").val());
                var othercost = parseFloat($('#form_header_over_head').val()) + parseFloat($('#form_header_break_even').val()) + parseFloat($('#form_header_profit').val());
                console.log('combined ' + combinedcost);
                console.log('other: ' + othercost);
                combinedcost = parseFloat(combinedcost).toFixed(2);
                headerElCombinedCosting.text('$' + combinedcost);
                $("#x_cost").val(combinedcost);
                headerElCustomerPrice.text('$' + parseFloat(combinedcost + othercost).toFixed(2));

            }

            function saveit()
            {
                $("#estimator_form").submit();

            }

            
        });

    </script>
@endpush
