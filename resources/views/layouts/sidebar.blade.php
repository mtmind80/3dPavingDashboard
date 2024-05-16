<!-- ========== Left Sidebar Start ========== -->
<div class="vertical-menu">

    <div data-simplebar class="h-100">

        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <ul class="metismenu list-unstyled" id="side-menu">

                <!-- Left Menu Start -->
<!-- do we need this                 <li class="menu-title">@lang('translation.Menu')</li> -->

                <li class="dashboard-controller">
                    <a href="{{url('dashboard')}}" class="waves-effect dashboard-function">
                        <i class="ri-home-3-line"></i><span class="badge badge-pill badge-success float-right">3</span>
                        <span>@if (auth()->user()->isSales()) @lang('translation.my') @endif
                            @lang('translation.Dashboard')</span>
                    </a>
                </li>
                @if (auth()->user()->canHaveLeads())
                    <li class="leads-controller">
                                <a href="{{ url('leads') }}" class="waves-effect index-function create-function edit-function show-function search-function">
                            <i class="ri-chat-follow-up-line"></i>
                            <span>@if (auth()->user()->isSales()) @lang('translation.my') @endif
                                @lang('translation.Leads')</span>
                        </a>
                    </li>
                @endif
                @if (auth()->user()->isAllSales())
                    <li class="contacts-controller">
                        <a href="{{ route('contact_list') }}" class="waves-effect index-function create-function edit-function show-function search-function">
                            <i class="ri-customer-service-2-line"></i>
                            <span>@lang('translation.Contacts')</span>
                        </a>
                    </li>
<!--
                    <li class="contractors-controller">
                        <a href="{{ route('contractor_list') }}" class="waves-effect index-function create-function edit-function show-function search-function">
                            <i class="ri-contacts-book-2-line"></i>
                            <span>@lang('translation.subcontractors')</span>
                        </a>
                    </li>
-->

                    <li class="permits-controller">
                        <a href="{{route('permits')}}" class="waves-effect index-function create-function edit-function show-function search-function">
                            <i class="ri-car-line"></i>
                            <span>
                                @if (auth()->user()->isSales()) @lang('translation.my') @endif
                                    @lang('translation.menu_permits')</span>
                        </a>
                    </li>

                @endif
                <li class="proposal-controller">
                    <a href="{{route('proposals')}}" class="waves-effect index-function create-function edit-function show-function search-function">
                        <i class="ri-compasses-2-line"></i>
                        <span>@if (auth()->user()->isSales()) @lang('translation.my') @endif
                            @lang('translation.menu_proposal')</span>
                    </a>
                </li>
                <li class="workorder-controller">
                    <a href="{{route('workorders')}}" class="waves-effect index-function create-function edit-function show-function search-function">
                        <i class="ri-money-dollar-circle-line"></i>
                        <span>
                            @if (auth()->user()->isSales()) @lang('translation.my') @endif
                                @lang('translation.menu_workorders')</span>
                    </a>
                </li>
                <!-- calendar will be done in another way
                <li class="calendar-controller">
                    <a href="{{route('calendar')}}" class="waves-effect index-function create-function edit-function show-function search-function">
                        <i class="ri-calendar-2-line"></i>
                        <span>@lang('translation.menu_calendar')</span>
                    </a>
                </li>
                -->
                @if (auth()->user()->isAdmin())
                {{-- Admin User Only --}}
                    <li class="reports-controller">
                        <a href="{{route('reports')}}" class="waves-effect index-function create-function edit-function show-function search-function">
                            <i class="ri-table-line"></i>
                            <span>@lang('translation.menu_reports')</span>
                        </a>
                    </li>
                    <li class="user-controller">
                        <a href="{{route('users')}}" class="waves-effect index-function create-function edit-function show-function search-function">
                            <i class="ri-user-follow-line"></i>
                            <span>@lang('translation.Employees')</span>
                        </a>
                    </li>

                @endif
                @if (auth()->user()->isSuperAdmin())
                <li class="resource-controller">
                        <a href="{{route('resources')}}" class="waves-effect showmenu-function index-function newresource-function editresource-function show-function search-function">
                            <i class="ri-barricade-line"></i>
                            <span>@lang('translation.menu_resources')</span>
                        </a>
                    </li>
                @endif
            </ul>
        </div>
        <!-- Sidebar -->
    </div>
</div>
<!-- Left Sidebar End -->
