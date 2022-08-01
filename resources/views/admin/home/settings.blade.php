@extends('view_path::layouts.default')



@section('content')
<div class="container">
    <div class="row suda-row">
        <div class="page-heading">
            <h1 class="page-title">
                <i class="ion-settings"></i>
                {{ __('suda_lang::press.basic_info') }}
            </h1>
            
        </div>

        
        <div class="col-sm-12 suda_page_body">


            
            @include('view_suda::admin.home.settings_tabs',['active'=>'settings'])
            
            <div class="card card-with-tab">
                
                <div class="card-body">
                    <div class="col-md-4 col-sm-6">
                        <form class="form-ajax"  method="POST" action="{{ admin_url('setting/site') }}" role="form">
                        @csrf
                        
                        <div class="mb-3 form-floating">
                        
                            <input type="text" class="form-control" name="site_name" placeholder="{{ __('suda_lang::press.input_placeholder',['column'=>__('suda_lang::press.system_name')]) }}" value="@if(isset($settings->site_name)){{ $settings->site_name }}@endif">
                                <label for="site_name">
                                    {{ __('suda_lang::press.website_name') }}<i class="optional">*</i>
                                </label>
                        </div>
                        
                        <div class="mb-3 form-floating">
                            <input type="text" class="form-control" name="site_domain" placeholder="{{ __('suda_lang::press.input_placeholder',['column'=>__('suda_lang::press.domain')]) }}" value="@if(isset($settings->site_domain)){{ $settings->site_domain }}@endif">
                            <label for="site_domain">
                                {{ __('suda_lang::press.domain') }}
                            </label>
                            
                        </div>
                        
                        <div class="mb-3 form-floating">
                            <input type="text" class="form-control" name="company_name" placeholder="{{ __('suda_lang::press.input_placeholder',['column'=>__('suda_lang::press.company_name')]) }}" value="@if(isset($settings->company_name)){{ $settings->company_name }}@endif">
                            <label for="company_name">
                                {{ __('suda_lang::press.company_name') }}<i class="optional">*</i>
                            </label>
                        </div>
                        
                        <div class="mb-3 form-floating">
                            <input type="text" class="form-control" name="company_addr" placeholder="{{ __('suda_lang::press.input_placeholder',['column'=>__('suda_lang::press.company_address')]) }}" value="@if(isset($settings->company_addr)){{ $settings->company_addr }}@endif">
                            <label for="company_addr" >
                                {{ __('suda_lang::press.company_address') }}<i class="optional">*</i>
                            </label>
                        </div>
                        
                        <div class="mb-3 form-floating">
                            <input type="text" class="form-control" name="company_phone" placeholder="{{ __('suda_lang::press.input_placeholder',['column'=>__('suda_lang::press.company_telephone')]) }}" value="@if(isset($settings->company_phone)){{ $settings->company_phone }}@endif">
                            <label for="company_phone">
                                {{ __('suda_lang::press.company_telephone') }}<i class="optional">*</i>
                            </label>
                        </div>
                        
                        <div class="mb-3 form-floating">
                            <input type="text" class="form-control" name="icp_number" placeholder="{{ __('suda_lang::press.input_placeholder',['column'=>__('suda_lang::press.icp_number')]) }}" value="@if(isset($settings->icp_number)){{ $settings->icp_number }}@endif">
                            <label for="icp_number">
                                {{ __('suda_lang::press.icp_number') }}
                            </label>
                        </div>
                        
                        <div class="mb-3">
                            
                            <label for="icp_number" class="col-sm-2 col-form-label text-right">
                                {{ __('suda_lang::press.system_status') }}
                            </label>
                            
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="site_close" id="site_close_0" value="0" @if((isset($settings->site_close) && $settings->site_close==0) || !isset($settings->site_close)) checked @endif>
                                <label class="form-check-label" for="site_close">{{ __('suda_lang::press.open') }}</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="site_close" id="site_close_1" value="1" @if((isset($settings->site_close) && $settings->site_close==1) || !isset($settings->site_close)) checked @endif>
                                <label class="form-check-label" for="site_close">{{ __('suda_lang::press.close') }}</label>
                            </div>
                        </div>
                        
                        <div class="">
                            <button type="submit" class="btn btn-primary ">{{ __('suda_lang::press.submit_save') }}</button>
                        </div>

                        </form>
                    </div>
                </div>
            </div>
            
        </div>
        
    </div>
</div>
@endsection
