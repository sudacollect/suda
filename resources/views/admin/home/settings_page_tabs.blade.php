<ul class="nav nav-tabs page-tabs">
    <li class="nav-item">
        <a class="nav-link @if($active=='settings') active @endif" href="{{ admin_url('setting/site') }}">{{ __('suda_lang::press.basic_info') }}</a>
      </li>
      <li  class="nav-item">
        <a class="nav-link @if($active=='login') active @endif" href="{{ admin_url('setting/dashboard_info') }}">{{ __('suda_lang::press.dashboard_info') }}</a>
      </li>
      <li  class="nav-item">
        <a class="nav-link @if($active=='logo') active @endif" href="{{ admin_url('setting/logo') }}">{{ __('suda_lang::press.logo') }}</a>
      </li>
      
      <li  class="nav-item">
        <a class="nav-link @if($active=='browser') active @endif" href="{{ admin_url('setting/browser') }}">{{ __('suda_lang::press.front_info') }}</a>
      </li>
      
      <li  class="nav-item">
        <a class="nav-link @if($active=='seo') active @endif" href="{{ admin_url('setting/seo') }}">{{ __('suda_lang::press.seo_info') }}</a>
      </li>
</ul>