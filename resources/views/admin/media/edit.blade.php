@extends('view_path::component.modal')



@section('content')
<style>
.edit-media{
    max-height:300px;
    margin: 0 auto;
    display: block;
}
</style>
<form role="form" method="POST" action="{{ admin_url('media/update') }}" class="handle-ajaxform">
    @csrf
    <input type="hidden" name="id" value="{{ $media->id }}">
    <div class="modal-body">
    <div class="container-fluid">
        <div class="col-12 suda_page_body">
            
              <div class="form-group">
                <label for="inputName" class="control-label">
                    图片
                </label>

                <div class="form-row">
                    <div class="col col-sm-6">
                        {!! suda_image($media,['size'=>'large','imageClass'=>'edit-media edit-media-'.$media->id],false) !!}
                    </div>
                    <div class="col col-sm-4">
                        {!! suda_image($media,['size'=>'medium','imageClass'=>'edit-media edit-media-'.$media->id],false) !!}
                    </div>
                    <div class="col col-sm-2">
                        {!! suda_image($media,['size'=>'small','imageClass'=>'edit-media edit-media-'.$media->id],false) !!}
                    </div>
                </div>
                  
              </div>
            
              <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                  <label for="inputName" class="control-label">
                      {{ __('suda_lang::press.name') }}
                  </label>

                  <input type="text" name="name" class="form-control" value="{{ $media->name }}" id="inputName" placeholder="图片名称">
              </div>
              
              <div class="form-group{{ $errors->has('keyword') ? ' has-error' : '' }}" >
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
    <button type="button" class="btn btn-light" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">取消</span></button>
    <button type="submit" class="btn btn-primary">{{ __('suda_lang::press.submit_save') }}</button>
</div>

</form>

<script>
    
    jQuery(document).ready(function(){
        $('.handle-ajaxform').ajaxform();

        $('select.select-keyword').selectTag({taxonomy:'media_tag'});
    });
    
</script>

@endsection

