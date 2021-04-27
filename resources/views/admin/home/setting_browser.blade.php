@extends('view_path::layouts.default')



@section('content')
<div class="container">
    <div class="row suda-row">
        <div class="page-heading">
            <h1 class="page-title">
                <i class="zly-gear-s"></i>
                浏览设置
            </h1>
            
        </div>
        
        <div class="col-12 suda_page_body">
            @include('view_suda::admin.home.settings_tabs',['active'=>'browser'])
            
            <div class="card card-with-tab">
                <div class="card-body">
                    
                    <form class="form-ajax"  method="POST" action="{{ admin_url('setting/browser') }}" role="form">
                      {{ csrf_field() }}
                      
                      <div class="form-group row">
                       
                        
                        <label for="default_page" class="col-sm-2 col-form-label text-right">
                               前台默认访问
                        </label>
                        
                        <div class="col-sm-4">
                            <div class="form-check">
                                <input type="radio" name="default_page" @if(isset($settings->values['page_type']) && $settings->values['page_type']=='default_page') checked @endif value="default_page">
                                <label class="form-check-label" for="default_page">
                                    默认首页
                                </label>
                            </div>
                            
                            <div class="form-check">
                                <input type="radio" name="default_page" @if(isset($settings->values['page_type']) && $settings->values['page_type']=='single_page') checked @endif  value="single_page">
                                <label class="form-check-label" for="default_page">
                                    静态页面
                                </label>

                                &nbsp;&nbsp;
                                <button href="{{ admin_url('page/modalbox/list/default') }}" class="pop-modal modalbox-select-page btn btn-light btn-sm"><i class="zly-paper"></i>&nbsp;选择</button>
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

                                <input type="radio" name="default_page" @if(isset($settings->values['page_type']) && $settings->values['page_type']=='link_page') checked @endif value="link_page">
                                <label class="form-check-label" for="default_page">
                                    自定义URL
                                </label>

                                <div class="input-group default_page_url" style="display:none;">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            {{ env('APP_URL') }}/
                                        </div>
                                    </div>
                                    <input type="text" name="default_page_url" placeholder="请输入访问地址" class="form-control" @if(isset($settings->values['page_type']) && $settings->values['page_type']=='link_page') value="{{ $settings->values['page_value'] }}" @endif>
                                </div>

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
