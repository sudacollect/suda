@extends('view_path::layouts.default')



@section('content')
<div class="container">
    <div class="row suda-row">
        
        <h1 class="display-3 page-title page-tabs-title">{{ __('suda_lang::press.menu_items.tool_compass') }}</h1>
        
        @include('view_suda::admin.compass.tabs',['active'=>'about'])
        
        
        {{-- <div class="col-sm-4 suda_page_body press-compass">
                <div class="card">
                    <div class="card-body partner">
                        <a href="{{ admin_url('certificate') }}" target="_blank" title="授权信息" style="display:block;width:100%">授权信息</a>
                    </div>
                </div>
        </div>
        
        <div class="col-sm-4 suda_page_body press-compass">
                <div class="card">
                
                    <div class="card-body document">
                        <a href="{{ url('https://docs.panel.cc') }}" target="_blank" title="文档" style="display:block;width:100%">使用说明</a>
                    </div>
                </div>
        </div>
        
        
        <div class="col-sm-4 suda_page_body press-compass">
            
                <div class="card">
                
                    <div class="card-body store">
                        <a href="{{ url('https://panel.cc') }}" target="_blank" title="应用市场" style="display:block;width:100%">应用市场</a>
                    </div>
                </div>
        </div> --}}
        
        <div class="col-12 suda_page_body press-compass-addition mt-3">
            
                <div class="card">
                    
                    <div class="card-body">
                        <p style="text-align:center;"><img src="{{ suda_asset('images/addition/aboutus.png') }}" style="margin:0 auto;max-width:120px;"></p>
                        
                       <h4>About</h4>
                       <p>
                           {{ __('suda_lang::press.about_page.brief') }}
                       </p>
                       
                       <h4>Vision</h4>
                       
                       <p>
                        {{ __('suda_lang::press.about_page.target') }}
                       </p>
                       
                       
                       <p>
                        {{ __('suda_lang::press.about_page.vision') }}
                       </p>
                       
                       
                    </div>
                </div>
            
        </div>

        

        
        
    </div>
</div>
@endsection
