@extends('view_path::component.modal',['modal_size'=>'sm'])



@section('content')
<form class="handle-ajaxform" role="form" method="POST" action="{{ admin_url('menu/recovery/save') }}">
    @csrf
    <div class="modal-body">
            

        <div class="container-fluid">
            <h5 class="text-danger">
                {{ __('suda_lang::press.menu_recover.title') }}
            </h5>
            <p>
                {{ __('suda_lang::press.menu_recover.tips_1') }}
            </p>
            <p>
                {{ __('suda_lang::press.menu_recover.tips_2') }}
            </p>
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