@extends('view_path::component.modal')

@section('content')

<form class="handle-ajaxform" role="form" method="POST" action="{{ admin_url('user/save') }}">
    {{ csrf_field() }}
    
    <div class="modal-body">
        
        <div class="container-fluid">
            
      
      
      
            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
        
              <label for="inputName" >
                  {{ trans('suda_lang::press.username') }}
              </label>
      
              <input type="text" name="name" class="form-control" id="inputName" placeholder="{{ __('suda_lang::auth.username_rule') }}">
                @if ($errors->has('name'))
                    <span class="help-block">
                        <strong>{{ $errors->first('name') }}</strong>
                    </span>
                @endif
            </div>
            
    
            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
        
              <label for="email">
                  {{ trans('suda_lang::press.email') }}
              </label>
      
              <input type="text" name="email" class="form-control" id="inputEmail" placeholder="{{ trans('suda_lang::press.email') }}">
            @if ($errors->has('email'))
                <span class="help-block">
                    <strong>{{ $errors->first('email') }}</strong>
                </span>
            @endif
      
            </div>
    
            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
        
              <label for="password">
                  {{ trans('suda_lang::auth.password') }}
              </label>
      
              <input type="password" name="password" class="form-control" id="inputPassword" placeholder="{{ trans('suda_lang::auth.password') }}">
                  @if ($errors->has('password'))
                      <span class="help-block">
                          <strong>{{ $errors->first('password') }}</strong>
                      </span>
                  @endif
      
            </div>
    

            
        </div>
        
    </div>
    
    <div class="modal-footer">
        <button type="submit" class="btn btn-primary">{{ __('suda_lang::press.submit_save') }}</button>
    </div>

</form>

<script>
    
    jQuery(document).ready(function(){
        $('.handle-ajaxform').ajaxform();
        
    });
    
</script>
    
@endsection
