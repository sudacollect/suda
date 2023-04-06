@extends('view_path::component.modal')



@section('content')
<form class="handle-ajaxform" role="form" method="POST" action="{{ admin_url('menu/item/save') }}">
    @csrf
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
        
                <input type="text" name="slug" class="form-control" id="inputSlug" placeholder="{{ __('suda_lang::press.validation.slug') }}">
                @if ($errors->has('slug'))
                    <span class="help-block">
                        <strong>{{ $errors->first('slug') }}</strong>
                    </span>
                @endif
        
            </div>
              
            <div class="mb-3{{ $errors->has('url_type') ? ' has-error' : '' }}">
          
                <label for="url_type">
                    {{ __('suda_lang::press.link_type') }}
                </label>
        
                <select name="url_type" id="url_type" class="form-control" placeholder="{{ __('suda_lang::press.link_type') }}">
                    <option value="1">{{ __('suda_lang::press.link_url') }}</option>
                    <option value="2">{{ __('suda_lang::press.link_route') }}</option>
                </select>
        
            </div>
              
              
              
            <div class="mb-3{{ $errors->has('url') ? ' has-error' : '' }}">
          
                <label for="url">
                    URL
                </label>
        
                <input type="text" name="url" class="form-control" id="url" placeholder="{{ __('suda_lang::press.link_url') }}">
                @if ($errors->has('url'))
                    <span class="help-block">
                        <strong>{{ $errors->first('url') }}</strong>
                    </span>
                @endif
        
            </div>
              
            <div class="mb-3{{ $errors->has('target') ? ' has-error' : '' }}">
          
                <label for="target">
                    {{ __('suda_lang::press.open_window') }}
                </label>
        
                <select name="target" id="target" class="form-control" placeholder="{{ __('suda_lang::press.open_window') }}">
                    <option value="_self">{{ __('suda_lang::press._self') }}</option>
                    <option value="_blank">{{ __('suda_lang::press._blank') }}</option>
                </select>
        
            </div>
              
            <div class="mb-3{{ $errors->has('icon_class') ? ' has-error' : '' }}">
          
                <label for="icon_class">
                    {{ __('suda_lang::press.icon') }}
                </label>
        
                <input type="text" name="icon_class" class="form-control" id="icon_class" placeholder="ion-settings">
                @if ($errors->has('icon_class'))
                    <span class="help-block">
                        <strong>{{ $errors->first('icon_class') }}</strong>
                    </span>
                @endif
                
                <span class="help-block">
                    <a href="{{ route('sudaroute.admin.tool_compass') }}" target="_blank">{{ __('suda_lang::press.icon') }}</a>
                </span>
        
            </div>

            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="1" id="enable" name="enable" checked>
                <label class="form-check-label" for="enable">
                    {{ __('suda_lang::press.enable') }}
                </label>
            </div>

        </div>
    </div>

    <div class="modal-footer">
        {{-- <button type="button" class="btn btn-light" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">{{ __('suda_lang::press.btn.cancel') }}</span></button> --}}
        <button type="submit" class="btn btn-primary">{{ __('suda_lang::press.btn.submit') }}</button>
    </div>

</form>


<script>
    
    jQuery(function(){
        
        $('.handle-ajaxform').ajaxform();
        
    });
    
</script>

@endsection




