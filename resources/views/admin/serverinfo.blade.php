@extends('view_path::layouts.default')



@section('content')
<div class="container">
    <div class="row suda-row">
        <h1 class="page-title">服务器信息</h1>
        
        
        <div class="col-sm-12 suda_page_body">
            
                <div class="card">
                
                    <div class="card-body">
                        
                        
                        <iframe width="100%" style="min-height:100vh;" frameborder=0 src="{{ admin_url('serverinfo/phpinfo') }}">
                            
                        </iframe>
                    </div>
                    
                    <div class="card-footer">
                        此页面数据由服务器自动生成.
                    </div>
                    
                </div>
            
        </div>
        

        
        
    </div>
</div>
@endsection
