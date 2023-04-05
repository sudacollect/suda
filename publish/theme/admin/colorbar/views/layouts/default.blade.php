<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="Suda">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex,nofollow">
    
    <title>{{ metas($sdcore) }}</title>

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
        suda.meta = { csrfToken: "{{csrf_token()}}",url:"{{url('/')}}",adminPath:"{{ $sdcore->admin_path }}" }
    </script>
        
    <!-- Scripts -->
    @stack('scripts-head')
        
</head>
<body class="suda-body">
    <div id="app" class="suda-app">

        <div class="suda-flat suda-flat-fluid">
            <nav class="{{ $navbar_style }}" style="padding-left:0">
                
                <a class="navbar-brand" href="{{ admin_url('/') }}">
                    {{ config('app.name', 'Suda') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    
                    <ul class="nav navbar-nav navbar-menu">
                        @if (Auth::guard('operate')->check())
                            
                            @include('view_suda::admin.menu.display.topbar',$sdcore->sidemenus)
                            
                        @endif
                        
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="nav navbar-nav ms-auto">
                        

                        
                        <li class="nav-item">
                            @if(\Gtd\Suda\Auth\OperateCan::operation($soperate))
                            <a href="{{ admin_url('entry/extensions') }}" title="控制面板" class="nav-link"><i class="ion-settings"></i></a>
                            @else
                            <a href="{{ admin_url('/') }}" title="控制面板" class="nav-link"><i class="ion-settings"></i></a>
                            @endif
                        </li>

                        <li class="nav-item">
                            <a href="{{ url('/home') }}" target="_blank" title="{{ __('suda_lang::press.visit_homepage') }}" class="nav-link"><i class="ion-home"></i></a>
                        </li>
                        
                        <!-- Authentication Links -->
                        @if (Auth::guard('operate')->guest())
                            <li class="nav-item"><a href="{{ url('/login') }}">{{ trans('suda_lang::auth.login') }}</a></li>
                            <li class="nav-item"><a href="{{ url('/register') }}">{{ trans('suda_lang::auth.register') }}</a></li>
                        @else
                            
                            
                            @include('view_suda::admin.layouts.operate_top')
                            
                            
                            
                        @endif
                    </ul>
                    
                    
                    
                </div>

            </nav>
            
            
            @if(isset($sdcore->settings->dashboard->show_breadcrumb) && $sdcore->settings->dashboard->show_breadcrumb==1)
            <div class="container-fluid" style="margin-top:1.5rem">
                @include('view_suda::admin.layouts.menu_breadcrumb')
            </div>
            @endif

            @include('view_path::layouts.toast')

        @yield('content')
        
        </div>

    </div>

    <!-- Scripts -->
    <script src="{{ suda_asset('js/app.js') }}"></script>
    <script src="{{ suda_asset('js/app.vendor.js') }}"></script>
    
    
    @stack('scripts')
    
</body>
</html>
