<div class="mt20 mb10">
    <form action="#" id="cost_formula_form" class="custom-validation">


        {{-- This form is for reference to calculate costs it is never submitted --}}

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
                    18	9	Pavement Marking
                    17	10	Any Sub Contractor

                    --}}
        <div class="mt20 mb10">

            @if($service->service_category_id == 1)

                <!-- asphalt -->

                @if($proposalDetail->services_id == 19)
                    {{-- Asphalt Milling --}}
                    <div class="row">
                        <div class="col-sm-3">
                            <x-form-text name="square_feet"
                                         class="check-contact tc"
                                         placeholder="enter value"
                                         data-parsley-type="number"
                                         id="square_feet"
                                         :params="[
                                    'label' => 'Size of project in SQ FT',
                                    'iconClass' => 'none',
                                    'required' => 'true',
                                ]">{{$proposalDetail->square_feet}}</x-form-text>
                        </div>
                        <div class="col-sm-2">
                            <x-form-text name="depth"
                                         class="check-contact tc"
                                         placeholder="enter value"
                                         id="depth"
                                         :params="[
                                    'label' => 'Depth In Inches',
                                    'iconClass' => 'none',
                                    'required' => 'true',
                                ]">{{$proposalDetail->depth}}</x-form-text>
                        </div>
                        <div class="col-sm-3">
                            <x-form-text name="days"
                                         class="check-contact tc"
                                         placeholder="enter value"
                                         id="days"
                                         :params="[
                                        'label' => 'Days of Milling',
                                        'iconClass' => 'none',
                                        'required' => 'true',
                                ]">{{$proposalDetail->days}}</x-form-text>

                        </div>

                        <div class="col-sm-2">
                            <x-form-text name="cost_per_day"
                                         class="check-contact tc"
                                         placeholder="enter value"
                                         id="cost_per_day"
                                         :params="[
                                    'label' => 'Cost Per Day',
                                    'iconClass' => 'none',
                                    'required' => 'true',
                                ]">{{$proposalDetail->cost_per_day}}</x-form-text>

                        </div>

                        <div class="col-sm-2">
                            <x-form-text name="locations"
                                         class="check-contact tc"
                                         placeholder="enter value"
                                         id="locations"
                                         :params="[
                                    'label' => 'Locations',
                                    'iconClass' => 'none',
                                    'required' => 'true',
                            ]">{{$proposalDetail->locations}}</x-form-text>

                        </div>

                    </div>
                    <br/>
                    <div class="row">
                        <div class="col-sm-4">
                            <x-form-show
                                    class="w180 show-check-contact"
                                    :params="[
                                    'placeholder'=>'calculated',
                                    'label' => 'Square Yards',
                                    'name'=>'square_yards',
                                    'id'=>'square_yards'
                    ]">{{$proposalDetail->square_yards}}</x-form-show>


                        </div>
                        <div class="col-sm-4">
                            <x-form-show
                                    class="w180 show-check-contact"
                                    :params="[
                                    'placeholder'=>'calculated',
                                    'label' => 'Loads',
                                    'name'=>'loads',
                                    'id'=>'loads'
                    ]">{{$proposalDetail->loads}}</x-form-show>
                        </div>

                        <div class="col-sm-4">
                            &nbsp;
                        </div>

                    </div>

                @else
                    {{-- * all other types --}}

                    <br/>
                    <div class="row">

                        <div class="col-sm-3">
                            <x-form-text name="square_feet"
                                         class="check-contact tc"
                                         placeholder="enter value"
                                         id="square_feet"
                                         :params="[
                                    'label' => 'Square Feet',
                                    'iconClass' => 'none',
                                    'required' => 'true',
                            ]">{{$proposalDetail->square_feet}}
                            </x-form-text>
                        </div>
                        <div class="col-sm-3">
                            <x-form-text name="depth"
                                         class="check-contact tc"
                                         placeholder="enter value"
                                         id="depth"
                                         :params="[
                                    'label' => 'Depth in Inches',
                                    'iconClass' => 'none',
                                    'required' => 'true',
                            ]">{{$proposalDetail->depth}}
                            </x-form-text>
                        </div>

                        <div class="col-sm-3">
                            <label class="control-label">Asphalt Cost <i class="field-required fa fa-asterisk"
                                                                         data-toggle="tooltip"
                                                                         title="@lang('translation.field_required')"></i></label>
                            <select class="form-control" required name="cost_per_day" id="cost_per_day">
                                <option value='0'>Select Asphalt Type</option>
                                @foreach($asphaltMaterials as $materials)
                                    <option value='{{$materials['cost']}}'
                                            @if($materials['name'] == $proposalDetail->materials_name)
                                                selected
                                            @endif
                                    >{{$materials['name']}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <x-form-text name="locations"
                                         class="check-contact tc"
                                         placeholder="enter value"
                                         id="locations"
                                         :params="[
                                    'label' => 'Locations',
                                    'iconClass' => 'none',
                                    'required' => 'true',
                ]"
                            >{{$proposalDetail->locations}}</x-form-text>

                        </div>

                    </div>
                    <br/>
                    <div class="row">
                        <div class="col-sm-3">
                            <x-form-show
                                class="w180 show-check-contact"
                                :params="[
                                    'label' => 'Tack Cost',
                                    'placeholder'=>'0',
                                    'name'=>'tack_cost',
                                    'id'=>'TackCost'
                    ]"></x-form-show>

                        </div>
                        <div class="col-sm-3">
                            <x-form-show
                                class="w180 show-check-contact"
                                :params="[
                                    'label' => 'Asphalt Cost',
                                    'placeholder'=>'0',
                                    'name'=>'asphalt_cost',
                                    'id'=>'TonCost'
                    ]"></x-form-show>

                        </div>
                        <div class="col-sm-3">
                            <x-form-show
                                    class="w180 show-check-contact"
                                    :params="[
                                    'placeholder'=>'calculated',
                                    'label' => 'Square Yards',
                                    'name'=>'square_yards',
                                    'id'=>'square_yards'
                    ]">{{$proposalDetail->square_yards}}</x-form-show>

                        </div>
                        <div class="col-sm-3">
                            <x-form-show
                                    class="w180 show-check-contact"
                                    :params="[
                                    'placeholder'=>'calculated',
                                    'label' => 'Tons',
                                    'name'=>'tons',
                                    'id'=>'tons'
                    ]">{{$proposalDetail->tons}}</x-form-show>

                        </div>
                    </div>

                @endif

            @endif
            {{-- END of Asphalt --}}

            @if($service->service_category_id == 2)

                <!-- concrete -->

                {{--IF $details.cmpServiceID < 12- *curb mix* --}}
                @if($service->id < 12)

                    <div class="row">
                        <div class="col-sm-3">
                            <x-form-text name="linear_feet"
                                         class="check-contact tc"
                                         placeholder="enter value"
                                         id="linear_feet"
                                         :params="[
                                    'label' => 'Length In Feet (linear feet)',
                                    'required' => 'true',
                                    'iconClass' => 'none',
                                ]">{{$proposalDetail->linear_feet}}</x-form-text>
                        </div>

                        <div class="col-sm-3">
                            <x-form-text name="locations"
                                         class="check-contact tc"
                                         placeholder="enter value"
                                         id="locations"
                                         :params="[
                                    'required' => 'true',
                                    'label' => 'Locations',
                                    'iconClass' => 'none',
                                ]">{{$proposalDetail->locations}}</x-form-text>
                        </div>
                        <div class="col-sm-3">
                            <x-form-show
                                    class="w180 show-check-contact"
                                    :params="[
                                    'name'=>'cubic_yards',
                                    'label' => 'Cubic Yards',
                                    'id'=>'cubic_yards'
                            ]">{{$proposalDetail->cubic_yards}}
                            </x-form-show>
                        </div>
                        <div class="col-sm-3">
                            <x-form-show
                                    class="w180 show-check-contact"
                                    :params="[
                                    'label' => 'Curb Mix (per cubic yard)',
                                    'name'=>'cost_per_linear_feet',
                                    'id'=>'cost_per_linear_feet',
                        ]">{{ \App\Helpers\Currency::format($materialsCB[9] ?? '0.0') }}
                            </x-form-show>
                        </div>

                    </div>

                @elseif ($service->id >= 12)

                    {{--IF $details.cmpServiceID >= 12-- *drum mix* --}}
                    <br/>
                    <div class="row">
                        <div class="col-sm-2">
                            <x-form-text name="square_feet"
                                         class="check-contact tc"
                                         placeholder="enter value"
                                         id="square_feet"
                                         :params="[
                                        'label' => 'Square Feet',
                                        'iconClass' => 'none',
                                        'required' => 'true',
                                      ]">{{$proposalDetail->square_feet}}</x-form-text>
                        </div>
                        <div class="col-sm-2">
                            <x-form-text name="depth"
                                         class="check-contact tc"
                                         placeholder="enter value"
                                         id="depth"
                                         :params="[
                                    'label' => 'Depth (inches)',
                                    'iconClass' => 'none',
                                    'required' => 'true',
                                    ]"
                            >{{$proposalDetail->depth}}
                            </x-form-text>
                        </div>
                        <div class="col-sm-2">
                            <x-form-text name="locations"
                                         class="check-contact tc"
                                         placeholder="enter value"
                                         id="locations"
                                         :params="[
                                        'label' => 'Locations',
                                        'iconClass' => 'none',
                                        'required' => 'true',
                                        ]"
                            >{{$proposalDetail->locations}}
                            </x-form-text>
                        </div>
                        <div class="col-sm-3">
                            <x-form-show
                                    class="w180 show-check-contact"
                                    name="cubic_yards"
                                    id="cubic_yards"
                                    :params="[
                                'label' => 'Cubic Yards',
                                'iconClass' => 'none',
                            ]">{{$proposalDetail->cubic_yards}}</x-form-show>
                        </div>
                        <div class="col-sm-3">
                            <x-form-show
                                    class="w180 show-check-contact"
                                    name="cost_per_linear_feet"
                                    id="cost_per_linear_feet"
                                    :params="[
                                'label' => 'Drum Mix Cost',
                                'iconClass' => 'none',
                            ]">{{ \App\Helpers\Currency::format($materialsCB[10] ?? '0.0') }}</x-form-show>

                        </div>

                    </div>
                @endif

            @endif

            @if($service->service_category_id == 3)

                <!--    3	Drainage and Catchbasins -->

                <br/>
                <div class="row">
                    <div class="col-sm-5">
                        <x-form-text name="catchbasins"
                                     class="check-contact tc"
                                     placeholder="enter value"
                                     id="catchbasins"
                                     :params="[
                                    'label' => 'Catchbasins',
                                    'iconClass' => 'none',
                                    'required' => 'true',
                                ]">{{$proposalDetail->catchbasins}}</x-form-text>
                    </div>
                    <div class="col-sm-4">
                        <x-form-text name="cost_per_day"
                                     class="check-contact tc"
                                     placeholder="enter value"
                                     id="cost_per_day"
                                     :params="[
                                    'label' => 'Cost Per Day',
                                    'iconClass' => 'none',
                                    'required' => 'true',
                                ]">{{$proposalDetail->cost_per_day}}</x-form-text>
                    </div>

                </div>
                <br/>
                <div class="row">
                    <div class="col-sm-9">
                        <x-form-textarea name="alt_desc"
                                         class="check-contact tc"
                                         placeholder="enter value"
                                         id="alt_desc"
                                         :params="[
                                    'label' => 'Job Description',
                                    'iconClass' => 'none',
                                    'required' => 'true',
                                ]">{{$proposalDetail->alt_desc}}</x-form-textarea>
                    </div>
                </div>

            @endif

            @if($service->service_category_id == 4)

                <!--  4	Excavation -->

                <div class="row">
                    <div class="col-sm-2 admin-form-item-widget">
                        <x-form-text name="square_feet"
                                     class="check-contact"
                                     placeholder="enter value"
                                     id="square_feet"
                                     :params="[
                                    'label' => 'Square Feet',
                                    'iconClass' => 'none',
                                    'required' => 'true',
                                ]">{{$proposalDetail->square_feet}}</x-form-text>
                    </div>


                    <div class="col-sm-2">
                        <x-form-text name="depth"
                                     class="check-contact tc"
                                     placeholder="enter value"
                                     id="depth"
                                     :params="[
                                    'label' => 'Depth in Inches',
                                    'required' => 'true',
                                    'iconClass' => 'none',
                                ]">{{$proposalDetail->depth}}</x-form-text>
                    </div>
                    <div class="col-sm-2">
                        <x-form-text name="cost_per_day"
                                     class="check-contact tc"
                                     placeholder="enter value"
                                     id="cost_per_day"
                                     :params="[
                                    'label' => 'Our Cost',
                                    'required' => 'true',
                                    'iconClass' => 'none',
                            ]">{{$proposalDetail->cost_per_day}}</x-form-text>
                    </div>
                    <div class="col-sm-2">
                        <x-form-show
                                class="w180 check-contact tc"
                                :params="[
                                'id'=>'loads',
                                'label' => 'Loads',
                                'placeholder'=>'calculated',
                                'name'=>'loads',
                    ]">{{$proposalDetail->loads}}</x-form-show>
                    </div>
                    <div class="col-sm-2">
                        <x-form-show
                            class="w180 check-contact tc"
                            :params="[
                                'id'=>'tons',
                                'placeholder'=>'calculated',
                                'label' => 'Tons',
                                'name'=>'tons',
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
                    'required' => 'true',
                    'label' => 'Our Cost',
                    'iconClass' => 'none',
                ]"
                        >{{$proposalDetail->cost_per_day}}</x-form-text>
                    </div>

                    <div class="col-sm-4">
                        <x-form-text name="locations"
                                     class="check-contact tc"
                                     placeholder="enter value"
                                     id="locations"
                                     :params="[
                    'label' => 'Locations',
                    'iconClass' => 'none',
                    'required' => 'true',
                ]"
                        >{{$proposalDetail->locations}}</x-form-text>
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
                    'required' => 'true',
                ]"
                        >{{$proposalDetail->alt_desc}}</x-form-textarea>
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
                    'required' => 'true',
                    'iconClass' => 'none',
                ]"
                        >{{$proposalDetail->square_feet}}</x-form-text>

                    </div>
                    <div class="col-sm-4">
                        <x-form-text name="cost_per_day"
                                     class="check-contact tc"
                                     placeholder="enter value"
                                     id="cost_per_day"
                                     :params="[
                    'label' => 'Cost',
                    'required' => 'true',
                    'iconClass' => 'none',
                ]"
                        >{{$proposalDetail->cost_per_day}}</x-form-text>
                    </div>
                    <div class="col-sm-4">
                        <div class='check-contact tc' id="tonnage"></div>
                        <x-form-text name="tons" id="tons"
                                     class="check-contact tc"
                                     placeholder="tons"
                                     id="tons"
                                     :params="[
                            'required' => 'true',
                            'label' => 'Excavate Tons',
                            'iconClass' => 'none',
                        ]">{{$proposalDetail->tons}}</x-form-text>
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
                                'required' => 'true',
                                'iconClass' => 'none',
                                ]">{{$proposalDetail->alt_desc}}</x-form-textarea>
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
                                    'required' => 'true',
                            ]">{{$proposalDetail->square_feet}}
                        </x-form-text>
                    </div>
                    <div class="col-sm-3">
                        <x-form-text name="depth"
                                     class="check-contact tc"
                                     placeholder="enter value"
                                     id="depth"
                                     :params="[
                    'label' => 'Depth in Inches',
                    'iconClass' => 'none',
                     ]"
                        >{{$proposalDetail->depth}}
                        </x-form-text>
                    </div>
                    <div class="col-sm-3">
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
                    <div class="col-sm-3">
                        <x-form-show
                                class="w180 show-check-contact"
                                :params="[
                             'label' => 'Loads',
                             'placeholder'=>'calculated',
                                    'name'=>'loads',
                                    'id'=>'loads'
                    ]">{{$proposalDetail->loads}}
                        </x-form-show>
                    </div>

                </div>
                <br/>
                <div class="row">
                    <div class="col-sm-1">
                        <!-- Show rock cost -->

                        <input type="radio" id="cost_per_day" name="cost_per_day"
                               class="form-control" value="{{$materialsCB[7]}}"
                               @if($materialsCB[7] == $proposalDetail->cost_per_day)
                                   checked
                                @endif
                        >
                    </div>

                    <div class="col-sm-4">
                        <label class="control-label">Base Rock (Palm Beach)
                            {{ \App\Helpers\Currency::format($materialsCB[7] ?? '0.0') }}
                        </label>
                    </div>
                    <div class="col-sm-1">
                        <input type="radio" id="cost_per_day" name="cost_per_day"
                               class="form-control" value="{{$materialsCB[6]}}"
                               @if($materialsCB[7] != $proposalDetail->cost_per_day)
                                   checked
                                @endif

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
                        <label class="form-field-label">Yield<i class="field-required fa fa-asterisk"
                                                                data-toggle="tooltip"
                                                                title="@lang('translation.field_required')"></i></label>
                        <select class="form-control" name="yield" id="yield">
                            <option value="{{$proposalDetail->yield}}">{{$proposalDetail->yield}}</option>
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
                        <label class="form-field-label">Square Feet <i class="field-required fa fa-asterisk"
                                                                       data-toggle="tooltip"
                                                                       title="@lang('translation.field_required')"></i></label>
                        <x-form-text name="square_feet"
                                     class="check-contact tc"
                                     placeholder="enter value"
                                     id="square_feet"
                                     :params="[
                    'label' => 'none',
                    'iconClass' => 'none',
                ]"
                        >{{$proposalDetail->square_feet}}</x-form-text>
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
                    'required' => 'true',
                    'iconClass' => 'none',
                   ]"
                        >{{$proposalDetail->primer}}</x-form-text>
                    </div>
                    <div class="col-sm-4">
                        <x-form-text name="fast_set"
                                     class="check-contact tc"
                                     placeholder="enter value"
                                     id="fast_set"
                                     :params="[
                    'label' => 'Fast Set (gals)',
                    'required' => ' true',
                    'iconClass' => 'none',
                ]"
                        >{{$proposalDetail->fast_set}}</x-form-text>
                    </div>
                    <div class="col-sm-4">
                        <x-form-text name="phases"
                                     class="check-contact tc"
                                     placeholder="enter value"
                                     id="phases"
                                     :params="[
                    'label' => 'Phases',
                    'required' => 'true',
                    'iconClass' => 'none',
                ]"
                        >{{$proposalDetail->phases}}</x-form-text>
                    </div>

                </div>
                <br/>
                <div class="row">
                    <div class="col-lg-3">
                        <h5>Materials Needed</h5>
                    </div>
                    <div class="col-lg-3">
                        <x-form-show name="sealer"
                                     class="check-contact tc"
                                     placeholder="calculated"
                                     id="sealer"
                                     :params="[
                               'label' => 'Sealer (gals)',
                               'iconClass' => 'none',
                        ]">{{$proposalDetail->sealer}}</x-form-show>
                    </div>
                    <div class="col-lg-3">
                        <x-form-show name="sand"
                                     class="check-contact tc"
                                     placeholder="calculated"
                                     id="sand"
                                     :params="[
                            'label' => 'Sand ',
                            'iconClass' => 'none',
                        ]">{{$proposalDetail->sand}}</x-form-show>
                    </div>
                    <div class="col-lg-3">
                        <x-form-show name="additive"
                                     class="check-contact tc"
                                     placeholder="calculated"
                                     id="additive"
                                     :params="[
                            'label' => 'Additive (sealer/50)',
                            'iconClass' => 'none',
                        ]">{{$proposalDetail->additive}}</x-form-show>
                    </div>

                </div>



                <div class="row">
                    <div class="col-2">
                        <label class="control-label">COST</label>
                    </div>
                    <div class="col-sm-2">
                            <span id="SealerCost">Sealer:{{ \App\Helpers\Currency::format($materialsCB[1] ?? '0.0') }}
                            </span>
                    </div>
                    <div class="col-2">
                        <span id="SandCost">Sand:{{ \App\Helpers\Currency::format($materialsCB[2] ?? '0.0') }}</span>
                    </div>
                    <div class="col-2">
                        <span id="AdditiveCost">Additive:{{ \App\Helpers\Currency::format($materialsCB[3] ?? '0.0') }}</span>
                    </div>
                    <div class="col-sm-2">
                        <span id="PrimerCost">Primaer:{{ \App\Helpers\Currency::format($materialsCB[4] ?? '0.0') }}</span>
                    </div>
                    <div class="col-sm-2">
                        <span id="FastSetCost">Fast Set:{{ \App\Helpers\Currency::format($materialsCB[5] ?? '0.0') }}</span>

                    </div>

                </div>


                <div class="row">
                    <div class="col-2">
                        <label class="control-label">TOTALS </label>
                    </div>
                    <div class="col-2">
                        <label class="control-label">Sealer</label>
                        <span class="form-control" id="SealerTotal"></span>
                    </div>
                    <div class="col-2">
                        <label class="form-field-label">Sand</label>
                        <span class="form-control" id="SandTotal"></span>

                    </div>
                    <div class="col-2">
                        <label class="form-field-label">Additive</label>
                        <span class="form-control" id="AdditiveTotal"></span>
                    </div>
                    <div class="col-2">
                        <label class="form-field-label">Primer</label>
                        <span class="form-control" id="PrimerTotal"></span>
                    </div>
                    <div class="col-2">
                        <label class="form-field-label">Fast Set</label>
                        <span class="form-control" id="FastSetTotal"></span>
                    </div>
                </div>

        </div>

        @endif

        @if($service->service_category_id == 9)
            <!-- striping -->


            @foreach($striping as $stripe)
                {{$stripe['name']}}
                {{$stripe['service']['dsort']}} <br/>

            @endforeach

        @endif

        @if($service->service_category_id == 10)

            <!--  Sub Contractor -->
            <div class="row">
                <div class="col-sm-3">
                    <x-form-select name="contractor_id"
                                   :items="$contractorsCB"
                                   selected=""
                                   :params="['label' => 'Contractor', 'required' => true]"
                    ></x-form-select>
                    <!-- <span id="contractor_overhead"></span> -->
                    <input type="hidden" name="additive" id="additive" value="">
                </div>
                <div class="col-sm-3">
                    <x-form-text name="cost_per_day"
                                 class="check-contact tc"
                                 placeholder="enter value"
                                 id="cost_per_day"
                                 :params="[
                    'label' => 'Contractor Cost',
                    'required' => true,
                    'iconClass' => 'none',
                ]"
                    >{{$proposalDetail->cost_per_day}}</x-form-text>

                </div>
                <div class="col-sm-6">
                    <x-form-textarea name="alt_desc"
                                     class="check-contact tc"
                                     placeholder="enter description"
                                     id="alt_desc"
                                     :params="[
                                     'label' => 'Job Description',
                                     'required' => true,
                                    'iconClass' => 'none',
                                ]"
                    >{{$proposalDetail->alt_desc}}</x-form-textarea>
                </div>
            </div>

        @endif

        <div class="row mt10">
            <div class="col-sm-12">
                <table class="table-centered table-light full-width">
                    <thead>
                    <tr>
                        <th class="w1-6">@lang('translation.vehicle')</th>
                        <th class="w1-6">@lang('translation.equipment')</th>
                        <th class="w1-6">@lang('translation.materials')</th>
                        <th class="w1-6">@lang('translation.labor')</th>
                        <th class="w1-6">@lang('translation.additionalcost')</th>
                        <th class="w1-6">
                            @if($proposalDetail->service->service_category_id != 10)
                                @lang('translation.subcontractors')
                            @endif
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td class="w1-6 td-tt pr10">
                            <div class="admin-form-item-widget">
                                <x-form-show
                                        class="w180 show-check-contact"
                                        :params="[
                                        'id' => 'header_show_vehicles_cost',
                                    ]">
                                </x-form-show>
                            </div>
                        </td>
                        <td class="w1-6 td-tt pr10">
                            <div class="admin-form-item-widget">
                                <x-form-show
                                        class="w180 show-check-contact"
                                        :params="[
                                        'id' => 'header_show_equipment_cost',
                                    ]">
                                </x-form-show>
                            </div>
                        </td>
                        <td class="w1-6 td-tt pr10">
                            <div class="admin-form-item-widget">
                                <x-form-show
                                        class="w180 show-check-contact"
                                        :params="[
                                        'id' => 'header_show_materials_cost',
                                    ]">
                                </x-form-show>
                            </div>
                        </td>
                        <td class="w1-6 td-tt pr10">
                            <div class="admin-form-item-widget">
                                <x-form-show
                                        class="w180 show-check-contact"
                                        :params="[
                                        'id' => 'header_show_labor_cost',
                                    ]">
                                </x-form-show>
                            </div>
                        </td>
                        <td class="w1-6 td-tt pr10">
                            <div class="admin-form-item-widget">
                                <x-form-show
                                        class="w180 show-check-contact"
                                        :params="[
                                        'id' => 'header_show_additional_cost',
                                    ]">
                                </x-form-show>
                            </div>
                        </td>
                        <td class="w1-6 td-tt">
                            <div class="admin-form-item-widget">
                                @if($proposalDetail->service->service_category_id != 10)
                                    <x-form-show
                                            class="w180 show-check-contact"
                                            :params="[
                                        'id' => 'header_show_subcontractor_cost',
                                    ]">
                                    </x-form-show>
                                @endif
                            </div>
                        </td>
                    </tr>
                    </tbody>
                </table>

            </div>
        </div>


        @if($proposal->progressive_billing)
            <div class="row card-body m20 p20" style="background:#E8F8F5;">
                <div class="col-sm-4">
                    <strong> <b>You indicated that you want to use "Progressive Billing" on this proposal:</b></strong> <br/>Do you want to bill the customer after this service is
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
                    <div class="col-sm-4">
                    &nbsp;
                    </div>
                    </div>
            </div>
        @else
            <input type="hidden" name="bill_after" value="0">
        @endif

        <a id="header_calculate_combined_costing_button2" href="javascript:" class="{{ $site_button_class }} ">Save This
            Service and Stay</a>

        &nbsp;

        <a id="header_calculate_combined_costing_button3" class="{{ $site_button_class }}" href="javascript:">Save and
            Return To Proposal</a>

    </form>

