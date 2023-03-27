@extends('view_path::component.modal')



@section('content')
<style>
.edit-media{
    max-height:300px;
    margin: 0 auto;
    display: block;
}
</style>
<form class="handle-ajaxform" role="form" method="POST" action="{{ admin_url('medias/batchtag/save') }}">
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
                <select class="select-keyword form-control" name="keyword[]" multiple="multiple" placeholder="输入标签">
                    @if($tags->count()>0)
                
                    @foreach($tags as $tag)
                    <option value="{{ $tag->name }}" selected>{{ $tag->name }}</option>
                    @endforeach

                    @endif
                </select>
            </div>

        </div>
    </div>
</div>

    <div class="modal-footer">
    <button type="button" class="btn btn-light" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">取消</span></button>
    <button type="submit" class="btn btn-primary">{{ __('suda_lang::press.submit_save') }}</button>
</div>

</form>

<script>
    
    jQuery(function(){
        
        $('.handle-ajaxform').ajaxform();

        $('select.select-keyword').selectTag({taxonomy:'media_tag'});
    });
    
</script>

@endsection

