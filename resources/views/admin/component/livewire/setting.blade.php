<div class="container">
    
    <div class="row suda-row">
        @if($error_msg)
        <div class="modal-bg position-fixed w-100 h-100 left-0 top-0" style="z-index:9999;background:rgba(0,0,0,0.6)">
            <div class="modal show d-block"  id="exampleModal" tabindex="999" aria-labelledby="error-modal" aria-modal="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="error-modal">提示</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" wire:click="closeBox"></button>
                    </div>
                    <div class="modal-body" style="min-height:100px;">
                        {{ $error_msg }}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" wire:click="closeBox">关闭</button>
                    </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

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
                        <form wire:submit.prevent="submit" class="form-ajax"  method="POST" role="form">
                        @csrf
                        
                        <div class="mb-3 form-floating">
                        
                            <input type="text" class="form-control" wire:model="settings.site_name" placeholder="{{ __('suda_lang::press.input_placeholder',['column'=>__('suda_lang::press.system_name')]) }}" >
                            <label for="site_name">
                                {{ __('suda_lang::press.website_name') }}<i class="optional">*</i>
                            </label>
                            @if($errors->has('settings.site_name'))
                                <span class="help-block text-danger">{{ $errors->first('settings.site_name') }}</span>
                            @endif
                        </div>
                        
                        <div class="mb-3 form-floating">
                            <input type="text" class="form-control" wire:model="settings.site_domain" placeholder="{{ __('suda_lang::press.input_placeholder',['column'=>__('suda_lang::press.domain')]) }}">
                            <label for="site_domain">
                                {{ __('suda_lang::press.domain') }}
                            </label>
                            
                        </div>
                        
                        <div class="mb-3 form-floating">
                            <input type="text" class="form-control" wire:model="settings.company_name" placeholder="{{ __('suda_lang::press.input_placeholder',['column'=>__('suda_lang::press.company_name')]) }}">
                            <label for="company_name">
                                {{ __('suda_lang::press.company_name') }}<i class="optional">*</i>
                            </label>
                        </div>
                        
                        <div class="mb-3 form-floating">
                            <input type="text" class="form-control" wire:model="settings.company_addr" placeholder="{{ __('suda_lang::press.input_placeholder',['column'=>__('suda_lang::press.company_address')]) }}">
                            <label for="company_addr" >
                                {{ __('suda_lang::press.company_address') }}<i class="optional">*</i>
                            </label>
                        </div>
                        
                        <div class="mb-3 form-floating">
                            <input type="text" class="form-control" wire:model="settings.company_phone" placeholder="{{ __('suda_lang::press.input_placeholder',['column'=>__('suda_lang::press.company_telephone')]) }}" >
                            <label for="company_phone">
                                {{ __('suda_lang::press.company_telephone') }}<i class="optional">*</i>
                            </label>
                        </div>
                        
                        <div class="mb-3 form-floating">
                            <input type="text" class="form-control" wire:model="settings.icp_number" placeholder="{{ __('suda_lang::press.input_placeholder',['column'=>__('suda_lang::press.icp_number')]) }}">
                            <label for="icp_number">
                                {{ __('suda_lang::press.icp_number') }}
                            </label>
                        </div>
                        
                        <div class="mb-3">
                            
                            <label for="icp_number" class="col-sm-2 col-form-label text-right">
                                {{ __('suda_lang::press.site_status') }}
                            </label>
                            
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" wire:model="settings.site_status" id="site_status_1" value="1" >
                                <label class="form-check-label" for="site_status">{{ __('suda_lang::press.open') }}</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" wire:model="settings.site_status" id="site_status_0" value="0" >
                                <label class="form-check-label" for="site_status">{{ __('suda_lang::press.close') }}</label>
                            </div>
                        </div>
                        
                        <div class="">
                            <button type="submit" class="btn btn-primary ">{{ __('suda_lang::press.submit_save') }}</button>
                        </div>

                        </form>
                    </div>
                </div>
            </div>
            
            <div wire:loading>
                Processing
            </div>
        </div>
        
    </div>

    <script>
        window.addEventListener('errorBox', event => {
            window.setTimeout(function () {
                // alert('提示: ' + event.detail.msg);
                setTimeout(function(){
                    window.location.reload();
                }, 500);
            },0);
        })
    </script>

</div>

