@if(Auth::guard('operate')->check())
@if(!isset($sdcore->settings->dashboard->operate_avatar) || (isset($sdcore->settings->dashboard->operate_avatar) && $sdcore->settings->dashboard->operate_avatar!=1))
<li class="nav-item dropdown">
    <a href="#" class="nav-link dropdown-toggle with-avatar" id="navbar-user-dropdown" data-toggle="dropdown"  aria-haspopup="true" aria-expanded="false">
        @if(isset($soperate))
        {{ suda_avatar($soperate->avatar) }}
        {{ $soperate->username }}
        @else
        <i class="ion-persion-circle"></i>
        @endif
    </a>


    <div class="dropdown-menu user-menu" aria-labelledby="navbar-user-dropdown">
        <a class="dropdown-item" href="{{ admin_url('/') }}">
            <i class="icon ion-settings"></i>控制面板
        </a>

        <a class="dropdown-item" href="{{ admin_url('profile') }}">
            <i class="icon ion-person"></i>{{ trans('suda_lang::auth.accountInfo') }}
        </a>

        <div class="dropdown-divider"></div>
        <a class="dropdown-item" href="{{ admin_url('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
            <i class="icon ion-exit-outline"></i>{{ __('suda_lang::auth.logout') }}
        </a>
        
        <form id="logout-form" action="{{ admin_url('logout') }}" method="POST" style="display: none;">
            {{ csrf_field() }}
        </form>
    </div>
</li>
@endif
@endif