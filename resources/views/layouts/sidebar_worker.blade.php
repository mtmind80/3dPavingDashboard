<!-- ========== Left Sidebar Start ========== -->
<div class="vertical-menu">

    <div data-simplebar class="h-100">

        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">
<!-- do we need this                 <li class="menu-title">@lang('translation.Menu')</li> -->

                <li>
                    <a href="{{url('dashboard')}}" class="waves-effect">
                        <i class="ri-home-3-line"></i><span class="badge badge-pill badge-success float-right">3</span>
                        <span>@lang('translation.Dashboard')</span>
                    </a>
                </li>
         
                <li>
                    <a href="{{route('workorders')}}" class="waves-effect">
                        <i class="ri-money-dollar-circle-line"></i>
                        <span>@lang('translation.menu_workorders')</span>                    </a>
                </li>


                <li>
                    <a href="{{route('calendar')}}" class="waves-effect">
                        <i class="ri-calendar-2-line"></i>
                        <span>@lang('translation.menu_calendar')</span>                    
                    </a>
                </li>

            </ul>
        </div>
        <!-- Sidebar -->
    </div>
</div>
<!-- Left Sidebar End -->
