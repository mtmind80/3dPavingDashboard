<div class="">
    @if ($service->service_category_id === 1)
        <!-- asphalt -->
        @if ( $proposalDetail->services_id === 19)
            {{-- asphalt milling --}}
            <div class="row">
                <div class="col-sm-3">
                    <p class="fs18 mb5">
                        <span class="fwb color-black mr5">Square Feet:</span>
                        {{ $proposalDetail->square_feet }}
                    </p>
                </div>
                <div class="col-sm-2">
                    <p class="fs18 mb5">
                        <span class="fwb color-black mr5">Depth:</span>
                        {{ $proposalDetail->depth }}
                    </p>
                </div>
                <div class="col-sm-3">
                    <p class="fs18 mb5">
                        <span class="fwb color-black mr5">:Days</span>
                        {{ $proposalDetail->days }}
                    </p>
                </div>
                @if (auth()->user()->isAdmin())
                    <div class="col-sm-2">
                        <p class="fs18 mb5">
                            <span class="fwb color-black mr5">Cost Per Day:</span>
                            {{ $proposalDetail->html_cost_per_day }}
                        </p>
                    </div>
                    <div class="col-sm-2">
                        <p class="fs18 mb5">
                            <span class="fwb color-black mr5">Locations:</span>
                            {{ $proposalDetail->locations }}
                        </p>
                    </div>
                @else
                    <div class="col-sm-4">
                        <p class="fs18 mb5">
                            <span class="fwb color-black mr5">Locations:</span>
                            {{ $proposalDetail->locations }}
                        </p>
                    </div>
                @endif
            </div>
            <div class="row">
                <div class="col-sm-4">
                    <p class="fs18 mb5">
                        <span class="fwb color-black mr5">Square Yards:</span>
                        {{ $proposalDetail->square_yards }}
                    </p>
                </div>
                <div class="col-sm-4">
                    <p class="fs18 mb5">
                        <span class="fwb color-black mr5">Loads:</span>
                        {{ $proposalDetail->loads }}
                    </p>
                </div>
                <div class="col-sm-4"></div>
            </div>
        @else
            {{-- all other types --}}         <!-- OK -->
            <div class="row">
                <div class="col-sm-3">
                    <p class="fs18 mb5">
                        <span class="fwb color-black mr5">Square Feet:</span>
                        {{ $proposalDetail->square_feet }}
                    </p>
                </div>
                <div class="col-sm-2">
                    <p class="fs18 mb5">
                        <span class="fwb color-black mr5">Depth:</span>
                        {{ $proposalDetail->depth }}
                    </p>
                </div>
                @if (auth()->user()->isAdmin())
                    <div class="col-sm-4">
                        <p class="fs18 mb5">
                            <span class="fwb color-black mr5">Asphalt Cost:</span>
                            {{ $proposalDetail->html_material_cost }} {{ $proposalDetail->materials_name }}
                        </p>
                    </div>
                @endif
                <div class="col-sm-2">
                    <p class="fs18 mb5">
                        <span class="fwb color-black mr5">Locations:</span>
                        {{ $proposalDetail->locations }}
                    </p>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-3">
                    <p class="fs18 mb5">
                        <span class="fwb color-black mr5">Square Yards:</span>
                        {{ $proposalDetail->square_yards }}
                    </p>
                </div>
                <div class="col-sm-3">
                    <p class="fs18 mb5">
                        <span class="fwb color-black mr5">Tons:</span>
                        {{ $proposalDetail->tons }}
                    </p>
                </div>
            </div>
        @endif
    @elseif ($service->service_category_id === 2)
        <!-- concrete -->
        @if ( $service->id < 12)
            <!-- curb mix -->
            <div class="row">
                <div class="col-sm-3">
                    <p class="fs18 mb5">
                        <span class="fwb color-black mr5">Linear Feet:</span>
                        {{ $proposalDetail->linear_feet }}
                    </p>
                </div>
                <div class="col-sm-3">
                    <p class="fs18 mb5">
                        <span class="fwb color-black mr5">Locations:</span>
                        {{ $proposalDetail->locations }}
                    </p>
                </div>
                <div class="col-sm-3">
                    <p class="fs18 mb5">
                        <span class="fwb color-black mr5">Cubic Yards:</span>
                        {{ $proposalDetail->cubic_yards }}
                    </p>
                </div>
                <div class="col-sm-3">
                    <p class="fs18 mb5">
                        <span class="fwb color-black mr5">Curb Mix (per cubic yard):</span>
                        {{ \App\Helpers\Currency::format($proposal->materials()->where('material_id', 9)->value('cost')) }}
                    </p>
                </div>
            </div>
        @else
            <!-- drum mix -->
            <div class="row">
                <div class="col-sm-2">
                    <p class="fs18 mb5">
                        <span class="fwb color-black mr5">Square Feet:</span>
                        {{ $proposalDetail->square_feet }}
                    </p>
                </div>
                <div class="col-sm-2">
                    <p class="fs18 mb5">
                        <span class="fwb color-black mr5">Depth:</span>
                        {{ $proposalDetail->depth }}
                    </p>
                </div>
                <div class="col-sm-2">
                    <p class="fs18 mb5">
                        <span class="fwb color-black mr5">Locations:</span>
                        {{ $proposalDetail->locations }}
                    </p>
                </div>
                <div class="col-sm-3">
                    <p class="fs18 mb5">
                        <span class="fwb color-black mr5">Cubic Yards:</span>
                        {{ $proposalDetail->cubic_yardsn }}
                    </p>
                </div>
                @if (auth()->user()->isAdmin())
                    <div class="col-sm-3">
                        <p class="fs18 mb5">
                            <span class="fwb color-black mr5">Cost Per Linear Feet:</span>
                            {{ \App\Helpers\Currency::format($proposal->materials()->where('material_id', 10)->value('cost')) }}
                        </p>
                    </div>
                @endif
            </div>
        @endif
    @endif

    @if ( $service->service_category_id === 3)
        <!--    3	Drainage and Catchbasins -->
        <div class="row">
            <div class="col-sm-5">
                <p class="fs18 mb5">
                    <span class="fwb color-black mr5">Catchbasins:</span>
                    {{ $proposalDetail->catchbasins }}
                </p>
            </div>
            @if (auth()->user()->isAdmin())
                <div class="col-sm-4">
                    <p class="fs18 mb5">
                        <span class="fwb color-black mr5">Cost Per Day:</span>
                        {{ $proposalDetail->html_cost_per_day }}
                    </p>
                </div>
            @endif
        </div>
        <div class="row">
            <div class="col-sm-9">
                <p class="fs18 mb5">
                    <span class="fwb color-black db mb5">Description:</span>
                    {{ $proposalDetail->alt_desc }}
                </p>
            </div>
        </div>
    @endif

    @if ( $service->service_category_id === 4)
        <!--  4	Excavation -->
        <div class="row">
            <div class="col-sm-2 admin-form-item-widget">
                <p class="fs18 mb5">
                    <span class="fwb color-black mr5">Square Feet:</span>
                    {{ $proposalDetail->square_feet }}
                </p>
            </div>
            <div class="col-sm-2">
                <p class="fs18 mb5">
                    <span class="fwb color-black mr5">Depth:</span>
                    {{ $proposalDetail->depth }}
                </p>
            </div>
            <div class="col-sm-2">
                <p class="fs18 mb5">
                    <span class="fwb color-black mr5">Cost Per Day:</span>
                    {{ $proposalDetail->html_cost_per_day }}
                </p>
            </div>
            <div class="col-sm-2">
                <p class="fs18 mb5">
                    <span class="fwb color-black mr5">Loads:</span>
                    {{ $proposalDetail->loads }}
                </p>
            </div>
            <div class="col-sm-2">
                <p class="fs18 mb5">
                    <span class="fwb color-black mr5">Tons:</span>
                    {{ $proposalDetail->tons }}
                </p>
            </div>
        </div>
    @endif

    @if ( $service->service_category_id === 5)
        <!--  Other -->
        <div class="row">
            @if (auth()->user()->isAdmin())
                @if (auth()->user()->isAdmin())
                    <div class="col-xs-6 col-sm-3">
                        <p class="fs18 mb5">
                            <span class="fwb color-black mr5">Cost Per Day:</span>
                            {{ $proposalDetail->html_cost_per_day }}
                        </p>
                    </div>
                @endif
                <div class="col-xs-6 col-sm-3">
                    <p class="fs18 mb5">
                        <span class="fwb color-black mr5">Locations:</span>
                        {{ $proposalDetail->locations }}
                    </p>
                </div>
            @else
                <div class="col-xs-6 col-sm-6">
                    <p class="fs18 mb5">
                        <span class="fwb color-black mr5">Locations:</span>
                        {{ $proposalDetail->locations }}
                    </p>
                </div>
            @endif
        </div>
        <div class="row">
            <div class="col-md-12">
                <p class="fs18 mb5">
                    <span class="fwb color-black db mb5">Job Description:</span>
                    {{ $proposalDetail->alt_desc }}
                </p>
            </div>
        </div>
    @endif

    @if ( $service->service_category_id === 6)
        <!--  Paver Brick -->
        <div class="row">
            <div class="col-sm-4">
                <p class="fs18 mb5">
                    <span class="fwb color-black mr5">Square Feet:</span>
                    {{ $proposalDetail->square_feet }}
                </p>
            </div>
            @if (auth()->user()->isAdmin())
                <div class="col-sm-4">
                    <p class="fs18 mb5">
                        <span class="fwb color-black mr5">Cost Per Day:</span>
                        {{ $proposalDetail->html_cost_per_day }}
                    </p>
                </div>
            @endif
            <div class="col-sm-4">
                <p class="fs18 mb5">
                    <span class="fwb color-black mr5">Tons:</span>
                    {{ $proposalDetail->tons }}
                </p>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-9">
                <p class="fs18 mb5">
                    <span class="fwb color-black db mb5">Description:</span>
                    {{ $proposalDetail->alt_desc }}
                </p>
            </div>
        </div>
    @endif

    @if ( $service->service_category_id === 7)
        <!--  Rock -->
        <div class="row">
            <div class="col-sm-3">
                <p class="fs18 mb5">
                    <span class="fwb color-black mr5">Square Feet:</span>
                    {{ $proposalDetail->square_feet }}
                </p>
            </div>
            <div class="col-sm-3">
                <p class="fs18 mb5">
                    <span class="fwb color-black mr5">Depth:</span>
                    {{ $proposalDetail->depth }}
                </p>
            </div>
            <div class="col-sm-3">
                <p class="fs18 mb5">
                    <span class="fwb color-black mr5">Tons:</span>
                    {{ $proposalDetail->tons }}
                </p>
            </div>
            <div class="col-sm-3">
                <p class="fs18 mb5">
                    <span class="fwb color-black mr5">Loads:</span>
                    {{ $proposalDetail->loads }}
                </p>
            </div>
        </div>
        @if (auth()->user()->isAdmin())
            <div class="row">
                <div class="col-sm-12">
                    <!-- Show rock cost -->
                    <p class="fs18 mb5">
                        <span class="fwb color-black mr5">Rock Cost:</span>
                        {{ $proposalDetail->html_cost_per_day }}
                    </p>
                </div>
            </div>
        @endif
    @endif

    @if ( $service->service_category_id === 8)
        <!--  Seal Coating -->
        <div class="row">
            <div class="col-sm-3">
                <p class="fs18 mb5">
                    <span class="fwb color-black mr5">Yield:</span>
                    {{ $proposalDetail->yield }}
                </p>
            </div>
            <div class="col-sm-9">
                <p class="fs18 mb5">
                    <span class="fwb color-black mr5">Square Feet:</span>
                    {{ $proposalDetail->square_feet }}
                </p>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-3">
                <p class="fs18 mb5">
                    <span class="fwb color-black mr5">Primer:</span>
                    {{ $proposalDetail->primer }}
                </p>
            </div>
            <div class="col-sm-3">
                <p class="fs18 mb5">
                    <span class="fwb color-black mr5">Fast Set:</span>
                    {{ $proposalDetail->fast_set }}
                </p>
            </div>
            <div class="col-sm-6">
                <p class="fs18 mb5">
                    <span class="fwb color-black mr5">Phases:</span>
                    {{ $proposalDetail->phases }}
                </p>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-3">
                <p class="fs18 mb5">
                    <span class="fwb color-black mr5">Materials Needed:</span>
                </p>
            </div>
            <div class="col-sm-3">
                <p class="fs18 mb5">
                    <span class="fwb color-black mr5">Sealer:</span>
                    {{ $proposalDetail->sealer }}
                </p>
            </div>
            <div class="col-sm-3">
                <p class="fs18 mb5">
                    <span class="fwb color-black mr5">Sand:</span>
                    {{ $proposalDetail->sand }}
                </p>
            </div>
            <div class="col-sm-3">
                <p class="fs18 mb5">
                    <span class="fwb color-black mr5">Additive:</span>
                    {{ $proposalDetail->additive }}
                </p>
            </div>
        </div>
   @endif

    @if ( $service->service_category_id === 9)
        <!-- striping -->
    @endif

    @if ($service->service_category_id === 10)
            <div class="row">
                @if (auth()->user()->isAdmin())
                    <div class="col-sm-12">
                        <p class="fs18 mb5">
                            <span class="fwb color-black mr5">Contractor Cost:</span>
                            {{ $proposalDetail->html_cost_per_day }}
                        </p>
                    </div>
                @endif
            </div>

        <div class="row">
            <div class="col-sm-12">
                <p class="fs18 mb5">
                    <span class="fwb color-black mr5 db mb5">Description:</span>
                    {{ $proposalDetail->alt_desc }}
                </p>
            </div>
        </div>
    @endif
</div>





