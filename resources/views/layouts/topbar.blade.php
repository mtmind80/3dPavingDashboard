<header id="page-topbar">
    <div class="navbar-header">
        <div class="d-flex">
            <!-- LOGO -->
            <div class="navbar-brand-box">
                <a href="{{route('dashboard')}}" class="logo logo-light">
                    <span class="logo-sm">
                        <img src="{{ URL::asset('/assets/images/logo-sm-light.png')}}" alt="" height="25">
                    </span>
                    <span class="logo-lg">
                        <img src="{{ URL::asset('/assets/images/logo-light.png')}}" alt="" height="28">
                    </span>
                </a>
            </div>

            <button type="button" class="btn btn-sm px-3 font-size-24 header-item waves-effect" id="vertical-menu-btn">
                <i class="ri-menu-2-line align-middle"></i>
            </button>

        </div>

        <div class="d-flex">

            <div class="dropdown d-inline-block d-lg-none ml-2">
                <button type="button" class="btn header-item noti-icon waves-effect" id="page-header-search-dropdown"
                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="ri-search-line"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right p-0"
                    aria-labelledby="page-header-search-dropdown">

                    <form class="p-3">
                        <div class="form-group m-0">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Search ...">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="submit"><i class="ri-search-line"></i></button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="dropdown d-none d-sm-inline-block">
                <button type="button" class="btn header-item waves-effect"
                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    @php $locale = session()->get('locale'); @endphp
                    @switch($locale)
                        @case('rs')
                        <img src="{{ URL::asset('/assets/images/flags/russia.jpg')}}" alt="Header Language" height="16">
                        @break
                        @case('it')
                        <img src="{{ URL::asset('/assets/images/flags/italy.jpg')}}" alt="Header Language" height="16">
                        @break
                        @case('gr')
                        <img src="{{ URL::asset('/assets/images/flags/germany.jpg')}}" alt="Header Language" height="16">
                        @break
                        @case('es')
                        <img src="{{ URL::asset('/assets/images/flags/spain.jpg')}}" alt="Header Language" height="16">
                        @break
                        @default
                        <img src="{{ URL::asset('/assets/images/flags/us.jpg')}}" alt="Header Language" height="16">
                    @endswitch
                </button>
                <div class="dropdown-menu dropdown-menu-right">
                    <!-- item-->
                    <a href="{{ url('index/en') }}" class="dropdown-item notify-item">
                        <img src="{{ URL::asset('/assets/images/flags/us.jpg')}}" alt="user-image" class="mr-1" height="12"> <span class="align-middle">English</span>
                    </a>
                    <!-- item-->
                    <a href="{{ url('index/es') }}" class="dropdown-item notify-item">
                        <img src="{{ URL::asset('/assets/images/flags/spain.jpg')}}" alt="user-image" class="mr-1" height="12"> <span class="align-middle">Spanish</span>
                    </a>
                </div>
            </div>

            <div class="dropdown d-inline-block user-dropdown">
                <button type="button" class="btn header-item waves-effect" id="page-header-user-dropdown"
                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
<!--
                   <img class="header-profile-user" src="{{ URL::asset('/assets/images/users/avatar-1.jpg')}}"
                        alt="Header Avatar"> -->
                    <span class="d-none d-xl-inline-block ml-1">{{ Auth::user()->full_name }}</span>
                    <i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-right">
                    <!-- item-->
                    <!--
                    <a class="dropdown-item" href="#"><i class="ri-user-line align-middle mr-1"></i> @lang('translation.Profile')</a>
                    <a class="dropdown-item" href="#"><i class="ri-wallet-2-line align-middle mr-1"></i> @lang('translation.My_Wallet')</a>
                    <a class="dropdown-item d-block" href="#"><span class="badge badge-success float-right mt-1">11</span><i class="ri-settings-2-line align-middle mr-1"></i> @lang('translation.Settings')</a>
                   -->
                    <a class="dropdown-item" href="{{ route('lockout') }}"><i class="ri-lock-unlock-line align-middle mr-1"></i>@lang('translation.Lock_screen') </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item text-danger" href="javascript:void();" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="ri-close-line font-size-16 align-middle mr-1 text-danger"></i> @lang('translation.Logout')</a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="#"><i class="ri-space-ship-line align-middle mr-1"></i>{{ \App\Models\Role::find(Auth::user()->role_id)->role }}</a>

                </div>
            </div>
        </div>
    </div>
</header>
