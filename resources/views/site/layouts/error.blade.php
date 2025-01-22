<!DOCTYPE html>
<html lang="zh-CN">
<head>
    
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="Suda, dev@panel.cc">
    <meta name=robots content=noindex,nofollow>
    
    <title>{{ metas($sdcore) }}</title>
    <meta name="keywords" itemprop="keywords" content="{{ $sdcore->keywords }}">
    <meta name="description" property="og:description" itemprop="description" content="{{ $sdcore->description }}">
    
    <meta property="og:site_name" content="{{ $sdcore->settings->site_name }}">
    <meta property="og:title" itemprop="name" content="{{ metas($sdcore->title) }}">
    <meta property="og:image" itemprop="image" content="{{ $sdcore->settings->og_image }}">
    
    <link rel="stylesheet" href="{{ suda_asset('css/app_site.css') }}">
    
    @stack('styles')
    
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script>
        window.suda = window.suda || {};
        suda.meta = { csrfToken: "{{csrf_token()}}",url:"{{url('/')}}" }
    </script>
    
    @stack('scripts-head')
    
</head>
<body>
   
    <div id="app" class="app-of-suda">
        
        <nav class="navbar navbar-expand-sm navbar-dark bg-primary">
            
            <!-- Branding Image -->
            <a class="navbar-brand" title="{{ $sdcore->settings->site_name }}" href="{{ url('/home') }}" >
            @if(isset($logo))<img src="{{ $logo }}" width="80"> @else <img src="{{ suda_asset('images/site/logo_white.png') }}" width="80">@endif
            </a>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                  <li class="nav-item @if(isset($active_tab) && $active_tab=='home') active @endif">
                    <a class="nav-link" href="{{ url('/') }}"><i class="ion-home"></i>&nbsp;首页 <span class="sr-only">(current)</span></a>
                  </li>
                  <li class="nav-item @if(isset($active_tab) && $active_tab=='pages') active @endif">
                    <a class="nav-link" href="{{ url('pages') }}"><i class="ion-reader"></i>&nbsp;页面</a>
                  </li>
                  <li class="nav-item @if(isset($active_tab) && $active_tab=='articles') active @endif">
                    <a class="nav-link" href="{{ url('articles') }}"><i class="ion-newspaper"></i>&nbsp;文章</a>
                  </li>
                  
                </ul>
              </div>

            
        </nav>

        @yield('content')
        
    </div>
    
    
    <!-- Scripts -->
    <script src="{{ suda_asset('js/app_site.js') }}"></script>
    
    @stack('scripts')
    

</body>
</html>
