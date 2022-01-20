<ul class="nav nav-tabs page-tabs">
    <li class="nav-item" >
        <a class="nav-link @if($active_tab=='about') active @endif" @if($active_tab=='about') style="background-color: #f6f6f6;border-color: #dee2e6 #dee2e6 #f6f6f6;" @endif href="{{ admin_url('compass/about') }}">{{ __('suda_lang::press.about_suda') }}</a>
    </li>
<li  class="nav-item">
      <a class="nav-link @if($active_tab=='index') active @endif" @if($active_tab=='index') style="background-color: #f6f6f6;border-color: #dee2e6 #dee2e6 #f6f6f6;" @endif href="{{ admin_url('compass') }}">{{ __('suda_lang::press.resources') }}</a>
  </li>
  {{-- <li  class="nav-item">
      <a class="nav-link @if($active_tab=='commands') active @endif" @if($active_tab=='commands') style="background-color: #f6f6f6;border-color: #dee2e6 #dee2e6 #f6f6f6;" @endif href="{{ admin_url('compass/commands') }}">{{ __('suda_lang::press.commands') }}</a>
  </li> --}}
  
  <li class="nav-item">
      <a class="nav-link @if($active_tab=='certificate') active @endif" @if($active_tab=='certificate') style="background-color: #f6f6f6;border-color: #dee2e6 #dee2e6 #f6f6f6;" @endif href="{{ admin_url('certificate') }}">{{ __('suda_lang::press.certificate') }}</a>
  </li>
</ul>