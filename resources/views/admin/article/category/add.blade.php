@extends('view_path::component.modal')



@section('content')
<form class="form-horizontal handle-ajaxform" role="form" method="POST" action="{{ admin_url('article/category/save') }}">
<div class="modal-body">
    <div class="container">

        <div class="col-sm-12 suda_page_body">
            
                
                    {{ csrf_field() }}
              
                  <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                  
                    <label for="inputName" class="col-sm-3 control-label">
                        {{ __('suda_lang::press.category_name') }}
                    </label>
                    <div class="col-sm-6">
                        <input type="text" name="name" class="form-control" id="inputName" placeholder="{{ __('suda_lang::press.input_placeholder',['column'=>__('suda_lang::press.category_name')]) }}">
                        @if ($errors->has('name'))
                            <span class="help-block">
                                <strong>{{ $errors->first('name') }}</strong>
                            </span>
                        @endif
                    </div>
                
                  </div>
              
                  <div class="form-group{{ $errors->has('slug') ? ' has-error' : '' }}">
                  
                    <label for="inputName" class="col-sm-3 control-label">
                        别名
                    </label>
                    <div class="col-sm-6">
                        <input type="text" name="slug" class="form-control" id="inputName" placeholder="请填写分类别名">
                        <span class="help-block">
                            例如 <strong>news</strong>
                        </span>
                    </div>
                
                  </div>
                  
                    <div class="form-group{{ $errors->has('desc') ? ' has-error' : '' }}">
                  
                      <label for="desc" class="col-sm-3 control-label">
                          描述
                      </label>
                      <div class="col-sm-6">
                          <textarea name="desc" class="form-control" rows=4 placeholder="分类描述"></textarea>
                      </div>
                
                    </div>
                    
                    <div class="form-group{{ $errors->has('parent') ? ' has-error' : '' }}">
                  
                      <label for="inputName" class="col-sm-3 control-label">
                          上级分类
                      </label>
                      <div class="col-sm-6">
                          <select name="parent" selectize="true" placeholder="-上级分类-">
                              <option value="0">无</option>
                              @if($categories)
                              
                              @include('view_suda::taxonomy.category_options',['cates'=>$categories,'child'=>0])
                              
                              @endif
                          </select>
                          <span class="help-block">
                              默认不选设置为一级分类
                          </span>
                      </div>
                
                    </div>
                    
                    <div class="form-group{{ $errors->has('sort') ? ' has-error' : '' }}">
                  
                      <label for="inputName" class="col-sm-3 control-label">
                          排序
                      </label>
                      <div class="col-sm-6">
                          <input type="text" name="sort" class="form-control" id="inputName" placeholder="请填写排序">
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
        $('select[selectize="true"]').selectCategory();
    });
    
</script>

@endsection

