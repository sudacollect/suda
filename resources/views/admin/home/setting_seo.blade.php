@extends('view_path::layouts.default')



@section('content')
<div class="container">
    <div class="row suda-row">
        <div class="page-heading">
            <h1 class="page-title">
                <i class="zly-gear-s"></i>
                SEO设置
            </h1>
            
        </div>
        
        <div class="col-12 suda_page_body">
            @include('view_suda::admin.home.settings_tabs',['active'=>'seo'])
            
            <div class="card card-with-tab">
                <div class="card-body">
                    
                    <form class="form-ajax"  method="POST" action="{{ admin_url('setting/seo') }}" role="form">
                      {{ csrf_field() }}
                      
                      
                      <div class="form-group row">
                       
                        
                        <label for="title" class="col-sm-2 col-form-label text-right">
                               SEO标题
                        </label>
                        
                        <div class="col-sm-4">
                            <input type="text" class="form-control" name="title" placeholder="标题" value="@if(isset($settings->values['title'])){{ $settings->values['title'] }}@endif">
                        </div>
                      </div>
                      
                      
                      <div class="form-group row">
                       
                        
                        <label for="keywords" class="col-sm-2 col-form-label text-right">
                               SEO关键词
                           </label>
                        
                        <div class="col-sm-4">
                            <input type="text" class="form-control" name="keywords" placeholder="关键词 英文逗号,分割" value="@if(isset($settings->values['keywords'])){{ $settings->values['keywords'] }}@endif">
                        </div>
                      </div>
                      
                      
                      <div class="form-group row">
                       
                        
                        <label for="description" class="col-sm-2 col-form-label text-right">
                               SEO描述
                           </label>
                        
                        <div class="col-sm-4">
                            <textarea class="form-control" rows=5 name="description" placeholder="描述">@if(isset($settings->values['description'])){{ $settings->values['description'] }}@endif</textarea>
                        </div>
                      </div>
                      
                      
                      
                      <div class="form-group row">
                          <div class="buttons col-sm-4 offset-sm-2">
                              <button type="submit" class="btn btn-primary btn-block">{{ trans('suda_lang::press.submit_save') }}</button>
                          </div>
                          
                      </div>

                    </form>
                </div>
            </div>
            
        </div>
        
    </div>
</div>
@endsection
