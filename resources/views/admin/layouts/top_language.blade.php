<li class="nav-item dropdown">
    <a href="#" class="nav-link dropdown-toggle" role="button" id="navbar-language-dropdown" data-toggle="dropdown"  aria-haspopup="true" aria-expanded="false">
        @if(config('app.locale')==config('sudaconf.locale','zh_CN'))
        <i class="icon-chinese"></i>
        @else
        <i class="icon-english"></i>
        @endif
        {{ trans('suda_lang::press.switch_language') }} <span class="caret"></span>
    </a>

    <div class="dropdown-menu" aria-labelledby="navbar-language-dropdown" >
        <a class="dropdown-item" href="{{ url('en/'.$sdcore->admin_path.'/index') }}"><i class="icon-english"></i>English</a>
        <a class="dropdown-item" href="{{ url('zh/'.$sdcore->admin_path.'/index') }}"><i class="icon-chinese"></i>中文</a>
    </div>
    
</li>