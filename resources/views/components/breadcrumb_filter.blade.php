 <!-- start page title -->
 <div class="row">
     <div class=" col-8 page-title-box d-flex align-items-center justify-content-between">
         <h4 class="mb-0">{{ $title }}</h4>
     </div>
     <div class="col-4">
         <div class="page-title-right float-right">
             <ol class="breadcrumb m-0">
                 <li class="breadcrumb-item active">{{ $li_1 }}</li>
                 @if(isset($li_2))
                     <li class="breadcrumb-item">{{ $li_2 }}</li>
                 @endif
             </ol>
         </div>
     </div>

         <p></p>
 </div>

 <div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            @if (auth()->user()->isAdmin())
                <div class="component-breadcrumb-filters">
                    <label>@lang('translation.year'):</label>
                    <select id="selected_year" class="custom-select custom-select-sm" name="selected_year">
                        <option{{ empty($selectedYear) ? ' selected' : '' }} value="all" data-url="{{ route('dashboard', http_build_query(\Request::except(['selected_year']))) }}">@lang('translation.all')</option>
                        @foreach ($yearsCB as $year)
                            <option
                                value="{{ $year }}"{{ !empty($selectedYear) && $year == $selectedYear ? ' selected' : '' }}
                                data-url="{{ route('dashboard', array_merge(Request::query(), ['selected_year' => $year])) }}"
                            >{{ $year }}</option>
                        @endforeach
                    </select>
                    @if (!empty($creatorsCB) || !empty($salesManagersCB) || !empty($salesPersonsCB))
                        <!--
                        @if (!empty($creatorsCB))
                            <label>@lang('translation.creator'):</label>
                            <select id="creator_id" class="custom-select custom-select-sm" name="creator_id">
                                <option{{ empty($creatorId) ? ' selected' : '' }} value="all" data-url="{{ route('dashboard', http_build_query(\Request::except(['creator_id']))) }}">@lang('translation.all')</option>
                                @foreach ($creatorsCB as $id => $fullName)
                                    <option
                                        value="{{ $id }}"{{ !empty($creatorId) && $creatorId == $id ? ' selected' : '' }}
                                        data-url="{{ route('dashboard', array_merge(Request::query(), ['creator_id' => $id])) }}"
                                    >{{ $fullName }}</option>
                                @endforeach
                            </select>
                        @endif

                        @if (!empty($salesManagersCB))
                            <label>@lang('translation.sales_manager'):</label>
                            <select id="sales_manager_id" class="custom-select custom-select-sm" name="sales_manager_id">
                                <option{{ empty($salesManagerId) ? ' selected' : '' }} value="all" data-url="{{ route('dashboard', http_build_query(\Request::except(['sales_manager_id']))) }}">@lang('translation.all')</option>
                                @foreach ($salesManagersCB as $id => $fullName)
                                    <option
                                        value="{{ $id }}"{{ !empty($salesManagerId) && $salesManagerId == $id ? ' selected' : '' }}
                                        data-url="{{ route('dashboard', array_merge(Request::query(), ['sales_manager_id' => $id])) }}"
                                    >{{ $fullName }}</option>
                                @endforeach
                            </select>
                        @endif
                        -->
                        @if (!empty($salesPersonsCB))
                            <label>@lang('translation.sales_person'):</label>
                            <select id="sales_person_id" class="custom-select custom-select-sm" name="sales_person_id">
                                <option{{ empty($salesPersonId) ? ' selected' : '' }} value="all" data-url="{{ route('dashboard', http_build_query(\Request::except(['sales_person_id']))) }}">@lang('translation.all')</option>
                                @foreach ($salesPersonsCB as $id => $fullName)
                                    <option
                                        value="{{ $id }}"{{ !empty($salesPersonId) && $salesPersonId == $id ? ' selected' : '' }}
                                        data-url="{{ route('dashboard', array_merge(Request::query(), ['sales_person_id' => $id])) }}"
                                    >{{ $fullName }}</option>
                                @endforeach
                            </select>
                        @endif
                    @endif
                    <button type="button" class="ml6 submit-button btn btn-secondary btn-sm waves-effect waves-light">@lang('translation.submit')</button>
                </div>
            @endif
        </div>
    </div>
</div>
<!-- end page title -->
