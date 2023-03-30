@if(!isset($sdcore->settings->dashboard->operate_avatar) || (isset($sdcore->settings->dashboard->operate_avatar) && $sdcore->settings->dashboard->operate_avatar!=0))
<div class="sidebar_bottom dropup">
    
    <a class="dropdown-toggle side-user" data-bs-toggle="dropdown" id="dropdownMenuUser" role="button" aria-haspopup="true" aria-expanded="false">
        {{ suda_avatar($soperate->avatar) }}
        
    </a>
    <ul role="menu" class="dropdown-menu" aria-labelledby="dropdownMenuUser">
        <li>
            <a class="dropdown-item" href="{{ admin_url('profile') }}">
                {{ __('suda_lang::auth.accountInfo') }}
            </a>
        </li>

        @if($soperate->superadmin==1)
        <li>
            <a class="dropdown-item" href="{{ admin_url('setting/site') }}">
                {{ __('suda_lang::press.menu_items.setting_system') }}
            </a>
        </li>
        @endif
        <li>
            <a class="dropdown-item" href="{{ admin_url('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form-bottom').submit();">
                {{ __('suda_lang::auth.logout') }}
            </a>
            <form id="logout-form-bottom" action="{{ admin_url('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </li>
    </ul>
    
</div>
@endif