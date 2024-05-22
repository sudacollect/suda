@extends('view_path::layouts.default')



@section('content')

<div class="container-fluid">
    <div class="page-title"><i class="ion-create"></i>&nbsp;{{ __('suda_lang::press.menu_items.article_update') }}</div>
    <form role="form" method="POST" action="{{ admin_url('article/save') }}" class="form-ajax">
        @csrf
        <input type="hidden" name="id" value="{{ $item->id }}">

    <div class="row suda-row">

        <div class="col-sm-9 suda_page_body">
            
            <div class="card">
                
                
                <div class="card-body">
                    
                    
                    
                    <div class="mb-3{{ $errors->has('title') ? ' has-error' : '' }}" >
                        <label for="title">{{ __('suda_lang::press.pages.title') }}</label>
                      <input type="text" name="title" class="form-control" value="{{ $item->title }}" id="inputName" placeholder="title">
                    </div>
                    
                    
                    
                    <div class="mb-3{{ $errors->has('content') ? ' has-error' : '' }}" >
                        <label for="title">{{ __('suda_lang::press.pages.content') }}</label>
                        
                        <x-suda::editor id="summernote" name="content" :height="$editor_height" :content="$item->content" />
                        
                    </div>
                    
                </div>
                
            </div>
        </div>
        
        
        <div class="col-sm-3  ">
            
            <div class="card mb-3">
                <div class="card-body">
                    <div class="mb-3{{ $errors->has('category') ? ' has-error' : '' }}" >
                        <label for="slug" >
                            {{ __('suda_lang::press.category') }}
                        </label>
                        <x-suda::select-category type="multiple" taxonomy="post_category" :selected="$cates" :placeholder="__('suda_lang::press.category')" />
                        
                    </div>
                
                
                    <div class="mb-3{{ $errors->has('keyword') ? ' has-error' : '' }}" >
                            <label for="slug" >
                                {{ __('suda_lang::press.tags.tag') }}
                            </label>
                            <x-suda::select-tag name="keyword[]" taxonomy="post_tag" max="5" :tags="$tags" :link="admin_url('tags/search/json')" />
                    </div>
                    <div class="mb-3">
                        <label for="inputName" >
                            {{ __('suda_lang::press.pages.kv_image') }}
                        </label>
                        @if($item->heroimage && isset($item->heroimage->media))
                        @uploadBox(['article',1,1,$item->heroimage->media])
                        @else
                        @uploadBox(['article',1,1])
                        @endif
                    </div>
                    
                </div>
                
            </div>
        

            <div class="card mb-3">
                <div class="card-body">

                    <div class="mb-3{{ $errors->has('slug') ? ' has-error' : '' }}" >
                        <label for="slug" >
                            {{ __('suda_lang::press.articles.slug') }}
                        </label>
                        <input type="text" name="slug" class="form-control" id="slug" placeholder="{{ __('suda_lang::press.pages.slug') }}" value="{{ $item->slug }}">
                        <span class="help-block">
                            {{ __('suda_lang::press.articles.slug_tips') }}
                        </span>
                    </div>
                    
                    <div class="mb-3{{ $errors->has('redirect_url') ? ' has-error' : '' }}" >
                        <label for="redirect_url" >
                            {{ __('suda_lang::press.articles.redirect_url') }}
                        </label>
        
                        <input type="text" name="redirect_url" class="form-control" id="redirect_url" placeholder="{{ __('suda_lang::press.pages.redirect_url') }}" value="{{ $item->redirect_url }}">
                        <span class="help-block">
                            {{ __('suda_lang::press.articles.redirect_tips') }}
                        </span>
        
                    </div>

                    <div class="mb-3">
                            
                        <label for="published_at" >
                            {{ __('suda_lang::press.publish_date') }}
                        </label>
                        <input type="text" name="published_at" data-toggle="datepicker" value="{{ $item->published_at }}" class="form-control" placeholder="{{ __('suda_lang::press.publish_date') }}">
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
                                    <input type="radio" class="form-check-input" name="stick_top" @if($item->stick_top=='1') checked @endif placeholder="{{ __('suda_lang::press.yes') }}" value="1" >
                                    <label class="form-check-label" for="stick_top">{{ __('suda_lang::press.yes') }}</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input type="radio" class="form-check-input" name="stick_top" @if($item->stick_top=='0') checked @endif placeholder="{{ __('suda_lang::press.no') }}" value="0">
                                    <label class="form-check-label" for="stick_top">{{ __('suda_lang::press.no') }}</label>
                                </div>

                            </div>
                    <div class="mb-3">
                            <label for="slug" >
                                {{ __('suda_lang::press.publish') }}
                            </label>    
                            <div class="form-check form-check-inline">
                                <input type="radio" class="form-check-input" name="disable" @if($item->disable=='0') checked @endif placeholder="{{ __('suda_lang::press.yes') }}" value="0" >
                                <label class="form-check-label" for="disable">{{ __('suda_lang::press.yes') }}</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input type="radio" class="form-check-input" name="disable" @if($item->disable=='1') checked @endif placeholder="{{ __('suda_lang::press.no') }}" value="1">
                                <label class="form-check-label" for="disable">{{ __('suda_lang::press.no') }}</label>
                            </div>
                    </div>
                
                    <button type="submit" class="btn btn-primary ">{{ __('suda_lang::press.save') }}</button>
                </div>
            </div>
  
        </div>

    </div>
    </form>
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


        $('[data-toggle="datepicker"]').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            showClear:true,
            sideBySide:false,
            useCurrent:'minute',
            locale:'zh-CN',
        });
        
    });
    
</script>
@endpush