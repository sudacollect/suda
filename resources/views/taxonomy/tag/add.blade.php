@extends('view_path::component.modal')



@section('content')
<form class="handle-ajaxform" role="form" method="POST" action="{{ admin_url($buttons['save']) }}">
    {{ csrf_field() }}
<div class="modal-body">
    <div class="container-fluid">

        <div class="col-12 suda_page_body">
            
                  <div class="form-group row {{ $errors->has('name') ? ' has-error' : '' }}">
                  
                    <label for="inputName" class="col-sm-3 col-form-label text-right">
                        {{ __('suda_lang::press.tag_name') }}
                    </label>
                    <div class="col-sm-6">
                        <input type="text" name="name" class="form-control" id="inputName" placeholder="{{ __('suda_lang::press.input_placeholder',['column'=>__('suda_lang::press.tag_name')]) }}">
                        @if ($errors->has('name'))
                            <span class="help-block">
                                <strong>{{ $errors->first('name') }}</strong>
                            </span>
                        @endif
                    </div>
                
                  </div>
              
                  <div class="form-group row {{ $errors->has('slug') ? ' has-error' : '' }}">
                  
                    <label for="inputName" class="col-sm-3 col-form-label text-right">
                        别名
                    </label>
                    <div class="col-sm-6">
                        <input type="text" name="slug" class="form-control" id="inputName" placeholder="请填写别名">
                        <span class="help-block">
                            例如 https://abc.com/tag/<strong>news</strong>
                        </span>
                    </div>
                
                  </div>
                  
                    <div class="form-group row {{ $errors->has('desc') ? ' has-error' : '' }}">
                  
                      <label for="desc" class="col-sm-3 col-form-label text-right">
                          描述
                      </label>
                      <div class="col-sm-6">
                          <textarea name="desc" class="form-control" rows=4 placeholder="描述"></textarea>
                      </div>
                
                    </div>
                    
                    <div class="form-group row {{ $errors->has('sort') ? ' has-error' : '' }}">
                  
                      <label for="inputName" class="col-sm-3 col-form-label text-right">
                          排序
                      </label>
                      <div class="col-sm-6">
                          <input type="number" name="sort" class="form-control" id="inputName" placeholder="请填写排序">
                          <span class="help-block">
                              数字越小越靠前
                          </span>
                      </div>
                
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
    });
    
</script>

@endsection

