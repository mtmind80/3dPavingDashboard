<div class="">
    @if ( $service->service_category_id === 1)
        <!-- asphalt -->
        @if ( $proposalDetail->services_id === 19)
            {{-- asphalt milling --}}
            <div class="row">
                <div class="col-sm-3">
                    Square Feet: {{ $proposalDetail->square_feet }}
                </div>
                <div class="col-sm-2">
                    Depth: {{ $proposalDetail->depth }}
                </div>
                <div class="col-sm-3">
                    Days: {{ $proposalDetail->days }}
                </div>
                <div class="col-sm-2">
                    Cost Per Day: {{ $proposalDetail->cost_per_day }}
                </div>
                <div class="col-sm-2">
                    Locations: {{ $proposalDetail->locations }}
                </div>
            </div>
            <br/>
            <div class="row">
                <div class="col-sm-4">
                    Square Yards: {{ $proposalDetail->square_yards }}
                </div>
                <div class="col-sm-4">
                    Loads:{{ $proposalDetail->loads }}
                </div>
                <div class="col-sm-4">
                    &nbsp;
                </div>
            </div>
        @else
            {{-- all other types --}}
            <br/>
            <div class="row">
                <div class="col-sm-3">
                    Square Feet: {{ $proposalDetail->square_feet }}
                </div>
                <div class="col-sm-3">
                    Depth: {{ $proposalDetail->depth }}
                </div>
                <div class="col-sm-3">
                    Asphalt Cost:
                    {{ $proposalDetail->material_cost }}
                    {{ $proposalDetail->materials_name }}
                </div>
                <div class="col-sm-3">
                    Locations: {{ $proposalDetail->locations }}
                </div>
            </div>
            <br/>
            <div class="row">
                <div class="col-sm-3">
                    Square Yards: {{ $proposalDetail->square_yards }}
                </div>
                <div class="col-sm-3">
                    Tons: {{ $proposalDetail->tons }}
                </div>
            </div>
        @endif
    @elseif ($service->service_category_id === 2)
        <!-- concrete -->
        @if ( $service->id < 12)
            <!-- curb mix -->
            <div class="row">
                <div class="col-sm-3">
                    Linear Feet: {{ $proposalDetail->linear_feet }}
                </div>
                <div class="col-sm-3">
                    Locations: {{ $proposalDetail->locations }}
                </div>
                <div class="col-sm-3">
                    Cubic Yards: {{ $proposalDetail->cubic_yards }}
                </div>
                <div class="col-sm-3">
                    Curb Mix (per cubic yard): {{ \App\Helpers\Currency::format($materialsCB[9] ?? '0.0') }}
                </div>
            </div>
        @else
            <!-- drum mix -->
            <br/>
            <div class="row">
                <div class="col-sm-2">
                    Square Feet: {{ $proposalDetail->square_feet }}
                </div>
                <div class="col-sm-2">
                    Depth: {{ $proposalDetail->depth }}
                </div>
                <div class="col-sm-2">
                    Locations: {{ $proposalDetail->locations }}
                </div>
                <div class="col-sm-3">
                    Cubic Yards: {{ $proposalDetail->cubic_yardsn }}
                </div>
                <div class="col-sm-3">
                    Cost Per Linear Feet: {{ \App\Helpers\Currency::format($materialsCB[10] ?? '0.0') }}
                </div>
            </div>
        @endif
    @endif

    @if ( $service->service_category_id === 3)
        <!--    3	Drainage and Catchbasins -->
        <br/>

        <div class="row">
            <div class="col-sm-5">
                Catchbasins: {{ $proposalDetail->catchbasins }}
            </div>
            <div class="col-sm-4">
                Cost Per Day: {{ $proposalDetail->cost_per_day }}
            </div>
        </div>
        <br/>
        <div class="row">
            <div class="col-sm-9">
                Description: {{ $proposalDetail->alt_desc }}
            </div>
        </div>
    @endif

    @if ( $service->service_category_id === 4)
        <!--  4	Excavation -->
        <div class="row">
            <div class="col-sm-2 admin-form-item-widget">
                Square Feet: {{ $proposalDetail->square_feet }}
            </div>
            <div class="col-sm-2">
                Depth: {{ $proposalDetail->depth }}
            </div>
            <div class="col-sm-2">
                Cost Per Day: {{ $proposalDetail->cost_per_day }}
            </div>
            <div class="col-sm-2">
                Loads: {{ $proposalDetail->loads }}
            </div>
            <div class="col-sm-2">
                Tons: {{ $proposalDetail->tons }}
            </div>
        </div>

        <br/>
    @endif

    @if ( $service->service_category_id === 5)
        <!--  Other -->
        <div class="row">
            <div class="col-xs-6 col-sm-3">
                <p class="fs18 mb5">
                    <span class="fwb color-black mr5">Cost Per Day:</span>
                    {{ $proposalDetail->cost_per_day }}
                </p>
            </div>
            <div class="col-xs-6 col-sm-3">
                <p class="fs18 mb5">
                    <span class="fwb color-black mr5">Locations:</span>
                    {{ $proposalDetail->locations }}
                </p>
            </div>
            <div class="col-xs-12 col-sm-6">
                <p class="fs18 mb5">
                    <span class="fwb color-black mr5">Job Description:</span>
                    {{ $proposalDetail->alt_desc }}
                </p>
            </div>
        </div>
    @endif

    @if ( $service->service_category_id === 6)
        <!--  Paver Brick -->
        <br/>

        <div class="row">
            <div class="col-sm-4">
                Square Feet: {{ $proposalDetail->square_feet }}
            </div>
            <div class="col-sm-4">
                Cost Per Day: {{ $proposalDetail->cost_per_day }}
            </div>
            <div class="col-sm-4">
                <div class='check-contact tc' id="tonnage"></div>
                Tons: {{ $proposalDetail->tons}}
            </div>
        </div>

        <br/>

        <div class="row">
            <div class="col-sm-9">
                Description: {{ $proposalDetail->alt_desc }}
            </div>
        </div>
    @endif

    @if ( $service->service_category_id === 7)
        <!--  Rock -->
        <div class="row">
            <div class="col-sm-3">
                Square Feet: {{ $proposalDetail->square_feet }}
            </div>
            <div class="col-sm-3">
                Depth: {{ $proposalDetail->depth }}
            </div>
            <div class="col-sm-3">
                Tons: {{ $proposalDetail->tons }}
            </div>
            <div class="col-sm-3">
                Loads: {{ $proposalDetail->loads }}
            </div>
        </div>

        <br/>

        <div class="row">
            <div class="col-sm-4">
                <!-- Show rock cost -->
                Rock Cost: {{ $proposalDetail->cost_per_day }}
            </div>
            <div class="col-sm-4">
            </div>
            <div class="col-sm-4">
            </div>
        </div>
    @endif

    @if ( $service->service_category_id === 8)
        <!--  Seal Coating -->
        <div class="row">
            <div class="col-sm-3">
            </div>
            <div class="col-sm-3">
                Yield: {{ $proposalDetail->yield }}
            </div>
            <div class="col-sm-3">
                Square Feet: {{ $proposalDetail->square_feet }}
            </div>
        </div>

        <br/>

        <div class="row">
            <div class="col-sm-4">
                Primer: {{ $proposalDetail->primer }}
            </div>
            <div class="col-sm-4">
                Fast Set: {{ $proposalDetail->fast_set }}
            </div>
            <div class="col-sm-4">
                Phases: {{ $proposalDetail->phases }}
            </div>
        </div>

        <br/>

        <div class="row">
            <div class="col-lg-3">
                <h5>Materials Needed</h5>
            </div>
            <div class="col-lg-3">
                Sealer: {{ $proposalDetail->sealer }}
            </div>
            <div class="col-lg-3">
                Sand: {{ $proposalDetail->sand }}
            </div>
            <div class="col-lg-3">
                Additive: {{ $proposalDetail->additive }}
            </div>
        </div>
   @endif

    @if ( $service->service_category_id === 9)
        <!-- striping -->
        @foreach($striping as $stripe)
            {{ $stripe['name'] }}
            {{ $stripe['service']['dsort'] }}
            <br/>
        @endforeach
    @endif

    @if ($service->service_category_id === 10)
        <div class="row">
            <div class="col-sm-3">
                Contractor Cost: {{ $proposalDetail->cost_per_day }}
            </div>
            <div class="col-sm-6">
                Description: {{ $proposalDetail->alt_desc }}
            </div>
        </div>
    @endif
</div>





