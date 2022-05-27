@extends('view_path::layouts.default')



@section('content')
<div class="container">
    <div class="row suda-row">
        
        <h1 class="display-3 page-title page-tabs-title">{{ __('suda_lang::press.menu_items.tool_compass') }}</h1>
        
        @include('view_suda::admin.compass.tabs',['active'=>'index'])
        
        
        
        
        <div class="col-sm-12 suda_page_body">
            
                <div class="card">
                    <div class="card-header">
                        <i class="ion-layers"></i>&nbsp;&nbsp;创建应用
                    </div>
                    
                    <div class="card-body">
                        
                        创建应用的说明.<br>
                        
                        
                    </div>
                </div>
            
        </div>
        
        <div class="col-sm-12 suda_page_body">
            
                <div class="card">
                    <div class="card-header">
                        <i class="ion-color-palette"></i>&nbsp;&nbsp;模板制作
                    </div>
                    
                    <div class="card-body">
                        
                        创建应用的说明.<br>
                        
                        
                    </div>
                </div>
            
        </div>
        
        <div class="col-sm-12 suda_page_body">
            
                <div class="card">
                    <div class="card-header">
                        <i class="ion-grid"></i>&nbsp;&nbsp;常用函数
                    </div>
                    
                    <div class="card-body">
                        
                        创建应用的说明.<br>
                        
                        
                    </div>
                </div>
            
        </div>
        
        <div class="col-sm-12 suda_page_body">
            
                <div class="card">
                    <div class="card-header">
                        <i class="ion-help-buoy"></i>&nbsp;&nbsp;系统参数
                    </div>
                    
                    <div class="card-body">
                        
                        创建应用的说明.<br>
                        
                        
                    </div>
                </div>
            
        </div>

        
        
    </div>
</div>
@endsection
