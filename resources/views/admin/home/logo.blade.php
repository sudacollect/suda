@extends('view_path::layouts.default')



@section('content')
<div class="container">
    <div class="row suda-row">
        <div class="page-heading">
            <h1 class="page-title">
                <i class="ion-settings"></i>
                {{ __('suda_lang::press.logo') }}
            </h1>
            
        </div>
        <div class="col-12 suda_page_body">
            @include('view_suda::admin.home.settings_tabs',['active'=>'logo'])
            
            <div class="card card-with-tab">
                <div class="card-body">
                    
                    <form class="form-ajax"  method="POST" action="{{ admin_url('setting/logo') }}" role="form">
                      
                      @csrf
                      
                      <div class="row mb-3">
                        
                        <label for="site_name" class="col-sm-2 col-form-label text-end">
                            Logo
                        </label>
                        
                        <div class="col-sm-4">
                            @if(isset($logos->logo))
                            @uploadBox('media@logo',1,1,$logos->logo)
                            @else
                            @uploadBox('media@logo',1,1)
                            @endif

                            <span class="help-block">Logo</span>
                        </div>
                      </div>
                      
                      <div class="row mb-3">
                        
                        <label for="site_domain" class="col-sm-2 col-form-label text-end">Favicon</label>
                        
                        <div class="col-sm-4">
                            @if(isset($logos->favicon))
                            @uploadBox('media@favicon',1,1,$logos->favicon)
                            @else
                            @uploadBox('media@favicon',1,1)
                            @endif

                            <span class="help-block">64x64 pixel, png</span>
                        </div>
                      </div>
                      
                      <div class="row mb-3">
                        
                        <label for="company_name" class="col-sm-2 col-form-label text-end">{{ __('suda_lang::press.settings.share_image') }}</label>
                        
                        <div class="col-sm-4">
                            @if(isset($logos->share_image))
                            @uploadBox('media@share_image',1,1,$logos->share_image)
                            @else
                            @uploadBox('media@share_image',1,1)
                            @endif
                            <span class="help-block"> 400x400 pixel</span>
                        </div>
                      </div>
                      
                      
                      <button type="submit" class="btn btn-primary offset-sm-2">{{ __('suda_lang::press.submit_save') }}</button>

                    </form>
                </div>
            </div>
            
        </div>
        
    </div>
</div>
@endsection

@push('scripts')
<script type="text/javascript">
    $(document).ready(function(){
        $.mediabox({
            box_url: "{{ admin_url('medias/load-modal/') }}",
            modal_url: "{{ admin_url('medias/modal/') }}",
            upload_url: "{{ admin_url('medias/upload/image/') }}",
            remove_url: "{{ admin_url('medias/remove/image/') }}"
        });
    })
</script>
@endpush