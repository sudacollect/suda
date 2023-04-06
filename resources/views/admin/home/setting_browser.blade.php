@extends('view_path::layouts.default')



@section('content')
<div class="container">
    <div class="row suda-row">
        <div class="page-heading">
            <h1 class="page-title">
                <i class="ion-settings"></i>
                {{ __('suda_lang::press.front_info') }}
            </h1>
            
        </div>
        
        <div class="col-12 suda_page_body">
            @include('view_suda::admin.home.settings_tabs',['active'=>'browser'])
            
            <div class="card card-with-tab">
                <div class="card-body">
                    
                    <form class="form-ajax"  method="POST" action="{{ admin_url('setting/browser') }}" role="form">
                      @csrf
                      
                      <div class="row mb-3">
                       
                        
                        <label for="default_page" class="col-sm-2 col-form-label text-end">
                            {{ __('suda_lang::press.settings.front_default_page') }}
                        </label>
                        
                        <div class="col-sm-4">
                            <div class="form-check">
                                <input type="radio" name="default_page" @if(isset($settings['page_type']) && $settings['page_type']=='default_page') checked @endif value="default_page">
                                <label class="form-check-label" for="default_page">
                                    {{ __('suda_lang::press.settings.default_homepage') }}
                                </label>
                            </div>
                            
                            <div class="form-check">
                                <input type="radio" name="default_page" @if(isset($settings['page_type']) && $settings['page_type']=='single_page') checked @endif  value="single_page">
                                <label class="form-check-label" for="default_page">
                                    {{ __('suda_lang::press.settings.static_homepage') }}
                                </label>

                                &nbsp;&nbsp;
                                <button href="{{ admin_url('page/modalbox/list/default') }}" class="pop-modal modalbox-select-page btn btn-light btn-sm">{{ __('suda_lang::press.btn.select') }}</button>
                                <div class="modal-result-default" style="padding-left:30px;color:#08f;">
                                    @if(isset($page))
                                    <p>
                                        <input type="hidden" name="default_page_id" value="{{ $page->id }}">
                                        {{ $page->title }}
                                    </p>
                                    @endif
                                </div>

                            </div>


                            <div class="form-check">

                                <input type="radio" name="default_page" @if(isset($settings['page_type']) && $settings['page_type']=='link_page') checked @endif value="link_page">
                                <label class="form-check-label" for="default_page">
                                    {{ __('suda_lang::press.settings.custom_homepage') }}
                                </label>

                                <div class="input-group default_page_url" style="display:none;">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            {{ env('APP_URL') }}/
                                        </div>
                                    </div>
                                    <input type="text" name="default_page_url" placeholder="请输入访问地址" class="form-control" @if(isset($settings['page_type']) && $settings['page_type']=='link_page') value="{{ $settings['page_value'] }}" @endif>
                                </div>

                            </div>

                            
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

<script>
    $(document).ready(function(){
        
        
        if($('input[name^="default_page"]:checked').val()=='single_page'){
            
            $('.modalbox-select-page').removeAttr('disabled');
            $('.modal-result-default').show();
            
        }else{
            $('.modalbox-select-page').attr('disabled','disabled');
        }
        
        if($('input[name^="default_page"]:checked').val()=='link_page'){
            
            $('.default_page_url').show();
            
        }
        
        $('input[name^="default_page"]').on('change',function(){
            
            if($(this).prop('checked')){
                
                if($(this).attr('value') != 'single_page'){
                    $('.modalbox-select-page').attr('disabled','disabled');
                }else{
                    $('.modalbox-select-page').removeAttr('disabled');
                }
                
                if($(this).attr('value') == 'link_page'){
                    $('.default_page_url').show();
                }else{
                    $('.default_page_url').hide().val('');
                }
                
            }
            
        })
        
    })
</script>


@endpush
