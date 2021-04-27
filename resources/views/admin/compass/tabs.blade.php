<ul class="nav nav-tabs page-tabs">
    <li class="nav-item" >
        <a class="nav-link @if($active_tab=='about') active @endif" href="{{ admin_url('compass/about') }}">{{ __('suda_lang::press.about_suda') }}</a>
    </li>
<li  class="nav-item">
      <a class="nav-link @if($active_tab=='index') active @endif" href="{{ admin_url('compass') }}">{{ __('suda_lang::press.resources') }}</a>
  </li>
  <li  class="nav-item">
      <a class="nav-link @if($active_tab=='commands') active @endif" href="{{ admin_url('compass/commands') }}">{{ __('suda_lang::press.commands') }}</a>
  </li>
  
  <li class="nav-item">
      <a class="nav-link @if($active_tab=='certificate') active @endif" href="{{ admin_url('certificate') }}">{{ __('suda_lang::press.certificate') }}</a>
  </li>
</ul>