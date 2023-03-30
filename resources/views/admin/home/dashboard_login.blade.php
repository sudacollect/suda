@extends('view_path::layouts.default')



@section('content')
<div class="container">
    <div class="row suda-row">
        <div class="page-heading">
            <h1 class="page-title">
                <i class="ion-settings"></i>
                {{ __('suda_lang::press.dashboard_info') }}
            </h1>
            
        </div>
        
        <div class="col-12 suda_page_body">
            @include('view_suda::admin.home.settings_tabs',['active'=>'login'])
            
            <div class="card card-with-tab">
                <div class="card-body">
                    
                    <form class="form-ajax"  method="POST" action="{{ admin_url('setting/dashboardinfo') }}" role="form">
                      @csrf
                      
                      
                      <div class="row mb-3">
                       
                        
                        <label for="site_name" class="col-sm-2 col-form-label text-end">
                               Logo
                        </label>
                        
                        <div class="col-sm-4">

                            @if(isset($settings['dashboard_logo']->media))
                            @uploadBox('media@dashboard_logo',1,1,$settings['dashboard_logo']->media)
                            @else
                            @uploadBox('media@dashboard_logo',1,1)
                            @endif

                            <span class="help-block">360*120 pixel</span>
                        </div>
                      </div>
                      
                    <div class="row mb-3">
                       
                        
                        <label for="login_page" class="col-sm-2 col-form-label text-end">
                               {{ __('suda_lang::press.settings.login_path') }}
                        </label>
                        
                        <div class="col-sm-4">
                            <div class="input-group">
                                <div class="input-group-text">
                                    {{ config('sudaconf.admin_path') }}/
                                </div>
                                <input type="text" class="form-control" name="login_page" placeholder="example: index" value="@if(isset($settings['login_page'])){{ $settings['login_page'] }}@endif">
                            </div><!-- /input-group -->
                        </div>
                    </div>

                    <div class="row mb-3">
                       
                        
                        <label for="login_page" class="col-sm-2 col-form-label text-end">
                            {{ __('suda_lang::press.settings.breadcrumb') }}
                        </label>
                        
                        <div class="col-sm-4">
                            <label for="login_page" class="col-form-label text-end">
                                &nbsp;
                            </label>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="show_breadcrumb" value="1" @if(isset($settings['show_breadcrumb']) && $settings['show_breadcrumb']==1) checked @endif>
                                <label class="form-check-label" for="show_breadcrumb">{{ __('suda_lang::press.open') }}</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="show_breadcrumb" value="0" @if(!isset($settings['show_breadcrumb']) || (isset($settings['show_breadcrumb']) && $settings['show_breadcrumb']==0)) checked @endif>
                                <label class="form-check-label" for="show_breadcrumb">{{ __('suda_lang::press.close') }}</label>
                            </div>
                        </div>
                    </div>
                      
                    
                      
                    <div class="row mb-3">
                    
                    
                        <label for="app_quickin" class="col-sm-2 col-form-label text-end">
                            {{ __('suda_lang::press.settings.modules') }}
                        </label>
                        
                        <div class="col-sm-4 check-buttons btn-group" data-toggle="buttons">
                            
                            <label class="btn btn-light @if(isset($settings['dashboard_apps']['welcome']) && $settings['dashboard_apps']['welcome']=='on') active @endif">
                                <input type="checkbox" name="dashboard_apps[welcome]"  @if(isset($settings['dashboard_apps']['welcome']) && $settings['dashboard_apps']['welcome']=='on') checked @endif >&nbsp;{{ __('suda_lang::press.settings.modules_list.welcome') }}
                            </label>
                            <label class="btn btn-light @if(isset($settings['dashboard_apps']['quickin']) && $settings['dashboard_apps']['quickin']=='on') active @endif">
                                <input type="checkbox" name="dashboard_apps[quickin]"  @if(isset($settings['dashboard_apps']['quickin']) && $settings['dashboard_apps']['quickin']=='on') checked @endif >&nbsp;{{ __('suda_lang::press.settings.modules_list.quickin') }}
                            </label>
                            <label class="btn btn-light @if(isset($settings['dashboard_apps']['custom']) && $settings['dashboard_apps']['custom']=='on') active @endif">
                                <input type="checkbox" name="dashboard_apps[custom]"  @if(isset($settings['dashboard_apps']['custom']) && $settings['dashboard_apps']['custom']=='on') checked @endif >&nbsp;{{ __('suda_lang::press.settings.modules_list.custom') }}
                            </label>
                        </div>
                    </div>




                    <div class="row mb-3">
                       
                        
                       <label for="loginbox" class="col-sm-2 col-form-label text-end">
                            {{ __('suda_lang::press.settings.login_style') }}
                          </label>
                       
                       <div class="col-sm-4">
                           <select class="form-control" name="loginbox">
                               <option value="light" @if(isset($settings['loginbox']) && $settings['loginbox']=='light') selected @endif>{{ __('suda_lang::press.settings.style_list.default') }}</option>
                               <option value="dark" @if(isset($settings['loginbox']) && $settings['loginbox']=='dark') selected @endif>{{ __('suda_lang::press.settings.style_list.dark') }}</option>
                           </select>
                           {{-- <span class="help-block">选择图片风格时,可选择下面图片</span> --}}
                       </div>
                       
                    </div>

                    <div class="row mb-3">
                       
                        
                        <label for="loginbox" class="col-sm-2 col-form-label text-end">&nbsp;</label>
                    
                        <div class="col-sm-4">
                            <input type="text" class="form-control color-pickr" name="login_color" placeholder="背景配色" @if(isset($settings['login_color'])) value="{{ $settings['login_color'] }}" @else value="#1c35a7" @endif>
                        </div>
                    
                    </div>

                    <div class="row mb-3">
                       
                        
                        <label for="site_name" class="col-sm-2 col-form-label text-end">
                            {{ __('suda_lang::press.settings.login_image') }}
                        </label>
                        
                        <div class="col-sm-4">
                            <input type="hidden" name="dashboard_login_logo_select" value="{{ $settings['dashboard_login_logo_select'] }}">

                            <div class="list-group list-group-horizontal list-images list-images-icon">
                                <div class="list-group-item">
                                    <div class="login-logo" data-name="boat">
                                    <img src="{{ suda_asset('images/login/icon_boat.jpg') }}" style="max-width:100%;width:100%;">
                                    </div>
                                </div>
                                <div class="list-group-item">
                                    <div class="login-logo" data-name="brand">
                                    <img src="{{ suda_asset('images/login/icon_brand.jpg') }}" style="max-width:100%;width:100%;">
                                    </div>
                                </div>
                                <div class="list-group-item">
                                    <div class="login-logo" data-name="building">
                                    <img src="{{ suda_asset('images/login/icon_building.jpg') }}" style="max-width:100%;width:100%;">
                                    </div>
                                </div>
                                <div class="list-group-item">
                                    <div class="login-logo" data-name="sport">
                                    <img src="{{ suda_asset('images/login/icon_sport.jpg') }}" style="max-width:100%;width:100%;">
                                    </div>
                                </div>
                                <div class="list-group-item">
                                    <div class="login-logo" data-name="cake">
                                    <img src="{{ suda_asset('images/login/icon_cake.jpg') }}" style="max-width:100%;width:100%;">
                                    </div>
                                </div>
                                
                            </div>

                        </div>
                    </div>

                    <div class="row mb-3">
                       
                        
                        <label for="site_name" class="col-sm-2 col-form-label text-end">
                            &nbsp;
                        </label>
                        
                        <div class="col-sm-4">
                            
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="custom_dashboard_login_logo" @if($settings['dashboard_login_logo_select']=='customize') checked @endif role="switch" id="customLoginImage">
                                <label class="form-check-label" for="customLoginImage">{{ __('suda_lang::press.settings.custom_login_image') }}</label>
                            </div>
                            
                            @uploadBox('media@dashboard_login_logo',1,1,$settings['dashboard_login_logo'])

                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-4 offset-sm-2">
                            <button type="submit" class="btn btn-primary">{{ __('suda_lang::press.save') }}</button>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
            
        </div>
        
    </div>
