<!-- Nav tabs -->
<ul class="nav nav-tabs" role="tablist">
    <li class="nav-item">
        <a id="tab_dashboard_1" class="nav-link @if($activelink == '1') active  @endif" href="{{ route('dashboard') }}" role="tab">
            <span class="d-block d-sm-none"><i class="fas fa-home"></i></span>
            <span class="d-none d-sm-block fwb">@lang('translation.dashboard1')</span>
        </a>
    </li>
    <li class="nav-item">
        <a id="tab_dashboard_2" class="nav-link @if($activelink == '2') active  @endif" href="{{ route('dashboard_2') }}" role="tab">
            <span class="d-block d-sm-none"><i class="fas fa-home"></i></span>
            <span class="d-none d-sm-block">@lang('translation.dashboard2')</span>
        </a>
    </li>
    <li class="nav-item">
        <a id="tab_dashboard_3" class="nav-link @if($activelink == '3') active  @endif" href="{{ route('dashboard_3') }}"
           role="tab">
            <span class="d-block d-sm-none"><i class="fas fa-home"></i></span>
            <span class="d-none d-sm-block">@lang('translation.dashboard3')</span>
        </a>
    </li>
    <li class="nav-item">
        <a id="tab_dashboard_4" class="nav-link @if($activelink == '4') active  @endif" href="{{ route('dashboard_4') }}" role="tab">
            <span class="d-block d-sm-none"><i class="fas fa-home"></i></span>
            <span class="d-none d-sm-block">@lang('translation.dashboard4')</span>
        </a>
    </li>
    <li class="nav-item">
        <a id="tab_dashboard_7" class="nav-link @if($activelink == '7') active  @endif" href="{{ route('dashboard_7') }}"
           role="tab">
            <span class="d-block d-sm-none"><i class="fas fa-home"></i></span>
            <span class="d-none d-sm-block">@lang('translation.dashboard7')</span>
        </a>
    </li>
    @if($authuser['role_id'] == 1 || $authuser['role_id'] == 7)
    <li class="nav-item">
        <a id="tab_dashboard_5" class="nav-link @if($activelink == '5') active  @endif" href="{{ route('dashboard_5') }}"
           role="tab">
            <span class="d-block d-sm-none"><i class="fas fa-home"></i></span>
            <span class="d-none d-sm-block">@lang('translation.dashboard5')</span>
        </a>
    </li>
    <li class="nav-item">
        <a id="tab_dashboard_6" class="nav-link @if($activelink == '6') active  @endif" href="{{ route('dashboard_6') }}"
           role="tab">
            <span class="d-block d-sm-none"><i class="fas fa-home"></i></span>
            <span class="d-none d-sm-block">@lang('translation.dashboard6')</span>
        </a>
    </li>
        @endif
</ul>

