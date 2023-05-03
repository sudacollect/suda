@extends('view_path::layouts.default')



@section('content')
<div class="container">
    <div class="row suda-row">
        <div class="page-heading">
            <h1 class="page-title">
                <i class="ion-settings"></i>
                {{ __('suda_lang::press.seo_info') }}
            </h1>
            
        </div>
        
        <div class="col-12 suda_page_body">
            @include('view_suda::admin.home.settings_tabs',['active'=>'seo'])
            
            <div class="card card-with-tab">
                <div class="card-body">
                    
                    <form class="form-ajax"  method="POST" action="{{ admin_url('setting/seo') }}" role="form">
                      @csrf
                      
                      
                      <div class="row mb-3">
                       
                        
                        <label for="title" class="col-sm-2 col-form-label ">
                            {{ __('suda_lang::press.settings.seo_title') }}
                        </label>
                        
                        <div class="col-sm-4">
                            <input type="text" class="form-control" name="title" placeholder="title" value="@if(isset($settings['title'])){{ $settings['title'] }}@endif">
                        </div>
                      </div>
                      
                      
                      <div class="row mb-3">
                       
                        
                        <label for="keywords" class="col-sm-2 col-form-label ">
                            {{ __('suda_lang::press.settings.keywords') }}
                           </label>
                        
                        <div class="col-sm-4">
                            <input type="text" class="form-control" name="keywords" placeholder="keywords 英文逗号,分割" value="@if(isset($settings['keywords'])){{ $settings['keywords'] }}@endif">
                        </div>
                      </div>
                      
                      
                      <div class="row mb-3">
                       
                        
                        <label for="description" class="col-sm-2 col-form-label ">
                            {{ __('suda_lang::press.settings.description') }}
                           </label>
                        
                        <div class="col-sm-4">
                            <textarea class="form-control" rows=5 name="description" placeholder="description">@if(isset($settings['description'])){{ $settings['description'] }}@endif</textarea>
                        </div>
                      </div>
                      
                      
                      
                      <div class="row mb-3">
                          <div class="buttons col-sm-4 offset-sm-2">
                              <button type="submit" class="btn btn-primary">{{ __('suda_lang::press.submit_save') }}</button>
                          </div>
                          
                      </div>

                    </form>
                </div>
            </div>
            
        </div>
        
    </div>
</div>
@endsection
