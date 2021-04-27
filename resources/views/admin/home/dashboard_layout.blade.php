@extends('view_path::component.modal')

@section('content')

<form class="handle-ajaxform" role="form" method="POST" action="{{ admin_url('style/dashboard.layout/save') }}">
    @csrf
    
    <div class="modal-body">
        
        <div class="container-fluid">
            
            <fieldset class="form-group">
                <div class="row">
                  <legend class="col-form-label col-sm-3 pt-0">导航布局</legend>
                  <div class="col-sm-9">
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="navbar_layout" id="navbar_layout" value="flat" @if(isset($setting['navbar_layout']) && $setting['navbar_layout']=='flat') checked @endif>
                      <label class="form-check-label" for="navbar_layout">
                        左右布局
                      </label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="navbar_layout" id="navbar_layout" value="fluid" @if(isset($setting['navbar_layout']) && $setting['navbar_layout']=='fluid') checked @endif>
                      <label class="form-check-label" for="navbar_layout">
                        通栏布局
                      </label>
                    </div>
                  </div>
                </div>
            </fieldset>
    

            <fieldset class="form-group">
                <div class="row">
                  <legend class="col-form-label col-sm-3 pt-0">导航配色</legend>
                  <div class="col-sm-9">
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="navbar_color" id="navbar_color" value="white" @if(isset($setting['navbar_color']) && $setting['navbar_color']=='white') checked @endif>
                      <label class="form-check-label" for="navbar_color">
                            <span class="badge badge-white bg-white text-dark border border-light">白色</span>
                      </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="navbar_color" id="navbar_color" value="dark" @if(isset($setting['navbar_color']) && $setting['navbar_color']=='dark') checked @endif>
                        <label class="form-check-label" for="navbar_color">
                            <span class="badge badge-dark">暗色</span>
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="navbar_color" id="navbar_color" value="blue" @if(isset($setting['navbar_color']) && $setting['navbar_color']=='blue') checked @endif>
                        <label class="form-check-label" for="navbar_color">
                            <span class="badge badge-light" style="background:#005caf;color:#fff;">蓝色</span>
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="navbar_color" id="navbar_color" value="coffe" @if(isset($setting['navbar_color']) && $setting['navbar_color']=='coffe') checked @endif>
                        <label class="form-check-label" for="navbar_color">
                            <span class="badge badge-light" style="background:#755e4a;color:#fff;">茶色</span>
                        </label>
                    </div>
                    
                  </div>
                </div>
            </fieldset>

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
