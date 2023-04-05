@extends('view_path::layouts.default')



@section('content')

<div class="container-fluid">
    
    <div class="page-title"><i class="ion-create"></i>&nbsp;{{ __('suda_lang::press.menu_items.article_new') }}</div>
    
    <form role="form" method="POST" action="{{ admin_url('article/save') }}" class="form-ajax">

        @csrf
        

        <div class="row">
        
        <div class="col-sm-9 suda_page_body">
            
            <div class="card">
                
                
                <div class="card-body">
                    
                    
                    
                    
                    <div class="mb-3{{ $errors->has('title') ? ' has-error' : '' }}" >
                      <label for="title">{{ __('suda_lang::press.pages.title') }}</label>
                      <input type="text" name="title" class="form-control" id="inputName" placeholder="title">
                    </div>
                    
                    
                    <div class="mb-3{{ $errors->has('content') ? ' has-error' : '' }}" >
                        <label for="content">{{ __('suda_lang::press.pages.content') }}</label>
                        
                        <x-suda::editor id="summernote" name="content" :height="$editor_height" />

                    </div>
                    
                </div>
                
            </div>

        </div>
        
        <div class="col-sm-3">
            
            <div class="card mb-3">
                <div class="card-body">
                    <div class="mb-3{{ $errors->has('category') ? ' has-error' : '' }}" >
                        <label for="slug">
                            {{ __('suda_lang::press.category') }}
                        </label>
                        <x-suda::select-category type="multiple" taxonomy="post_category" :placeholder="__('suda_lang::press.category')" />
                    </div>
               
                    <div class="mb-3{{ $errors->has('keyword') ? ' has-error' : '' }}" >
                            <label for="slug">
                                {{ __('suda_lang::press.tags.tag') }}
                            </label>
                            <x-suda::select-tag name="keyword[]" taxonomy="post_tag" max=5 :link="admin_url('tags/search/json')" />
                    </div>

                    <div class="mb-3">
                        <label for="inputName">
                            {{ __('suda_lang::press.pages.kv_image') }}
                        </label>
                        @uploadBox(['article',1,1])
                    </div>
                    
                </div>
                
            </div>
        

            <div class="card mb-3">
                <div class="card-body">

                    <div class="mb-3{{ $errors->has('slug') ? ' has-error' : '' }}" >
                        <label for="slug" >
                            {{ __('suda_lang::press.pages.slug') }}
                        </label>
                        <input type="text" name="slug" class="form-control" id="slug" placeholder="{{ __('suda_lang::press.pages.slug') }}">
                        <span class="help-block">
                            {{ __('suda_lang::press.pages.slug_tips') }}
                        </span>
                    </div>
                    
                    <div class="mb-3{{ $errors->has('redirect_url') ? ' has-error' : '' }}" >
                        <label for="redirect_url" >
                            {{ __('suda_lang::press.pages.redirect_url') }}
                        </label>
        
                        <input type="text" name="redirect_url" class="form-control" id="redirect_url" placeholder="{{ __('suda_lang::press.pages.redirect_url') }}">
                        <span class="help-block">
                            {{ __('suda_lang::press.pages.redirect_tips') }}
                        </span>
        
                    </div>

                    <div class="mb-3">
                              
                        <label for="published_at" >
                            {{ __('suda_lang::press.publish_date') }}
                        </label>
                        <input type="text" name="published_at" data-toggle="datepicker" class="form-control" placeholder="{{ __('suda_lang::press.publish_date') }}">
                    </div>
                    
                </div>

            </div>



            
            
        
            <div class="card">

                <div class="card-body">
                        <div class="mb-3">
                              
                                <label for="stick_top" >
                                    {{ __('suda_lang::press.sticked') }}
                                </label>
                                <div class="form-check form-check-inline">
                                    <input type="radio" class="form-check-input" name="stick_top" placeholder="是" value="1" >
                                    <label class="form-check-label" for="stick_top">是</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input type="radio" class="form-check-input" name="stick_top" placeholder="否" value="0" checked>
                                    <label class="form-check-label" for="stick_top">否</label>
                                </div>

                            </div>
                    <div class="mb-3">
                            <label for="slug" >
                                {{ __('suda_lang::press.publish') }}
                            </label>    
                            <div class="form-check form-check-inline">
                                <input type="radio" class="form-check-input" name="disable" placeholder="是" value="0" >
                                <label class="form-check-label" for="disable">是</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input type="radio" class="form-check-input" name="disable" placeholder="否" value="1" checked>
                                <label class="form-check-label" for="disable">否</label>
                            </div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">{{ __('suda_lang::press.save') }}</button>

                </div>
            </div>
            
            
            
            
        </div>
        

        </div>
        
    </form>
        
    
</div>

@endsection



@push('scripts')
<script type="text/javascript">
    
    $(function(){

        
        $.mediabox({
            box_url: "{{ admin_url('medias/load-modal/') }}",
            modal_url: "{{ admin_url('medias/modal/') }}",
            upload_url: "{{ admin_url('medias/upload/image/') }}",
            remove_url: "{{ admin_url('medias/remove/image/') }}"
        });
        
        $('[data-toggle="datepicker"]').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            showClear:true,
            sideBySide:false,
            useCurrent:'minute',
            locale:'zh-CN',
        });
        
        
    })
    
</script>
@endpush