</div>

@push('partials-scripts')

    <script>


        $(document).ready(function () {

            // when the page loads we may need to repeat some calculations to determine total costs
            // and populate other display items on the page


            function calculate(cost_form, estimatorForm, services_id, proposal_detail_id, proposal_id, serviceCategoryId, dosave) {
                /*cost_form has data used to calculate costs,
                estimator form gets hidden values filled in and data gets sent via ajax on save
                what service did we pick
                alert(serviceIds_id);
                alert(serviceIdCategoryName);
                The Math.ceil() static method always rounds up and returns the smaller integer
                 greater than or equal to a given number.
                */


                var ohead = 0.7;
                var percent_overhead = {{ $service->percent_overhead }};
                var servicedesc = '{!! $service->service_template !!}';
                var profit = $("#form_header_profit").val();
                var breakeven = '{{$proposalDetail->break_even}}';
                var overhead = '{{$proposalDetail->overhead}}';
                var materials = 0;
                var proposaltext = tinymce.activeEditor.getContent();
                var service = {{ $proposalDetail->services_id }};
                var square_feet = 0;
                //Materials
                var tackcost = {{$materialsCB[14]}};

                var curbmix = {{$materialsCB[9]}};
                var drummix = {{$materialsCB[10]}};
                var sealercost = {{$materialsCB[1]}};
                var sandcost = {{$materialsCB[2]}};
                var additivecost = {{$materialsCB[3]}};
                var primercost = {{$materialsCB[4]}};
                var fastsetcost = {{$materialsCB[5]}};

                if (parseInt(profit) != profit) { // check these are numbers
                    showInfoAlert('You must only enter numbers for profit.', headerAlert);

                    setTimeout(() => {
                        closeAlert(headerAlert);
                    }, 3000);

                    return;
                }


                if (serviceCategoryId == 1) {

                    {{-- asphalt --}}

                    if (serviceId == 19) {
                        {{-- Asphalt Milling --}}

                        var ohead = 0.88;
                        console.log("overhead:" + ohead);
                        //var cost_per_day = $("#cost_per_day").val();
                        var cost_per_day = $("#cost_per_day").val();
                        var materials_name = $('#cost_per_day').find('option:selected').text();


                        var locations = $("#locations").val();
                        var square_feet = $("#square_feet").val();
                        var depth = $("#depth").val();
                        var days = $("#days").val();

                        if (parseFloat(cost_per_day) == cost_per_day && parseFloat(days) == days && parseFloat(square_feet) == square_feet && parseFloat(depth) == depth) {
                            //alert(cost_per_day + " "  + days + " " + square_feet + " " + depth);

                            var square_yards = Math.ceil(square_feet / 9);

                            $("#square_yards").text(square_yards);


                            //var loads = Math.ceil((form.jordSquareFeet.value * form.jordDepthInInches.value) * (7/2160));
                            var loads = Math.ceil(((square_feet * depth) / 180) / 20);

                            $("#loads").text(loads);

                            var materials = parseFloat(cost_per_day) * parseInt(days);

                            $("#header_show_materials_cost").text('$' + materials);

                            //add it up
                            var results = additup(materials);

                            if ({{$debug_blade}}) {
                                console.log(results);
                            }


                            var combinedcost = results['combined'];


                            if (proposaltext == '') {
                                proposaltext = servicedesc.replace('#SQFT#', square_feet);
                                tinymce.activeEditor.setContent(proposaltext);
                            }

                            // set all relevant form values for update
                            $("#x_material_cost").val(materials);
                            $("#x_square_feet").val(square_feet);
                            $("#x_square_yards").val(square_yards);
                            $("#x_depth").val(depth);
                            $("#x_days").val(days);
                            $("#x_locations").val(locations);
                            $("#x_loads").val(loads);
                            $("#x_cost_per_day").val(cost_per_day);
                            $("#x_materials_name").val(materials_name);

                        } else {

                            showInfoAlert('You must only enter numbers for Square Feet, Depth, Days of Milling and Cost Per Day.', headerAlert);
                            return;


                        }


                    } else {
                        {{-- if(sid == 4 || sid == 5 || sid == 22 || sid == 3 --}}

                        if (serviceId == 4 || serviceId == 22 ) {
                            var ohead = 0.8;
                        }
                        var square_feet = $("#square_feet").val();
                        var depth = $("#depth").val();
                        var cost_per_day = $("#cost_per_day>option:selected").val();
                        //var materials_name = $('#cost_per_day').find('option:selected').text();
                        var materials_name = $( "#cost_per_day option:selected" ).text();

                        //alert (cost_per_day + '-' + materials_name);

                        var locations = $("#locations").val();


                        if (parseFloat(square_feet) == 'NaN' || parseFloat(depth) == 'NaN' || parseFloat(locations) == 'NaN') { // check these are numbers
                            showInfoAlert('You can only enter numbers for square feet and depth.', headerAlert);
                            return;
                        }

                        if (parseFloat(profit) == 'NaN') {
                            showInfoAlert('You can only enter numbers for profit, overhead and break even.', headerAlert);
                            return;
                        }


                        if (square_feet > 0 && depth > 0) {


                            var sqyrd = Math.ceil(square_feet / 9);
                            $("#square_yards").val(sqyrd);
                            $("#x_square_yards").val(sqyrd);



                            var tonamount = Math.ceil((square_feet * depth) / 162);
                            $("#x_tons").val(tonamount);

                            if (serviceId == 4 || service == 22) {
                                var tackamount = Math.ceil(square_feet / 324);

                            } else {
                                var tackamount = Math.ceil(square_feet / 108);

                            }

                            var totaltackcost = tackcost * tackamount;
                            $("#TackCost").text(formatCurrency.format(totaltackcost));

                            var totaltonscost = cost_per_day * tonamount;
                            $("#TonCost").text(formatCurrency.format(totaltonscost));



                            $("#form_header_over_head").text(formatCurrency.format(overhead));


                            var materials = parseFloat(totaltackcost + totaltonscost);

                            //set display value for materials
                            $("#header_show_materials_cost").text('$' + materials);

                            //alert(depth);

                                proposaltext = servicedesc.replace('#TONS#', tonamount);
                                proposaltext = proposaltext.replace('#INCHES#', depth);
                                tinymce.activeEditor.setContent(proposaltext);
                            //add it up
                            var results = additup(materials);

                            if ({{$debug_blade}}) {
                                console.log(results);
                            }
                            var combinedcost = results['combined'];


                            // set all relevant form values for update
                            $("#x_material_cost").val(materials);
                            $("#x_square_feet").val(square_feet);
                            $("#x_depth").val(depth);
                            $("#x_locations").val(locations);
                            $("#x_cost_per_day").val(cost_per_day);
                            $("#x_materials_name").val(materials_name);

                            $("#x_curbmix").val(curbmix);
                            $("#x_drummix").val(drummix);
                            $("#x_sealer").val(sealer);
                            $("#x_sand").val(sand);
                            $("#x_additive").val(additive);
                            $("#x_primer").val(primer);
                            $("#x_fastset").val(fastset);


                            console.log("end asphalt");
                        }
                    }
                }
                {{-- END of Asphalt --}}

                if (serviceCategoryId == 2) {

                    if (serviceId < 12) {

                        var linear_feet = $("#linear_feet").val();
                        var locations = $("#locations").val();
                        var cost_per_linear_feet = {{$materialsCB[9]}}; // curbmix default


                        //linear feet
                        if (linear_feet == parseInt(linear_feet)) {

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

                                cost_per_linear_feet = drummix;
                                cubic_yards = Math.ceil(linear_feet / 15);
                            }
                            if (serviceId == 10) {

                                cubic_yards = Math.ceil(linear_feet / 22);
                            }

                            if (serviceId == 11) {
                                cubic_yards = Math.ceil(linear_feet / 25);
                            }

                            materials = Math.ceil(cubic_yards * cost_per_linear_feet);
                            $("#header_show_materials_cost").text('$' + materials);


                            if (proposaltext == '') {
                                proposaltext = servicedesc.replace('#TONS#', cubic_yards);
                                tinymce.activeEditor.setContent(proposaltext);
                            }


                            var results = additup(materials);
                            if ({{$debug_blade}}) {
                                console.log(results);
                            }

                            var combinedcost = results['combined'];

                            $("#x_material_cost").val(materials);
                            $("#x_linear_feet").val(linear_feet);
                            $("#x_cubic_yards").val(cubic_yards);
                            $("#x_locations").val(locations);
                            $("#x_cost_per_linear_feet").val(cost_per_linear_feet);


                        }

                    } else if (serviceId >= 12) {

                        /*depth and sqq ft sid 12, 13 14
                        12    Concrete    Slab [New or Repairs]    (SF * Depth)/300
                        13    Concrete    Ramp [New or Repairs]    (SF * Depth)/300
                        14    Concrete    Sidewalks [New or Repairs]    (SF * Depth)/300
                        */
                        var square_feet = $("#square_feet").val();
                        var depth = $("#depth").val();
                        var locations = $("#locations").val();
                        var cost_per_linear_feet = {{$materialsCB[10]}};


                        if (square_feet == parseInt(square_feet) && depth == parseInt(depth)) {


                            var cubic_yards = Math.ceil((square_feet * depth) / 300);

                            $("#cubic_yards").val(cubic_yards);
                            var materials = Math.ceil(cubic_yards * cost_per_linear_feet);

                            $("#header_show_materials_cost").text('$' + materials);

                            if (profit == '') {
                                profit = 0;
                                $("#profit").val(0);
                            }


                            proposaltext = servicedesc.replace('#TONS#', cubic_yards);
                            proposaltext = proposaltext.replace('#INCHES#', depth);
                            tinymce.activeEditor.setContent(proposaltext);

                            //total up
                            var results = additup(materials);
                            if ({{$debug_blade}}) {
                                console.log(results);
                            }

                            var combinedcost = results['combined'];


                            // set all relevant form values for update
                            $("#x_material_cost").val(materials);
                            $("#x_cubic_yards").val(cubic_yards);
                            $("#x_square_feet").val(square_feet);
                            $("#x_depth").val(depth);
                            $("#x_locations").val(locations);
                            $("#x_cost_per_linear_feet").val(cost_per_linear_feet);

                        } else {

                            showInfoAlert('Square Feet and Depth are Required Fields.', headerAlert);

                            setTimeout(() => {
                                closeAlert(headerAlert);
                            }, 3000);

                            return;

                        }
                        console.log('End of Concrete');
                    }
                }

                if (serviceCategoryId == 3) {
                    {{--Drainage and Catchbasins--}}

                    var catchbasins = $("#catchbasins").val();
                    var cost_per_day = $("#cost_per_day").val();
                    var alt_desc = $("#alt_desc").val();

                    if (cost_per_day == parseInt(cost_per_day) && catchbasins == parseInt(catchbasins) && alt_desc != '') {


                        var materials = cost_per_day;

                        $("#header_show_materials_cost").text('$' + materials);
                        //total up
                        var results = additup(materials);
                        if ({{$debug_blade}}) {
                            console.log(results);
                        }

                        var combinedcost = results['combined'];


                        if (proposaltext == '') {
                            proposaltext = servicedesc.replace('#BASINS#', catchbasins);
                            tinymce.activeEditor.setContent(proposaltext);
                        }


                        // set all relevant form values for update
                        $("#x_material_cost").val(materials);
                        $("#x_catchbasins").val(catchbasins);
                        $("#x_alt_desc").val(alt_desc);
                        $("#x_cost_per_day").val(cost_per_day);

                        console.log('End of Catchbasins');

                    } else {
                        showInfoAlert('You can only enter numbers for catch basins and cost per day.', headerAlert);
                        return;

                    }
                }

                if (serviceCategoryId == 4) {
                    {{-- 4	Excavation --}}

                    var square_feet = $("#square_feet").val();
                    var depth = $("#depth").val();
                    var cost_per_day = $("#cost_per_day").val();

                    if (parseFloat(square_feet) == 'NaN' || parseFloat(depth) == 'NaN' || parseFloat(cost_per_day) == 'NaN') { // check these are numbers
                        showInfoAlert('You can only enter numbers for square feet and depth and cost.', headerAlert);
                        return;
                    }

                    if (parseFloat(profit) == 'NaN') {
                        showInfoAlert('You can only enter numbers for profit.', headerAlert);
                        return;
                    }


                    if (square_feet > 0 && depth > 0) {

                        var multiplyer = (7 / 1080);
                        var tons = Math.ceil(square_feet * depth * multiplyer);
                        var loads = Math.ceil(tons / 18);

                        $("#loads").text(loads);
                        $("#tons").text(tons);
                        var materials = parseFloat(cost_per_day).toFixed(2);
                        $("#header_show_materials_cost").text('$' + materials);
                        if (proposaltext == '') {
                            proposaltext = servicedesc.replace('#TONS#', tons);
                            tinymce.activeEditor.setContent(proposaltext);
                        }
                        //add it up
                        var results = additup(materials);
                        if ({{$debug_blade}}) {
                            console.log(results);
                        }
                        var combinedcost = results['combined'];


                        // ok so set square_feet, cost, loads, tons, depth, bill_after, profit, break_even, location_id, overhead, toncost, proposal_text
                        // set all relevant form values for update
                        $("#x_material_cost").val(materials);
                        $("#x_square_feet").val(square_feet);
                        $("#x_depth").val(depth);
                        $("#x_loads").val(loads);
                        $("#x_tons").val(tons);
                        $("#x_cost_per_day").val(cost_per_day);
                        $("#x_materials_name").val(materials_name);
                        console.log("end excavation");

                    }


                }
                {{-- END	Excavation --}}


                if (serviceCategoryId == 5) {

                    {{--  Other --}}
                    var cost_per_day = $("#cost_per_day").val();
                    var alt_desc = $("#alt_desc").val();
                    var locations = $("#locations").val();
                    var overhead = 0;
                    if (cost_per_day == parseInt(cost_per_day)) {
                        materials = 0;


                        var results = additup(cost_per_day);
                        if ({{$debug_blade}}) {
                            console.log(results);
                        }

                        var combinedcost = results['combined'];


                        if (proposaltext == '' || proposaltext == 'proposaltext') {
                            proposaltext = servicedesc;
                            tinymce.activeEditor.setContent(proposaltext);
                        }

                        $("#header_show_materials_cost").text(formatCurrency.format(materials));
                        $("#x_cost_per_day").val(cost_per_day);
                        $("#x_material_cost").val(materials);
                        $("#x_proposal_text").val(proposaltext);
                        $("#x_alt_desc").val(alt_desc);
                        $("#x_locations").val(locations);

                    }

                    console.log('End Other');

                }
                {{-- end Other --}}

                if (serviceCategoryId == 6) {

                    {{--  Paver Brick --}}

                    var ohead =  0.75;

                    var cost_per_day = $("#cost_per_day").val();
                    var materials = cost_per_day;
                    var square_feet = $("#square_feet").val();
                    var tons = $("#tons").val();
                    var alt_desc = $("#alt_desc");


                    if (square_feet == parseInt(square_feet) && cost_per_day == parseInt(cost_per_day) && alt_desc != '') {

                        materials = parseFloat(materials).toFixed(2);

                        if (proposaltext == '') {
                            var proposaltext = servicedesc.replace('#SQFT#', square_feet);
                            proposaltext = proposaltext.replace('#TONS#', tons);
                            tinymce.activeEditor.setContent(proposaltext);
                        }

                        var results = additup(materials);
                        if ({{$debug_blade}}) {
                            console.log(results);
                        }

                        var combinedcost = results['combined'];

                        $("#header_show_materials_cost").text(formatCurrency.format(materials));
                        $("#x_cost_per_day").val(cost_per_day);
                        $("#x_material_cost").val(materials);
                        $("#x_proposal_text").val(proposaltext);
                        $("#x_alt_desc").val(alt_desc);
                        $("#x_square_feet").val(square_feet);
                        $("#x_tons").val(tons);


                    } else {
                        showInfoAlert('You can only enter numbers for square feet and cost and tons.', headerAlert);

                        setTimeout(() => {
                            closeAlert(headerAlert);
                        }, 3000);

                        return;

                    }


                }

                if (serviceCategoryId == 7) {
                    {{--  Rock --}}


                    var square_feet = $("#square_feet").val();
                    var depth = $("#depth").val();
                    var rockcost = $('input[name="cost_per_day"]:checked').val();
                    if ({{$debug_blade}}) {
                        console.log(rockcost);
                    }
                    if (parseInt(square_feet) != square_feet || parseInt(depth) != depth) { // check these are numbers
                        showInfoAlert('You can only enter numbers for square feet and depth.', headerAlert);

                        setTimeout(() => {
                            closeAlert(headerAlert);
                        }, 2000);

                        return;
                    }

                    if (square_feet > 0 && depth > 0) {

                        var multiplyer = (7 / 1080);
                        var tons = Math.ceil(square_feet * depth * multiplyer);
                        var loads = Math.ceil(tons / 18);

                        $("#loads").text(loads);
                        $("#tons").text(tons);
                        var materials = (tons * rockcost);
                        //materials = parseFloat(materials);

                        if (proposaltext == '') {
                            var proposaltext = servicedesc.replace('#INCHES#', depth);
                            tinymce.activeEditor.setContent(proposaltext);
                        }

                        var results = additup(materials);
                        if ({{$debug_blade}}) {
                            console.log(results);
                        }

                        var combinedcost = results['combined'];

                        $("#header_show_materials_cost").text(formatCurrency.format(materials));
                        $("#x_square_feet").val(square_feet);
                        $("#x_depth").val(depth);
                        $("#x_tons").val(tons);
                        $("#x_cost_per_day").val(rockcost);
                        $("#x_material_cost").val(materials);
                        $("#x_loads").val(loads);
                        console.log("end rock");
                    }


                }


                if (serviceCategoryId == 8) {

                    {{--  Seal Coating  these are the user imput fields that need to be filled in validated--}}

                    var square_feet = $("#square_feet").val();
                    var yield = $("#yield>option:selected").val();
                    var primer = $("#primer").val();
                    var fastset = $("#fast_set").val();
                    var phases = $("#phases").val();

                    /*
                    SEALER  = Size/Yield  = GAL SEALER
                    AND SAND = GAL SEALER * 2
                    ADDITIVE = AND GAL SEALER / 50
                    */


                    if (square_feet == parseInt(square_feet) && primer == parseInt(primer) && fastset == parseInt(fastset) && parseInt(yield) > 0) {


                        var sealer = Math.ceil(square_feet / yield);
                        var sand = Math.ceil(sealer * 2);
                        var additive = Math.ceil(sealer / 50);

                        $("#sealer").val(sealer);
                        $("#sand").val(sand);
                        $("#additive").val(additive);

                        var sandtotal = Math.ceil(sandcost * sand);
                        var fastsettotal = Math.ceil(fastsetcost * fastset);
                        var primertotal = Math.ceil(primercost * primer);
                        var additivetotal = Math.ceil(additivecost * additive);
                        var sealertotal = Math.ceil(sealercost * sealer);

                        $("#SealerTotal").text(formatCurrency.format(sealertotal));
                        $("#AdditiveTotal").text(formatCurrency.format(additivetotal));
                        $("#PrimerTotal").text(formatCurrency.format(primertotal));
                        $("#FastSetTotal").text(formatCurrency.format(fastsettotal));
                        $("#SandTotal").text(formatCurrency.format(sandtotal));

                        //material cost  total all above
                        var materials = parseFloat(sandtotal + fastsettotal + primertotal + additivetotal + sealertotal);
                        materials = Math.ceil(materials);
                        if ({{$debug_blade}}) {
                            console.log('materials:' + materials);
                        }
                        $("#header_show_materials_cost").text(formatCurrency.format(materials));


                        var results = additup(materials);
                        console.log(results);
                        var combinedcost = results['combined'];

                        $("#x_sealer").val(sealer);
                        $("#x_square_feet").val(square_feet);
                        $("#x_fast_set").val(fastset);
                        $("#x_yield").val(yield);
                        $("#x_phases").val(phases);
                        $("#x_primer").val(primer);
                        $("#x_sand").val(sand);
                        $("#x_additive").val(additive);
                        $("#x_material_cost").val(materials);


                        $("#explain").html(' 30%');
                        if (proposaltext == '') {
                            servicedesc = servicedesc.replace('#SQFT#', square_feet);
                            servicedesc = servicedesc.replace('#PHASES#', phases);
                            tinymce.activeEditor.setContent(servicedesc);
                        }


                    } else {

                        showInfoAlert('You must only enter numbers for Square Feet, Primer and Fast Set, Profit, Break Even and Overhead.', headerAlert);

                        setTimeout(() => {
                            closeAlert(headerAlert);
                        }, 2000);

                        return;


                    }

                    console.log("end sealcoating");

                }

                if (serviceCategoryId == 9) {

                    {{--  striping  not used for this service--}}

                    console.log("end striping");
                }

                if (serviceCategoryId == 10) {

                    {{--  Sub Contractor --}}

                    var additive = $("#additive").val();  // percent overhead
                    var cost_per_day = $("#cost_per_day").val(); // cost to contractor
                    var alt_desc = $("#alt_desc").val(); // desc of services
                    var contractor_id = $("#contractor_id>option:selected").val(); // contractor ID

                    if (cost_per_day == parseInt(cost_per_day)) {
                        materials = cost_per_day;
                        $("#header_show_materials_cost").text(formatCurrency.format(materials));

                        var results = additup(materials);
                        if ({{$debug_blade}})
                            console.log(results);
                    }
                    var combinedcost = results['combined'];



                    if (additive = parseInt(additive) && additive > 0) {
                        // we currently are going with a straight 30% and ignoraing any contractor overhead
                        var soh = parseFloat(1 - (additive / 100));
                        contractor_overhead = Math.ceil((cost_per_day / soh) - cost_per_day);
                        contractor_overhead = formatCurrency.format(contractor_overhead)
                        //$("#contractor_overhead").html(contractor_overhead + ' calculated at ' + additive + '%');

                    } else // sub has no overhead value use standard
                    {
                        var contractor_overhead = Math.ceil((cost_per_day / 0.7) - cost_per_day);
                        formatted_contractor_overhead = formatCurrency.format(contractor_overhead)
                        $("#contractor_overhead").html(formatted_contractor_overhead + ' calculated at 30%');
                    }


                    if (proposaltext == '') {
                        tinymce.activeEditor.setContent(servicedesc);
                    }


                    $("#header_show_materials_cost").text(formatCurrency.format(materials));
                    $("#x_cost_per_day").val(cost_per_day);
                    $("#x_material_cost").val(materials);
                    $("#x_additive").val(contractor_overhead);
                    $("#x_proposal_text").val(proposaltext);
                    $("#x_contractor_id").val(contractor_id);
                    $("#x_alt_desc").val(alt_desc);

                    console.log("end sub contractor");
                }

                /*
                set these fields for all services
                 */


                var profit = $("#form_header_profit").val();
                var otcost = Math.ceil(parseFloat(combinedcost) + parseFloat(profit));
                var overhead = Math.ceil((otcost / ohead) - otcost);
                if (serviceCategoryId == 5) {
                    var overhead = 0;

                }
                //console.log("otcost" + otcost + " OHEAD" + ohead);

                $("#form_header_over_head").text(formatCurrency.format(overhead));
                $("#explain").html('Percent Overhead');

                breakeven = parseFloat(overhead) + parseFloat(combinedcost);
                $("#form_header_break_even").text(formatCurrency.format(breakeven));
                $("#header_show_customer_price").text(formatCurrency.format(breakeven + profit));


                service_name = $("#service_name").val();
                $("#x_service_name").val(service_name);

                var bill_after = $('input[name="bill_after"]:checked').val();
                $("#x_bill_after").val(bill_after);

                var servicename = $("#service_name").val();
                $("#x_service_name").val(servicename)
                var proposaltext = tinymce.activeEditor.getContent();
                $("#x_proposal_text").val(proposaltext);

                $("#x_overhead").val(overhead);
                $("#x_break_even").val(breakeven);
                $("#x_profit").val(profit);

                var customercost = parseFloat(breakeven) + parseFloat(profit);
                headerElCombinedCosting.text(formatCurrency.format(combinedcost));
                headerElCustomerPrice.text(formatCurrency.format(customercost));

                $("#x_cost").val(customercost);

                var zcost = Math.ceil( parseFloat(combinedcost) +  parseFloat(profit) +  parseFloat(overhead));

                if (serviceCategoryId == 10)
                {
                    zcost = customercost;
                }
                if(square_feet > 0)
                {
                    zcost = (zcost/square_feet).toFixed(2);
                }
                else
                {
                    zcost = 'NA';
                }

                if($.isNumeric(zcost))
                {
                    formatCurrency.format(zcost)
                }
                $("#form_cost_per").text(zcost);

                //then save it
                if (dosave == 1) {
                    saveit(false);
                }
                if (dosave == 2) {
                    saveit(true);
                }

            }


            function additup(materials) {


                var combinedcost = (parseFloat($('#estimator_form_vehicle_total_cost').val()) +
                    parseFloat($('#estimator_form_equipment_total_cost').val()) +
                    parseFloat($('#estimator_form_labor_total_cost').val()) +
                    parseFloat($('#estimator_form_additional_cost_total_cost').val()) +
                    parseFloat($('#estimator_form_subcontractor_total_cost').val()) +
                    parseFloat(materials));
                if ({{$debug_blade}}) {
                    console.log('add combined:' + combinedcost);
                }
                var data = [];
                data['combined'] = parseFloat(combinedcost).toFixed(2);
                return data;

            }

            function saveit($leave = false) {

                if ($leave) {
                    $("#stayorleave").val("true")  // return to proposal page or service page

                }

                $("#estimator_form").submit();

                Swal.fire({
                    title: 'Be Patient',
                    text: 'Saving your estimate.',
                    icon: 'success',
                    showConfirmButton: false,

                })


            }

            var cost_form = $("#cost_formula_form");  // values to determine cost
            var estimatorForm = $("#estimator_form"); // form to set values for submit and save

            calculate(cost_form, estimatorForm, serviceId, proposalDetailId, proposalId, serviceCategoryId, 0);

            // when you want to calculate and save record
            $('#header_calculate_combined_costing_button2').on('click', function () {

                calculate(cost_form, estimatorForm, serviceId, proposalDetailId, proposalId, serviceCategoryId, 1);

            });

            $('#header_calculate_combined_costing_button3 ,#header_calculate_combined_costing_button4').on('click', function () {

                calculate(cost_form, estimatorForm, serviceId, proposalDetailId, proposalId, serviceCategoryId, 2);

            });


        });
    </script>
@endpush
