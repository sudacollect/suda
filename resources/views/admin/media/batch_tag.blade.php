@extends('view_path::component.modal')

@section('content')
<style>
.edit-media{
    max-height:300px;
    margin: 0 auto;
    display: block;
}
</style>
<form class="handle-ajaxform" role="form" method="POST" action="{{ admin_url('media/batchtag/save') }}">
    @csrf
    
    <div class="modal-body">
    <div class="container-fluid">
        <div class="col-12 suda_page_body">
            
              <div class="mb-3">
                  <label for="inputName" class="control-label">
                      对已选择图片设置标签
                  </label>
                  
              </div>
              
              <div class="mb-3{{ $errors->has('keyword') ? ' has-error' : '' }}" >
                <label for="slug" >
                    标签
                </label>
                <x-suda::select-tag name="keyword[]" taxonomy="media_tag" max=5 :link="admin_url('tags/search/json')" />
            </div>

        </div>
    </div>
</div>

    <div class="modal-footer">
    <button type="button" class="btn btn-light" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">取消</span></button>
    <button type="submit" class="btn btn-primary">{{ __('suda_lang::press.submit_save') }}</button>
</div>

</form>

@stack('scripts')

<script>
    $(function(){
        $('.handle-ajaxform').ajaxform();
    });
</script>

@endsection

