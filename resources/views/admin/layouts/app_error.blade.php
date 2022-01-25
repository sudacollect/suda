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
    
    @stack('styles')
    
    <!-- Scripts -->
    <script>
        window.suda = {csrfToken:"{{ csrf_token() }}"}
    </script>
</head>
<body class="bg-white">
    <div id="app">
        <nav class="navbar navbar-light fixed-top navbar-nologin">

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Left Side Of Navbar -->
                <ul class="nav navbar-nav">
                    
                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="nav navbar-nav navbar-operate">
                    @include('view_suda::admin.layouts.operate_top')
                    
                    <!-- support language -->
                    {{-- @include('view_suda::admin.layouts.top_language') --}}
                </ul>
            </div>
        </nav>

        @yield('content')
    </div>
    
    <!-- Scripts -->
    <script src="{{ suda_asset('js/app.js') }}"></script>
    <script>
        jQuery(document).ready(function($){
            $('.ajaxPassword').on('click',function(e){
                e.preventDefault();
                var aurl = $(this).attr('href');
                
                $.ajax({
                    type    : 'GET', 
                    url     : aurl,
                    cache   : false,
                    error : (function(xhr){
                        if(xhr.status == 422){
                            var errors = xhr.responseJSON;
                            suda.modal(errors.error,'warning');
                        }
                        $(this).removeAttr('disabled');
                    }),
                    fail : (function() {
                            suda.modal('加载失败，请重试');
                            $(this).removeAttr('disabled');
                      })
                });
            })
        });
    </script>
</body>
</html>
