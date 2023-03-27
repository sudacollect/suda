@extends('view_path::component.modal',['modal_size'=>'sm'])



@section('content')
<form class="handle-ajaxform" role="form" method="POST" action="{{ admin_url('menu/save') }}">
    @csrf
    <input type="hidden" name="id" value="{{ $menu->id }}">

<div class="modal-body">
    <div class="container-fluid">

        <div class="mb-3{{ $errors->has('name') ? ' has-error' : '' }}">
                          
            <label for="name" >
                {{ __('suda_lang::press.name') }}
            </label>
            
            <input type="text" name="name" class="form-control" id="inputName" value="{{ $menu->name }}" placeholder="{{ __('suda_lang::press.input_placeholder',['column'=>__('suda_lang::press.name')]) }}">
            @if ($errors->has('name'))
                <span class="help-block">
                    <strong>{{ $errors->first('name') }}</strong>
                </span>
            @endif
            
        </div>

    </div>
</div>

<div class="modal-footer">
    <button type="submit" class="btn btn-primary">{{ __('suda_lang::press.btn.submit') }}</button>
</div>

</form>

<script>
    
    jQuery(function(){
        
        $('.handle-ajaxform').ajaxform();
        
    });
    
</script>

@endsection




