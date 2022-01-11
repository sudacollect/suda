<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <title>{{ metas($sdcore) }}</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="Suda">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex,nofollow">
    
    
    <link rel="shortcut icon" href="{{ suda_asset('images/logo/favicon.png') }}" type="image/x-icon">

    @stack('styles-head')
    
    @if(config('app.debug'))
    <link rel="stylesheet" href="{{ suda_asset('css/app.css') }}">
    <link rel="stylesheet" href="/theme/admin/{{ $sdcore->theme }}/design/style.css">
    @else
    <link rel="stylesheet" href="{{ suda_asset('css/app.css') }}">
    <link rel="stylesheet" href="/theme/admin/{{ $sdcore->theme }}/design/style.min.css">
    @endif
    
    @stack('styles')
    
    <script>
        window.suda = window.suda || {};
        suda.meta = { csrfToken: "{{csrf_token()}}",url:"{{url('/')}}",adminPath:"{{ $sdcore->admin_path }}" };
    </script>
        
    <!-- Scripts -->
    @stack('scripts-head')
        
</head>
<body class="suda-body">
    @if (Auth::guard('operate')->check())
        @if(!isset($without_sidebar))
        @include('view_path::layouts.sidebar')
        @endif
    @endif 

    
    <nav class="{{ $navbar_style }}">
        
        <button type="button" class="navbar-take-toggle navbar-side navbar-side-sm collapsed" style="float:left">
            <span class="sr-only">Menu</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        
        <a class="navbar-brand" href="{{ admin_url('/') }}">
            {{ config('app.name', 'Suda') }}
        </a>
        
        <button type="button" class="navbar-take-toggle navbar-side collapsed" style="float:left">
            <span class="sr-only">Menu</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        
        

        
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            
            {{-- @if(count($custom_navi) < 1 && (isset($sdcore->settings->dashboard->show_breadcrumb) && $sdcore->settings->dashboard->show_breadcrumb==1))
            @include('view_path::layouts.menu_breadcrumb')
            @endif --}}

            @if (count($custom_navi) > 0)
            <ul class="nav navbar-nav navbar-custom-navi pl-3 align-self-start">
                @foreach($custom_navi as $navi)
                <li class="nav-item">
                    <a class="nav-link @if(isset($current_navi) && $current_navi==$navi['name']) active @endif" href="{{ admin_url($navi['url']) }}" target="{{ $navi['target'] }}" title="{{ $navi['name'] }}">
                        @if(isset($navi['blade_icon']))
                        @svg($navi['blade_icon'],['width'=>'16px'])
                        @else
                        @if(isset($navi['icon'])) <i class="{{ $navi['icon'] }}"></i>@endif
                        @endif
                        {{ $navi['name'] }}
                    </a>
                </li>
                @endforeach
            </ul>
            @endif

            
            <!-- Right Side Of Navbar -->
            <ul class="nav navbar-nav navbar-operate">
                        
                        
                    {{-- <li class="nav-item">
                        @if($soperate->user_role==2)
                        <a href="{{ admin_url('entry/extensions') }}" title="Dashboard" class="nav-link"><i class="ion-settings"></i></a>
                        @else
                        <a href="{{ admin_url('/') }}" title="Dashboard" class="nav-link"><i class="ion-settings"></i></a>
                        @endif
                    </li> --}}

                    <li class="nav-item">
                        <a href="{{ url('/home') }}" target="_blank" title="{{ __('suda_lang::press.visit_homepage') }}" class="nav-link"><i class="ion-home"></i></a>
                    </li>
                
                    <!-- Authentication Links -->
                    @if (Auth::guard('operate')->guest())
                        <li class="nav-item"><a href="{{ url('/login') }}">{{ trans('suda_lang::auth.login') }}</a></li>
                        <li class="nav-item"><a href="{{ url('/register') }}">{{ trans('suda_lang::auth.register') }}</a></li>
                    @else
                    
                    
                    @include('view_suda::admin.layouts.operate_top')
                    
                    <!-- support language -->
                    @include('view_suda::admin.layouts.top_language')
                    
                    @endif
            </ul>
        
        </div>

    </nav>
    
    <div id="app" class="suda-app">
        
        @if (Auth::guard('operate')->check())
        
        @php
        $suda_flat_style = '';
        if(config('sudaconf.sidemenu_style','')=='pro')
        {
            $suda_flat_style = 'suda-flat-lg ';
            if($sdcore->sidemenus['has_children'])
            {
                $suda_flat_style .= ' suda-flat-md suda-flat-with-sub-menus';
            }
        }elseif($sidemenu_style=='icon')
        {
            $suda_flat_style = 'suda-flat-lg ';
        }

        @endphp
        <div class="suda-flat  {{ $suda_flat_style }}">
        
        @if(isset($sdcore->settings->dashboard->show_breadcrumb) && $sdcore->settings->dashboard->show_breadcrumb==1 && (!isset($no_breadcrumb) || !$no_breadcrumb))
            @include('view_path::layouts.menu_float_breadcrumb')
        @endif
        
        @include('view_path::layouts.toast')
        
        @yield('content')
        
        </div>
        

        @endif
        
    </div>

    
    
    {{-- @if (session('status'))
    <div class="modal-alert @if(isset(session('status')['keep'])) alert-keep @endif">
        <div class="alert alert-{{ session('status')['code'] }}">
            {!! session('status')['msg'] !!}
        </div>
    </div>
    @endif --}}
    
    <script src="{{ suda_asset('js/app.js') }}"></script>
    <script src="{{ suda_asset('js/app.vendor.js') }}"></script>
    
    
    @stack('scripts')
    
</body>
</html>
