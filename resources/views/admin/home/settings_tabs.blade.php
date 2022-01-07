
<ul class="nav nav-tabs card-tabs">
  <li class="nav-item">
    <a class="nav-link @if($active=='settings') bg-white active @endif" href="{{ admin_url('setting/site') }}">{{ __('suda_lang::press.basic_info') }}</a>
  </li>
  <li  class="nav-item">
    <a class="nav-link @if($active=='login') bg-white active @endif" href="{{ admin_url('setting/dashboard_info') }}">{{ __('suda_lang::press.dashboard_info') }}</a>
  </li>
  <li  class="nav-item">
    <a class="nav-link @if($active=='logo') bg-white active @endif" href="{{ admin_url('setting/logo') }}">{{ trans('suda_lang::press.logo') }}</a>
  </li>
  
  <li  class="nav-item">
    <a class="nav-link @if($active=='browser') bg-white active @endif" href="{{ admin_url('setting/browser') }}">{{ __('suda_lang::press.front_info') }}</a>
  </li>
  
  <li  class="nav-item">
    <a class="nav-link @if($active=='seo') bg-white active @endif" href="{{ admin_url('setting/seo') }}">{{ __('suda_lang::press.seo_info') }}</a>
  </li>
</ul>