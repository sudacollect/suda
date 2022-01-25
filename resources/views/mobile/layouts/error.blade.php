<!DOCTYPE html>
<html lang="zh-CN">
<head>
    
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="Suda, hello@suda.gtd.xyz">
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
        
        <nav class="navbar navbar-site  navbar-static-top" style="background:transparent;">
            
            <div class="container container-flex">
                <div class="navbar-header" style="background:transparent;margin: 0 auto;display: block;float: initial;">

                    <!-- Collapsed Hamburger -->
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <!-- Branding Image -->
                    <a class="navbar-brand" title="{{ config('app.name', 'Suda') }}" href="{{ url('/') }}" style="display:none;">
                        {{ config('app.name', 'Suda') }}
                    </a>
                </div>

                
            </div>
            
        </nav>

        @yield('content')
        
    </div>
    
    
    <!-- Scripts -->
    <script src="{{ suda_asset('js/app_site.js') }}"></script>
    
    @stack('scripts')


</body>
</html>