</div>
@endsection

@push('styles')
<style>


.list-images-icon .list-group-item{
    width:20%;
    float:left;
    border:none;
    padding:5px;
    background:transparent;
    position:relative;
}
.list-images-icon-fixed{
    width:20%;
    float:left;
}
.list-images-icon-fixed .list-group-item{
    width:100%;
}
.list-images-icon .list-group-item:hover{
    background:transparent;
}
.list-images-icon .list-group-item div.login-logo{
    position:relative;
    display:block;
}
.list-images-icon .list-group-item img{
    width:100%;
    max-width:100%;
}
.list-images-icon .uploadbox{
    height: auto;
    line-height: inherit;
    background:#eee;
    padding:0;
}
.list-images-icon .login-logo span.checked{
    position: absolute;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background: rgba(80, 80, 80, 0.7);
    display: flex;
    justify-content: center;
    align-items: Center;
}
.list-images-icon .login-logo span.checked i.icon{
    font-size:18px;
    color:#fff;
}

.list-images-icon .list-group-item .delete_image_show{
    height:100%;
    display: flex;
    justify-content: center;
    align-items: Center;
}
</style>
@endpush

@push('scripts')
<script type="text/javascript">
    $(document).ready(function(){
        
        $.mediabox({
            box_url: "{{ admin_url('medias/load-modal/') }}",
            modal_url: "{{ admin_url('medias/modal/') }}",
            upload_url: "{{ admin_url('medias/upload/image/') }}",
            remove_url: "{{ admin_url('medias/remove/image/') }}"
        });

        var pickr = Pickr.create({
            el: '.color-pickr',
            theme: 'nano', // or 'monolith', or 'nano'
            useAsButton: true,
            default: $('input[name="login_color"]').val(),
            swatches: [
                'rgba(244, 67, 54, 1)',
                'rgba(233, 30, 99, 1)',
                'rgba(156, 39, 176, 1)',
                'rgba(103, 58, 183, 1)',
                'rgba(63, 81, 181, 1)',
                'rgba(33, 150, 243, 1)',
                'rgba(3, 169, 244, 1)',
                'rgba(0, 188, 212, 1)',
                'rgba(0, 150, 136, 1)',
                'rgba(76, 175, 80, 1)',
                'rgba(139, 195, 74, 1)',
                'rgba(205, 220, 57, 1)',
                'rgba(255, 235, 59, 1)',
                'rgba(255, 193, 7, 1)'
            ],

            components: {

                // Main components
                preview: true,
                opacity: false,
                hue: true,

                // Input / output Options
                interaction: {
                    hex: false,
                    rgba: false,
                    hsla: false,
                    hsva: false,
                    cmyk: false,
                    input: false,
                    clear: false,
                    save: false
                }
            }
        });

        pickr.on('save', (color, instance) => {
            //
        }).on('clear', instance => {
            //
        }).on('change', (color, instance) => {
            pickr.applyColor();
            // pickr.hide();
            pickr.setColorRepresentation('HEX');
            var right_color = $('input[name="login_color"]').val();
            $('input[name="login_color"]').val(pickr.getColor().toHEXA().toString());
        });

        //选择登录图片
        $('.list-images-icon').on('click','.login-logo',function(e){
            e.preventDefault();

            var el = this;
            var logo = $(el).attr('data-name');
            var logo_select = $('input[name="dashboard_login_logo_select"]');

            if($(el).find('span.checked').length<1){
                $('.list-images-icon').find('span.checked').remove();
                $(el).append('<span class="checked"><i class="icon ion-checkmark-circle"></i></span>');
            }

            $(logo_select).val(logo);

        });

        //初始化选择
        $('.list-images-icon').find('.login-logo[data-name="{{ $settings['dashboard_login_logo_select'] }}"]').trigger('click');
    });
</script>
@endpush