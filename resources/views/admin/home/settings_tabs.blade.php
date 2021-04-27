
<ul class="nav nav-tabs card-tabs">
  <li class="nav-item">
    <a class="nav-link @if($active=='settings') bg-white active @endif" href="{{ admin_url('setting/site') }}">{{ trans('suda_lang::press.system_info') }}</a>
  </li>
  <li  class="nav-item">
    <a class="nav-link @if($active=='login') bg-white active @endif" href="{{ admin_url('setting/dashboard_info') }}">控制台</a>
  </li>
  <li  class="nav-item">
    <a class="nav-link @if($active=='logo') bg-white active @endif" href="{{ admin_url('setting/logo') }}">{{ trans('suda_lang::press.logo') }}</a>
  </li>
  
  <li  class="nav-item">
    <a class="nav-link @if($active=='browser') bg-white active @endif" href="{{ admin_url('setting/browser') }}">前台浏览</a>
  </li>
  
  <li  class="nav-item">
    <a class="nav-link @if($active=='seo') bg-white active @endif" href="{{ admin_url('setting/seo') }}">SEO</a>
  </li>
</ul>