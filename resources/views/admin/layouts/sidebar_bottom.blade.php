@if(!isset($sdcore->settings->dashboard->operate_avatar) || (isset($sdcore->settings->dashboard->operate_avatar) && $sdcore->settings->dashboard->operate_avatar!=0))
<div class="sidebar_bottom mt-auto">
    <div class="dropdown-toggle side-user" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
        {{ suda_avatar($soperate->avatar) }}
        <div class="username">
            {{ $soperate->username }}
        </div>
    </div>
    <div role="menu" class="dropdown-menu">
        <a class="dropdown-item" href="{{ admin_url('profile') }}">
            {{ __('suda_lang::auth.accountInfo') }}
        </a>
        @if($soperate->superadmin==1)
            <a class="dropdown-item" href="{{ admin_url('setting/site') }}">
                {{ __('suda_lang::press.menu_items.setting_system') }}
            </a>
        @endif
        <a class="dropdown-item" href="{{ admin_url('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form-bottom').submit();">
            {{ __('suda_lang::auth.logout') }}
        </a>
        <form id="logout-form-bottom" action="{{ admin_url('logout') }}" method="POST" style="display: none;">
            {{ csrf_field() }}
        </form>
    </div>
</div>
@endif