@extends('view_path::layouts.default')



@section('content')
<div class="container">
    <div class="row suda-row">
        <div class="page-heading">
            <h1 class="page-title">
                <i class="zly-gear-s"></i>
                系统设置
            </h1>
            
        </div>

        
        <div class="col-sm-12 suda_page_body">


            
            @include('view_suda::admin.home.settings_tabs',['active'=>'settings'])
            
            <div class="card card-with-tab">
                
                <div class="card-body">
                    
                    <form class="form-ajax"  method="POST" action="{{ admin_url('setting/site') }}" role="form">
                      {{ csrf_field() }}
                      
                      <div class="form-group row">
                       
                        
                        <label for="site_name" class="col-sm-2 col-form-label text-right">
                            {{ __('suda_lang::press.website_name') }}<i class="optional">*</i>
                           </label>
                        
                        <div class="col-sm-4">
                            <input type="text" class="form-control" name="site_name" placeholder="{{ trans('suda_lang::press.input_placeholder',['column'=>trans('suda_lang::press.system_name')]) }}" value="@if(isset($settings->site_name)){{ $settings->site_name }}@endif">
                        </div>
                      </div>
                      
                      <div class="form-group row">
                        
                        <label for="site_domain" class="col-sm-2 col-form-label text-right">
                               {{ trans('suda_lang::press.domain') }}
                           </label>
                        
                        <div class="col-sm-4">
                            <input type="text" class="form-control" name="site_domain" placeholder="{{ trans('suda_lang::press.input_placeholder',['column'=>trans('suda_lang::press.domain')]) }}" value="@if(isset($settings->site_domain)){{ $settings->site_domain }}@endif">
                        </div>
                      </div>
                      
                      <div class="form-group row">
                        
                        <label for="company_name" class="col-sm-2  col-form-label text-right">
                               {{ trans('suda_lang::press.company_name') }}<i class="optional">*</i>
                           </label>
                        
                        <div class="col-sm-4">
                            <input type="text" class="form-control" name="company_name" placeholder="{{ trans('suda_lang::press.input_placeholder',['column'=>trans('suda_lang::press.company_name')]) }}" value="@if(isset($settings->company_name)){{ $settings->company_name }}@endif">
                        </div>
                      </div>
                      
                      <div class="form-group row">
                          
                        <label for="company_addr" class="col-sm-2  col-form-label text-right">
                               {{ __('suda_lang::press.company_address') }}<i class="optional">*</i>
                           </label>
                        
                        <div class="col-sm-4">
                            <input type="text" class="form-control" name="company_addr" placeholder="{{ trans('suda_lang::press.input_placeholder',['column'=>trans('suda_lang::press.company_address')]) }}" value="@if(isset($settings->company_addr)){{ $settings->company_addr }}@endif">
                        </div>
                      </div>
                      
                      <div class="form-group row">
                        
                        <label for="company_phone" class="col-sm-2  col-form-label text-right">
                               {{ trans('suda_lang::press.company_telephone') }}<i class="optional">*</i>
                        </label>
                        
                        <div class="col-sm-4">
                            <input type="text" class="form-control" name="company_phone" placeholder="{{ trans('suda_lang::press.input_placeholder',['column'=>trans('suda_lang::press.company_telephone')]) }}" value="@if(isset($settings->company_phone)){{ $settings->company_phone }}@endif">
                        </div>

                      </div>
                      
                      <div class="form-group row">
                        
                        <label for="icp_number" class="col-sm-2 col-form-label text-right">
                               {{ trans('suda_lang::press.icp_number') }}<i class="optional">*</i>
                           </label>
                        
                        <div class="col-sm-4">
                            <input type="text" class="form-control" name="icp_number" placeholder="{{ trans('suda_lang::press.input_placeholder',['column'=>trans('suda_lang::press.icp_number')]) }}" value="@if(isset($settings->icp_number)){{ $settings->icp_number }}@endif">
                        </div>
                      </div>
                      
                      <div class="form-group row">
                        
                        <label for="icp_number" class="col-sm-2 col-form-label text-right">
                               {{ trans('suda_lang::press.system_close') }}
                           </label>
                        
                        <div class="col-sm-4 form-inline">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="site_close" id="site_close_0" value="0" @if((isset($settings->site_close) && $settings->site_close==0) || !isset($settings->site_close)) checked @endif>
                                <label class="form-check-label" for="site_close">{{ trans('suda_lang::press.open') }}</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="site_close" id="site_close_1" value="1" @if((isset($settings->site_close) && $settings->site_close==1) || !isset($settings->site_close)) checked @endif>
                                <label class="form-check-label" for="site_close">{{ trans('suda_lang::press.close') }}</label>
                            </div>
                            
                            
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
