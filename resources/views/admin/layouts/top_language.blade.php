<li class="nav-item dropdown switch-language">
    <a href="#" class="nav-link dropdown-toggle" role="button" id="navbar-language-dropdown" data-bs-toggle="dropdown"  aria-haspopup="true" aria-expanded="false">
        @if(config('app.locale')=='zh_CN')
        <i class="icon-chinese"></i>
        @elseif(config('app.locale')=='en')
        <i class="icon-english"></i>
        @else
        <i class="icon-english"></i>
        @endif
        {{ trans('suda_lang::press.switch_language') }} <span class="caret"></span>
    </a>

    <div class="dropdown-menu " aria-labelledby="navbar-language-dropdown" >
        <a class="dropdown-item" href="{{ url($sdcore->admin_path.'/setting/switch-language/en') }}"><i class="icon-english"></i>English</a>
        <a class="dropdown-item" href="{{ url($sdcore->admin_path.'/setting/switch-language/zh_CN') }}"><i class="icon-chinese"></i>中文</a>
    </div>
    
</li>