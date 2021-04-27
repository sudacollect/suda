<ul class="nav nav-tabs page-tabs">
    <li class="nav-item">
        <a class="nav-link @if($active=='settings') active @endif" href="{{ admin_url('setting/site') }}">{{ trans('suda_lang::press.system_info') }}</a>
      </li>
      <li  class="nav-item">
        <a class="nav-link @if($active=='login') active @endif" href="{{ admin_url('setting/dashboard_info') }}">控制台设置</a>
      </li>
      <li  class="nav-item">
        <a class="nav-link @if($active=='logo') active @endif" href="{{ admin_url('setting/logo') }}">{{ trans('suda_lang::press.logo') }}</a>
      </li>
      
      <li  class="nav-item">
        <a class="nav-link @if($active=='browser') active @endif" href="{{ admin_url('setting/browser') }}">浏览设置</a>
      </li>
      
      <li  class="nav-item">
        <a class="nav-link @if($active=='seo') active @endif" href="{{ admin_url('setting/seo') }}">SEO设置</a>
      </li>
</ul>