@extends('view_path::component.modal',['modal_size'=>'sm'])



@section('content')
<form class="handle-ajaxform" role="form" method="POST" action="{{ admin_url('menu/recovery/save') }}">
    @csrf
    <div class="modal-body">
            

        <div class="container-fluid">
            <h5 class="text-danger">恢复初始化数据</h5>
            <p>
                恢复默认菜单为初始化数据，操作将仅仅替换当前菜单组下所有菜单数据，并不会改变系统的调用规则。
            </p>
            <p>
                恢复操作不会影响其他菜单组数据。
            </p>
            <hr>
            <h5 class="text-primary">说明</h5>
            <p>
                当需要恢复默认菜单组的菜单，或者 Suda 更新后，菜单数据没有更新。
            </p>
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