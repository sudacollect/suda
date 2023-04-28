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
   
    <div id="app" class="app-of-suda row">
      
      <div class="col-lg-8 pt-3 px-3 mx-auto">
        <header class="d-flex align-items-center pb-3 mb-1 border-bottom">
          <a href="/" class="d-flex align-items-center text-dark text-decoration-none">
            @if(isset($sdcore->settings->site->logo) && $sdcore->settings->site->logo)
            <img src="{{ $sdcore->settings->site->logo }}" alt="{{ $sdcore->settings->site_name }}" title="{{ $sdcore->settings->site_name }}" style="width:80px;" />
            @else
            <div class="logo me-3" style="width:80px">
              <svg viewBox="0 0 160 53" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                  <g id="Logo2021" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                      <g id="240" transform="translate(-40.000000, -94.000000)" fill="#313131" fill-rule="nonzero">
                          <path d="M88.1596147,94 L88.1596147,146.287582 L40,146.287582 L40,94 L88.1596147,94 Z M86.5084279,95.6511868 L41.6511868,95.6511868 L41.6511868,144.636395 L86.5084279,144.636395 L86.5084279,95.6511868 Z M73.629171,114.309598 C75.463823,114.309598 77.2801284,114.566449 79.0780874,115.080151 C80.8760463,115.593854 82.3437679,116.272675 83.4812521,117.116615 L82.2153423,119.318197 C81.041165,118.474258 79.7018691,117.832129 78.1974544,117.391813 C76.6930398,116.951496 75.1519321,116.731338 73.5741314,116.731338 C70.8955395,116.731338 68.8774223,117.226694 67.5197798,118.217406 C66.1621374,119.208118 65.4833161,120.510721 65.4833161,122.125215 C65.4833161,123.409471 65.8685931,124.409357 66.6391469,125.124871 C67.4097007,125.840385 68.3545465,126.372434 69.4736842,126.721018 C70.5928219,127.069602 72.1614494,127.445706 74.1795666,127.849329 C76.417842,128.252953 78.2249742,128.684096 79.6009632,129.142759 C80.9769522,129.601422 82.1419562,130.344456 83.0959752,131.371861 C84.0499943,132.399266 84.5270038,133.811948 84.5270038,135.609907 C84.5270038,137.994955 83.5362917,139.902993 81.5548676,141.334021 C79.5734434,142.76505 76.7847724,143.480564 73.1888545,143.480564 C70.877193,143.480564 68.6756106,143.13198 66.5841073,142.434813 C64.4926041,141.737645 62.8597638,140.857012 61.6855865,139.792914 L62.9514964,137.591331 C64.1256737,138.582043 65.6392616,139.407637 67.4922601,140.068111 C69.3452586,140.728586 71.2808164,141.058824 73.2989336,141.058824 C76.1976838,141.058824 78.3350533,140.581814 79.7110423,139.627795 C81.0870313,138.673776 81.7750258,137.352827 81.7750258,135.664947 C81.7750258,134.454076 81.3989221,133.500057 80.6467148,132.80289 C79.8945075,132.105722 78.9680083,131.592019 77.8672171,131.261782 C76.7664259,130.931545 75.2436647,130.582961 73.2989336,130.21603 C71.0239651,129.812407 69.1893132,129.37209 67.7949776,128.895081 C66.4006421,128.418071 65.2081183,127.647517 64.2174063,126.583419 C63.2266942,125.519321 62.7313381,124.0516 62.7313381,122.180255 C62.7313381,119.905286 63.6761839,118.024768 65.5658755,116.5387 C67.455567,115.052632 70.1433322,114.309598 73.629171,114.309598 Z M120.137599,114.529756 L120.137599,143.260406 L117.4957,143.260406 L117.4957,137.095975 C116.541681,139.114092 115.128999,140.68272 113.257654,141.801858 C111.386309,142.920995 109.184727,143.480564 106.652907,143.480564 C102.910217,143.480564 99.9747735,142.434813 97.8465772,140.343309 C95.7183809,138.251806 94.6542828,135.242977 94.6542828,131.316821 L94.6542828,114.529756 L97.4062607,114.529756 L97.4062607,131.096663 C97.4062607,134.325651 98.2318541,136.784084 99.8830409,138.471964 C101.534228,140.159844 103.864236,141.003784 106.873065,141.003784 C110.102053,141.003784 112.661392,139.994725 114.551084,137.976608 C116.440775,135.958491 117.385621,133.243206 117.385621,129.830753 L117.385621,114.529756 L120.137599,114.529756 Z M160.096319,102.421053 L160.096319,143.260406 L157.399381,143.260406 L157.399381,136.49054 C156.261897,138.728816 154.665749,140.453388 152.610939,141.664259 C150.556129,142.875129 148.226121,143.480564 145.620915,143.480564 C142.942323,143.480564 140.520583,142.856782 138.355693,141.609219 C136.190804,140.361656 134.493751,138.637083 133.264534,136.435501 C132.035317,134.233918 131.420709,131.720445 131.420709,128.895081 C131.420709,126.069717 132.035317,123.54707 133.264534,121.327141 C134.493751,119.107212 136.190804,117.38264 138.355693,116.153423 C140.520583,114.924206 142.942323,114.309598 145.620915,114.309598 C148.189428,114.309598 150.491916,114.915033 152.52838,116.125903 C154.564843,117.336773 156.170164,119.024653 157.344341,121.189542 L157.344341,102.421053 L160.096319,102.421053 Z M145.786034,141.003784 C147.987616,141.003784 149.959867,140.490081 151.702786,139.462676 C153.445706,138.435271 154.821695,137.004243 155.830753,135.169591 C156.839812,133.334939 157.344341,131.243435 157.344341,128.895081 C157.344341,126.546726 156.839812,124.455223 155.830753,122.620571 C154.821695,120.785919 153.445706,119.35489 151.702786,118.327485 C149.959867,117.30008 147.987616,116.786378 145.786034,116.786378 C143.584451,116.786378 141.6122,117.30008 139.869281,118.327485 C138.126362,119.35489 136.750373,120.785919 135.741314,122.620571 C134.732255,124.455223 134.227726,126.546726 134.227726,128.895081 C134.227726,131.243435 134.732255,133.334939 135.741314,135.169591 C136.750373,137.004243 138.126362,138.435271 139.869281,139.462676 C141.6122,140.490081 143.584451,141.003784 145.786034,141.003784 Z M200,114.529756 L200,143.260406 L197.303062,143.260406 L197.303062,136.49054 C196.165577,138.728816 194.56943,140.453388 192.51462,141.664259 C190.45981,142.875129 188.129802,143.480564 185.524596,143.480564 C182.846004,143.480564 180.424263,142.856782 178.259374,141.609219 C176.094485,140.361656 174.397431,138.637083 173.168215,136.435501 C171.938998,134.233918 171.324389,131.720445 171.324389,128.895081 C171.324389,126.069717 171.938998,123.54707 173.168215,121.327141 C174.397431,119.107212 176.094485,117.38264 178.259374,116.153423 C180.424263,114.924206 182.846004,114.309598 185.524596,114.309598 C188.093109,114.309598 190.395597,114.915033 192.432061,116.125903 C194.468524,117.336773 196.073845,119.024653 197.248022,121.189542 L197.248022,114.529756 L200,114.529756 Z M185.689714,141.003784 C187.891297,141.003784 189.863548,140.490081 191.606467,139.462676 C193.349387,138.435271 194.725376,137.004243 195.734434,135.169591 C196.743493,133.334939 197.248022,131.243435 197.248022,128.895081 C197.248022,126.546726 196.743493,124.455223 195.734434,122.620571 C194.725376,120.785919 193.349387,119.35489 191.606467,118.327485 C189.863548,117.30008 187.891297,116.786378 185.689714,116.786378 C183.488132,116.786378 181.515881,117.30008 179.772962,118.327485 C178.030042,119.35489 176.654053,120.785919 175.644995,122.620571 C174.635936,124.455223 174.131407,126.546726 174.131407,128.895081 C174.131407,131.243435 174.635936,133.334939 175.644995,135.169591 C176.654053,137.004243 178.030042,138.435271 179.772962,139.462676 C181.515881,140.490081 183.488132,141.003784 185.689714,141.003784 Z" id="s-box"></path>
                      </g>
                  </g>
              </svg>
            </div>
            @endif
            {{-- <span class="fs-4">{{ $sdcore->settings->site_name }}</span> --}}
          </a>
        </header>
      </div>

        
        
        @yield('content')
    
        @include('view_suda::site.layouts.footer')
    

        
    </div>
    
    
    <!-- Scripts -->
    <script src="{{ suda_asset('js/app_site.js') }}"></script>
    
    
    @stack('scripts')
    


</body>
</html>
