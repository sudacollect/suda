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
        <div class="col-12 suda_page_body">
            @include('view_suda::admin.home.settings_tabs',['active'=>'logo'])
            
            <div class="card card-with-tab">
                <div class="card-body">
                    
                    <form class="form-ajax"  method="POST" action="{{ admin_url('setting/logo') }}" role="form">
                      
                      {{ csrf_field() }}
                      
                      <div class="form-group row">
                        
                        <label for="site_name" class="col-sm-2 col-form-label text-right">
                            Logo
                        </label>
                        
                        <div class="col-sm-4">
                            
                            <div class="list-group list-images list-images-1">
                              <div class="list-group-item">
                                  <div class="upload-item uploadbox add-image-one @if(isset($logos->logo)) uploadbox-filled @endif" id="addimage_one" _data_type="setting" _data_name="logo">
                                      @if(isset($logos->logo))
                                      {!! suda_image($logos->logo->media,['size'=>'medium','imageClass'=>'image_show','url'=>false],false) !!}
                                      <input type="hidden" name="images[logo]" value="{{ $logos->logo->media_id }}">
                                      @endif
                                  </div>
                              </div>
                            </div>
                            <span class="help-block">请根据模板风格上传Logo</span>
                        </div>
                      </div>
                      
                      <div class="form-group row">
                        
                        <label for="site_domain" class="col-sm-2 col-form-label text-right">Favicon</label>
                        
                        <div class="col-sm-4">
                            <div class="list-group list-images list-images-1">
                              <div class="list-group-item">
                                  <div class="upload-item uploadbox add-image-one @if(isset($logos->favicon)) uploadbox-filled @endif" id="addimage_two" _data_type="setting" _data_name="favicon">
                                      @if(isset($logos->favicon))
                                      {!! suda_image($logos->favicon->media,['size'=>'large','imageClass'=>'image_show','url'=>false],false) !!}
                                      <input type="hidden" name="images[favicon]" value="{{ $logos->favicon->media_id }}">
                                      @endif
                                  </div>
                              </div>
                            </div>
                            <span class="help-block">尺寸建议64x64, 格式建议png</span>
                        </div>
                      </div>
                      
                      <div class="form-group row">
                        
                        <label for="company_name" class="col-sm-2 col-form-label text-right">分享图片</label>
                        
                        <div class="col-sm-4">
                            <div class="list-group list-images list-images-1">
                              <div class="list-group-item">
                                  <div class="upload-item uploadbox add-image-one @if(isset($logos->share_image)) uploadbox-filled @endif" id="addimage_three" _data_type="setting" _data_name="share_image">
                                      @if(isset($logos->share_image))
                                      {!! suda_image($logos->share_image->media,['size'=>'medium','imageClass'=>'image_show','url'=>false],false) !!}
                                      <input type="hidden" name="images[share_image]" value="{{ $logos->share_image->media_id }}">
                                      @endif
                                  </div>
                              </div>
                            </div>
                            <span class="help-block">建议不要小于400x400</span>
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
<script type="text/javascript">
    $(document).ready(function(){
        $.mediabox({
            modal_url: "{{ admin_url('medias/modal') }}",
            upload_url: "{{ admin_url('medias/upload/image') }}"
        });
    })
</script>
@endpush