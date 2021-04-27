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
                        <a href="{{ url('https://docs.gtd.xyz') }}" target="_blank" title="文档" style="display:block;width:100%">使用说明</a>
                    </div>
                </div>
        </div>
        
        
        <div class="col-sm-4 suda_page_body press-compass">
            
                <div class="card">
                
                    <div class="card-body store">
                        <a href="{{ url('https://suda.gtd.xyz') }}" target="_blank" title="应用市场" style="display:block;width:100%">应用市场</a>
                    </div>
                </div>
        </div> --}}
        
        <div class="col-12 suda_page_body press-compass-addition mt-3">
            
                <div class="card">
                    
                    <div class="card-body">
                        <p style="text-align:center;"><img src="{{ suda_asset('images/addition/aboutus.png') }}" style="margin:0 auto;max-width:120px;"></p>
                        
                       <h4>关于</h4>
                       <p>速搭 - 用于快速开发 Web 应用和 App 应用后台系统。</p>
                       
                       <h4>目标</h4>
                       <p>
                           设计初衷是为了帮助我们自己和很多相似的个人/公司能快速设计和研发自己的业务系统。 我们相信以最便捷的方式开始使用能有助于您专注于自身的业务，同时我们提供软件的持续发展和增值应用。
                           基于我们丰富的经验和对 Web 应用、 App 应用设计实现的最佳实践，它将提供可扩展的接口，方便所有个人/公司都可以很快的上手来实现一个应用或者对接自己的 App。
                           我们相信通过不断提升和发展，在为大家提供便捷的同时，我们也能够和所有客户一道，共同设计优秀的产品体验。                           
                       </p>
                       
                       <h4>愿景</h4>
                       <p>
                           降低 Web 应用研发的成本和门槛<br>
                           分享产品/项目设计实施中的经验，有效/有用的集成模块<br>
                           提高IT基础能力(Just Wish)<br>
                       </p>
                       
                       
                    </div>
                </div>
            
        </div>

        

        
        
    </div>
</div>
@endsection
