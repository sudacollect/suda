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
    
    <nav class="navbar navbar-expand-sm navbar-suda navbar-suda-noside fixed-top navbar-dark bg-coffe @if (Auth::guard('operate')->guest()) navbar-nologin @endif">
            
        <a class="navbar-brand" href="#">
            {{ config('app.name', 'Suda') }}
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">

            @if (isset($custom_navi) && Auth::guard('operate')->check())
            <ul class="nav navbar-nav navbar-custom-navi">
                @foreach($custom_navi as $navi)
                <li class="nav-item">
                    <a class="nav-link" href="{{ admin_url($navi['url']) }}" target="{{ $navi['target'] }}" title="{{ $navi['name'] }}">@if(isset($navi['icon'])) <i class="{{ $navi['icon'] }}"></i>&nbsp;@endif{{ $navi['name'] }}</a>
                </li>
                @endforeach
            </ul>
            @endif

            <!-- Right Side Of Navbar -->
            <ul class="nav navbar-nav navbar-operate">
                        
                        
                    <li class="nav-item">
                        @if($soperate->user_role==2)
                        <a href="{{ admin_url('entry/extensions') }}" title="控制面板" class="nav-link"><i class="ion-settings"></i></a>
                        @else
                        <a href="{{ admin_url('/') }}" title="控制面板" class="nav-link"><i class="ion-settings"></i></a>
                        @endif
                    </li>

                    <li>
                        <a href="{{ url('/home') }}" target="_blank" title="{{ __('suda_lang::press.visit_homepage') }}" class="nav-link"><i class="zlyicon ion-home"></i></a>
                    </li>
                
                <!-- Authentication Links -->
                @if (Auth::guard('operate')->guest())
                    <li><a href="{{ url('/login') }}">{{ __('suda_lang::auth.login') }}</a></li>
                    <li><a href="{{ url('/register') }}">{{ __('suda_lang::auth.register') }}</a></li>
                @else
                    
                    
                    @include('view_suda::admin.layouts.operate_top')
                    
                    <!-- support language -->
                    {{-- @include('view_suda::admin.layouts.top_language') --}}
                    
                    
                @endif
            </ul>
        
        </div>

    </nav>

    <div id="app" class="suda-app">

        @if (Auth::guard('operate')->check())
        
        
        <div class="suda-flat  suda-flat-fluid">
        
        
        
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
