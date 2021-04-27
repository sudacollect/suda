@extends('view_path::component.modal',['modal_size'=>'sm'])



@section('content')
<form class="handle-ajaxform" role="form" method="POST" action="{{ admin_url('menu/save') }}">
    @csrf
<div class="modal-body">
          

    <div class="container-fluid">


        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                
            <label for="name">
                {{ trans('suda_lang::press.name') }}
            </label>
        
            <input type="text" name="name" class="form-control" id="inputName" placeholder="只能使用英文、数字">
            @if ($errors->has('name'))
                <span class="help-block">
                    <strong>{{ $errors->first('name') }}</strong>
                </span>
            @endif
    
        </div>

    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-light" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">取消</span></button>
    <button type="submit" class="btn btn-primary">{{ __('suda_lang::press.btn.submit') }}</button>
</div>

</form>
<script>
    
    jQuery(document).ready(function(){
        $('.handle-ajaxform').ajaxform();
        
    });
    
</script>
@endsection