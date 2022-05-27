<!DOCTYPE html>
<html lang="zh-CN">
<head>
    
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, maximum-scale=1.0, user-scalable=0, initial-scale=1">
    <meta name="author" content="Suda, hello@suda.gtd.xyz">
    
    <title>{{ metas($sdcore) }}</title>
    <meta name="keywords" itemprop="keywords" content="{{ $sdcore->keywords }}">
    <meta name="description" property="og:description" itemprop="description" content="{{ $sdcore->description }}">
    
    <link rel="shortcut icon" href="{{ $favicon }}" type="image/x-icon">
    <meta property="og:site_name" content="{{ $sdcore->settings->site_name }}">
    <meta property="og:title" itemprop="name" content="{{ metas($sdcore->title) }}">
    <meta property="og:image" itemprop="image" content="{{ $sdcore->settings->og_image }}">
    
    <link rel="stylesheet" href="{{ suda_asset('css/app_site.css') }}">
    
    

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
        
            <div class="col-lg-8 pt-3 px-3 mx-auto">
              <header class="d-flex align-items-center pb-3 mb-5 border-bottom">
                <a href="/" class="d-flex align-items-center text-dark text-decoration-none">
                  

                  
<svg width="30px" height="32px" class="me-2" viewBox="0 0 30 32" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
  <g id="demo-page" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
      <g id="site_logo" fill="#404243" fill-rule="nonzero">
          <path d="M29.4736842,0 L29.4736842,32 L2.13162821e-14,32 L2.13162821e-14,0 L29.4736842,0 Z M20.5810526,12.4294737 C18.4477193,12.4294737 16.802807,12.8842105 15.6463158,13.7936842 C14.4898246,14.7031579 13.9115789,15.8540351 13.9115789,17.2463158 C13.9115789,18.3915789 14.2147368,19.2898246 14.8210526,19.9410526 C15.4273684,20.5922807 16.157193,21.0638596 17.0105263,21.3557895 C17.8638596,21.6477193 18.9866667,21.917193 20.3789474,22.1642105 C21.5691228,22.3887719 22.5010526,22.6021053 23.1747368,22.8042105 C23.8484211,23.0063158 24.4154386,23.3207018 24.8757895,23.7473684 C25.3361404,24.1740351 25.5663158,24.7578947 25.5663158,25.4989474 C25.5663158,26.5319298 25.1452632,27.3403509 24.3031579,27.9242105 C23.4610526,28.5080702 22.1529825,28.8 20.3789474,28.8 C19.1438596,28.8 17.9592982,28.5978947 16.8252632,28.1936842 C15.6912281,27.7894737 14.7649123,27.2842105 14.0463158,26.6778947 L14.0463158,26.6778947 L13.2715789,28.0252632 C13.9901754,28.6764912 14.9894737,29.2154386 16.2694737,29.6421053 C17.5494737,30.0687719 18.8968421,30.2821053 20.3115789,30.2821053 C22.5122807,30.2821053 24.2189474,29.8442105 25.4315789,28.9684211 C26.6442105,28.0926316 27.2505263,26.9249123 27.2505263,25.4652632 C27.2505263,24.3649123 26.9585965,23.5003509 26.3747368,22.8715789 C25.7908772,22.242807 25.0778947,21.7880702 24.2357895,21.5073684 C23.3936842,21.2266667 22.2877193,20.962807 20.9178947,20.7157895 C19.682807,20.4687719 18.722807,20.2385965 18.0378947,20.0252632 C17.3529825,19.8119298 16.7747368,19.4863158 16.3031579,19.0484211 C15.8315789,18.6105263 15.5957895,17.9985965 15.5957895,17.2126316 C15.5957895,16.2245614 16.0112281,15.4273684 16.8421053,14.8210526 C17.6729825,14.2147368 18.9080702,13.9115789 20.5473684,13.9115789 C21.5129825,13.9115789 22.4561404,14.0463158 23.3768421,14.3157895 C24.2975439,14.5852632 25.117193,14.9782456 25.8357895,15.4947368 L25.8357895,15.4947368 L26.6105263,14.1473684 C25.914386,13.6308772 25.0161404,13.2154386 23.9157895,12.9010526 C22.8154386,12.5866667 21.7038596,12.4294737 20.5810526,12.4294737 Z" id="s-box"></path>
      </g>
  </g>
</svg>
                  
                  <span class="fs-4">{{ $sdcore->settings->site_name }}</span>
                </a>
              </header>
            </div>
        
        
            @yield('content')
        
            @include('view_suda::mobile.layouts.footer')
        

        
    </div>
    
    
    <!-- Scripts -->
    <script src="{{ suda_asset('js/app_site.js') }}"></script>
    
    
    @stack('scripts')
    


</body>
</html>
