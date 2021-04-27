@extends('view_path::layouts.default')



@section('content')

<div class="container">

    <form role="form" method="POST" action="{{ admin_url('page/save') }}" class="form-ajax">
        <div class="page-title"><i class="ion-create"></i>&nbsp;新建页面</div>
    <div class="row suda-row">

        <input type="hidden" name="previous_url" value="{{ URL::previous() }}">

        <div class="col-sm-9 suda_page_body">
            <div class="card">
                
                <div class="card-body">
                    
                    
                    {{ csrf_field() }}
                    
                    
                    <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}" >
                        <label for="inputName" >
                            标题<i class="optional">*</i>
                        </label>
                        <input type="text" name="title" class="form-control" id="inputName" placeholder="请输入名称">
                    </div>

                    
                    
                    <div class="form-group{{ $errors->has('content') ? ' has-error' : '' }}" >
                        
                        <label for="inputName" >
                            内容<i class="optional">*</i>
                        </label>

                        @include('view_app::component.editor',['height'=>$editor_height])

                    </div>
                    
                    
                </div>
                
                <div class="card-footer">
                    <span class="help-block">
                        <a target="_blank" href="https://suda.gtd.xyz" style="color:#999;"><i class="icon ion-help-buoy"></i>&nbsp;了解什么是自定义路径？</a>
                    </span>
                </div>
            </div>
            
        </div>
        <div class="col-sm-3">
            <div class="card">
    
                <div class="card-body">
                    
                    <div class="form-group{{ $errors->has('keyword') ? ' has-error' : '' }}" >
                        <label for="keyword" >
                            标签
                        </label>
                        <select name="keyword[]" class="select-keyword form-control" multiple="multiple" placeholder="输入标签"></select>
                    </div>
                    
                    
                    
                    <div class="form-group">
                        <label for="inputName" >
                            标题图<i class="optional">*</i>
                        </label>
                        @uploadBox('page@input_page','1','1')
                    </div>
    
                    <div class="form-group{{ $errors->has('slug') ? ' has-error' : '' }}" >
                        <label for="slug" >
                            自定义路径
                        </label>
                        <input type="text" name="slug" class="form-control" id="slug" placeholder="自定义路径">
                        <span class="help-block">
                        设置后页面访问链接将变成 page/定义的路径.
                        </span>
                    </div>
                    
                    <div class="form-group{{ $errors->has('redirect_url') ? ' has-error' : '' }}" >
                        <label for="redirect_url" >
                            跳转URL
                        </label>
    
                        <input type="text" name="redirect_url" class="form-control" id="redirect_url" placeholder="跳转URL">
                        <span class="help-block">
                        设置跳转后，将直接访问到设定的URL页面.
                        </span>
    
                    </div>
    
                    <div class="form-group">
                        
                        <label for="published_at" >
                            发布日期
                        </label>
                        <input type="text" name="published_at" data-toggle="datepicker" class="form-control" placeholder="可选发布日期">
                    </div>
    
                    <div class="form-group">
                        
                        <label for="stick_top" >
                            置顶
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
    
                    <div class="form-group">
                        
                        <label for="enable" >
                            发布
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
                    
                    
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-block">{{ __('suda_lang::press.save') }}</button>
                    </div>
                    
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
            modal_url: "{{ admin_url('medias/modal') }}",
            upload_url: "{{ admin_url('medias/upload/image') }}"
        });
        

        $('[data-toggle="datepicker"]').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            showClear:true,
            sideBySide:false,
            useCurrent:'minute',
            locale:'zh-CN',
        });

        $('select.select-keyword').selectTag({taxonomy:'post_tag',placeholder:'选择标签'});
        
    });
    
</script>
@endpush