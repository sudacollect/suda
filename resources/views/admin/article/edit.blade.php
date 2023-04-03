@extends('view_path::layouts.default')



@section('content')

<div class="container-fluid">
    <div class="page-title"><i class="ion-create"></i>&nbsp;编辑文章</div>
    <form role="form" method="POST" action="{{ admin_url('article/save') }}" class="form-ajax">
        @csrf
        <input type="hidden" name="id" value="{{ $item->id }}">

    <div class="row suda-row">

        <div class="col-sm-9 suda_page_body">
            
            <div class="card">
                
                
                <div class="card-body">
                    
                    
                    
                    <div class="mb-3{{ $errors->has('title') ? ' has-error' : '' }}" >
                        <label for="title">标题</label>
                      <input type="text" name="title" class="form-control" value="{{ $item->title }}" id="inputName" placeholder="请输入标题">
                    </div>
                    
                    
                    
                    <div class="mb-3{{ $errors->has('content') ? ' has-error' : '' }}" >
                        <label for="title">内容</label>
                        
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
                                分类
                        </label>
                        <x-suda::select-category type="multiple" taxonomy="post_category" :selected="$cates" />
                        
                    </div>
                
                
                    <div class="mb-3{{ $errors->has('keyword') ? ' has-error' : '' }}" >
                            <label for="slug" >
                                标签
                            </label>
                            <x-suda::select-tag name="keyword[]" taxonomy="post_tag" max="5" :tags="$tags" :link="admin_url('tags/search/json')" />
                    </div>
                    <div class="mb-3">
                        <label for="inputName" >
                        标题图
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
                                自定义路径
                        </label>
                        <input type="text" name="slug" class="form-control" id="slug" placeholder="自定义路径" value="{{ $item->slug }}">
                        <span class="help-block">
                        设置后访问链接将变成 article/定义的路径.
                        </span>
                    </div>
                    
                    <div class="mb-3{{ $errors->has('redirect_url') ? ' has-error' : '' }}" >
                        <label for="redirect_url" >
                            跳转URL
                        </label>
        
                        <input type="text" name="redirect_url" class="form-control" id="redirect_url" placeholder="跳转URL" value="{{ $item->redirect_url }}">
                        <span class="help-block">
                        设置跳转后，将直接访问到设定的URL页面.
                        </span>
        
                    </div>

                    <div class="mb-3">
                            
                        <label for="published_at" >
                            发布日期
                        </label>
                        <input type="text" name="published_at" data-toggle="datepicker" value="{{ $item->published_at }}" class="form-control" placeholder="可选发布日期">
                    </div>
                    
                </div>

            </div>

            <div class="card">

                <div class="card-body">
                        <div class="mb-3">
                                
                                <label for="stick_top" >
                                    置顶
                                </label>
                                
                                <div class="form-check form-check-inline">
                                    <input type="radio" class="form-check-input" name="stick_top" @if($item->stick_top=='1') checked @endif placeholder="是" value="1" >
                                    <label class="form-check-label" for="stick_top">是</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input type="radio" class="form-check-input" name="stick_top" @if($item->stick_top=='0') checked @endif placeholder="否" value="0">
                                    <label class="form-check-label" for="stick_top">否</label>
                                </div>

                            </div>
                    <div class="mb-3">
                            <label for="slug" >
                                    发布
                            </label>    
                            <div class="form-check form-check-inline">
                                <input type="radio" class="form-check-input" name="disable" @if($item->disable=='0') checked @endif placeholder="是" value="0" >
                                <label class="form-check-label" for="disable">是</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input type="radio" class="form-check-input" name="disable" @if($item->disable=='1') checked @endif placeholder="否" value="1">
                                <label class="form-check-label" for="disable">否</label>
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