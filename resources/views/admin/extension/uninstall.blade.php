@extends('view_path::component.modal',['modal_size'=>'md'])



@section('content')
<form class="handle-ajaxform" role="form" method="POST" action="{{ admin_url('manage/extension/'.$item['slug'].'/uninstall') }}">
    @csrf

    <div class="modal-body">
    <div class="container-fluid">
            <input type="hidden" name="extension_slug" value="{{ $item['slug'] }}">
            <div class="mb-3">
                
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="1" name="drop_table" >
                    <label class="form-check-label" for="drop_table">{{ __('suda_lang::press.extensions.force_delete_table') }}</label>
                </div>
                <span class="help-block text-danger">
                    {{ __('suda_lang::press.extensions.force_delete_table_tips') }}
                </span>
                
            </div>

    </div>
</div>

    <div class="modal-footer">
    <button type="button" class="btn btn-light" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">{{ __('suda_lang::press.btn.cancel') }}</span></button>
    <button type="submit" class="btn btn-primary">{{ __('suda_lang::press.submit_save') }}</button>
</div>

</form>

<script>
    
    jQuery(function(){
        
        $('.handle-ajaxform').ajaxform();

        $.mediabox({
            box_url: "{{ admin_url('medias/load-modal/') }}",
            modal_url: "{{ admin_url('medias/modal/') }}",
            upload_url: "{{ admin_url('medias/upload/image/') }}",
            remove_url: "{{ admin_url('medias/remove/image/') }}"
        });
    });
    
</script>

@endsection

