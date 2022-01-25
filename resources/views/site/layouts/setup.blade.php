<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name=robots content=noindex,nofollow>
    <link rel="shortcut icon" href="{{ suda_asset('images/logo/favicon.png') }}" type="image/x-icon">
    
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@if(isset($sdcore)) {{ metas($sdcore) }} @else {{ metas() }} @endif</title>

    
    
    <link rel="stylesheet" href="{{ suda_asset('css/app.css') }}">
    
    <style>
        .navbar .navbar-brand{display:inline-block}
        .navbar-brand{
            text-indent: -9999px;
            width:80px;
            background: url("{{ suda_asset('images/logo/logo_blue.png') }}") left center no-repeat;
            background-size: contain;
        }
    </style>
    @stack('styles')
    
    <!-- Scripts -->
    <script>
        window.suda = {csrfToken:"{{ csrf_token() }}"}
        suda.meta = { csrfToken: "{{csrf_token()}}",url:"{{url('/')}}" }
    </script>
    
    <!-- Scripts -->
    <script src="{{ suda_asset('js/app.js') }}"></script>
    
</head>
<body>
    <div id="app" class="suda-app">
        
        <nav class="navbar navbar-expand-sm navbar-light bg-white navbar-nologin">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">
                    {{ config('app.name', 'Suda') }}
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    <!-- Left Side Of Navbar -->
                    <ul class="nav navbar-nav">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="nnavbar-nav ms-auto">
                        <li class="nav-item">
                            <a href="{{ url('https://suda.gtd.xyz') }}" class="nav-link">
                                <i class="ion-home"></i>&nbsp;Suda
                            </a>
                        </li>

                    </ul>
                </div>
            </div>
        </nav>

        @yield('content')

    </div>
    
    
        
    @stack('scripts')
    
</body>
</html>
