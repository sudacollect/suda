@if(Auth::guard('operate')->check())

<li class="nav-item dropdown" x-data="{ expanded: false }">
    <a @click="expanded = ! expanded" href="#" class="nav-link dropdown-toggle with-avatar" id="navbar-user-dropdown" data-bs-toggle="dropdown"  aria-haspopup="true" aria-expanded="false">
        @if(isset($soperate))
        {{ suda_avatar($soperate->avatar) }}
        {{ $soperate->username }}
        @else
        <i class="ion-persion-circle"></i>
        @endif
    </a>


    <div class="dropdown-menu user-menu show" aria-labelledby="navbar-user-dropdown" x-show="expanded" @click.outside="expanded = false">
        <a class="dropdown-item" href="{{ admin_url('/') }}">
            <i class="icon ion-settings"></i>{{ ucfirst(__('suda_lang::press.dashboard')) }}
        </a>

        <a class="dropdown-item" href="{{ admin_url('profile') }}">
            <i class="icon ion-person"></i>{{ __('suda_lang::press.profile.profile') }}
        </a>

        <div class="dropdown-divider"></div>
        <a class="dropdown-item" href="{{ admin_url('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
            <i class="icon ion-exit-outline"></i>{{ __('suda_lang::press.logout') }}
        </a>
        
        <form id="logout-form" action="{{ admin_url('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </div>
</li>

@endif