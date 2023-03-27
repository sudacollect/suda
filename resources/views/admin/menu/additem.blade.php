@extends('view_path::component.modal')



@section('content')
<form class="handle-ajaxform" role="form" method="POST" action="{{ admin_url('menu/item/save') }}">
    {{ csrf_field() }}
    <input type="hidden" name="menu_id" value="{{ $menu->id }}">
    <div class="modal-body">
          

        <div class="container-fluid">

            <div class="mb-3{{ $errors->has('title') ? ' has-error' : '' }}">
                  
                <label for="title">
                    {{ __('suda_lang::press.menu_name') }}
                </label>
        
                <input type="text" name="title" class="form-control" id="inputTitle" placeholder="{{ __('suda_lang::press.menu_name') }}">
                @if ($errors->has('title'))
                    <span class="help-block">
                        <strong>{{ $errors->first('title') }}</strong>
                    </span>
                @endif
        
            </div>
              
              
            <div class="mb-3{{ $errors->has('slug') ? ' has-error' : '' }}">
          
                <label for="slug">
                    {{ __('suda_lang::press.slug') }}
                </label>
        
                <input type="text" name="slug" class="form-control" id="inputSlug" placeholder="要求唯一,只能使用英文、数字">
                @if ($errors->has('slug'))
                    <span class="help-block">
                        <strong>{{ $errors->first('slug') }}</strong>
                    </span>
                @endif
        
            </div>
              
            <div class="mb-3{{ $errors->has('url_type') ? ' has-error' : '' }}">
          
                <label for="url_type">
                    链接类型
                </label>
        
                <select name="url_type" id="url_type" class="form-control" placeholder="请选择类型">
                    <option value="1">链接URL</option>
                    <option value="2">路由别名</option>
                </select>
        
            </div>
              
              
              
            <div class="mb-3{{ $errors->has('url') ? ' has-error' : '' }}">
          
                <label for="url">
                    URL
                </label>
        
                <input type="text" name="url" class="form-control" id="url" placeholder="链接地址">
                @if ($errors->has('url'))
                    <span class="help-block">
                        <strong>{{ $errors->first('url') }}</strong>
                    </span>
                @endif
        
            </div>
              
            <div class="mb-3{{ $errors->has('target') ? ' has-error' : '' }}">
          
                <label for="target">
                    是否新窗口打开
                </label>
        
                <select name="target" id="target" class="form-control" placeholder="请选择打开方式">
                    <option value="_self">当前窗口</option>
                    <option value="_blank">新窗口</option>
                </select>
        
            </div>
              
            <div class="mb-3{{ $errors->has('icon_class') ? ' has-error' : '' }}">
          
                <label for="icon_class">
                    图标样式
                </label>
        
                <input type="text" name="icon_class" class="form-control" id="icon_class" placeholder="图标样式,例如 ion-settings">
                @if ($errors->has('icon_class'))
                    <span class="help-block">
                        <strong>{{ $errors->first('icon_class') }}</strong>
                    </span>
                @endif
                
                <span class="help-block">
                    查看图标样式表 <a href="{{ route('sudaroute.admin.tool_compass') }}" target="_blank">图标</a>
                </span>
        
            </div>

            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="1" id="enable" name="enable" checked>
                <label class="form-check-label" for="enable">
                  是否启用
                </label>
            </div>

        </div>
    </div>

    <div class="modal-footer">
        {{-- <button type="button" class="btn btn-light" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">取消</span></button> --}}
        <button type="submit" class="btn btn-primary">{{ __('suda_lang::press.btn.submit') }}</button>
    </div>

</form>


<script>
    
    jQuery(function(){
        
        $('.handle-ajaxform').ajaxform();
        
    });
    
</script>

@endsection




