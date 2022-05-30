@extends('view_path::component.modal')



@section('content')
<form class="handle-ajaxform" role="form" method="POST" action="{{ admin_url($buttons['save']) }}">
    {{ csrf_field() }}

    <input type="hidden" name="id" value="{{ $term->id }}">
    <input type="hidden" name="taxonomy_name" value="{{ $term->taxonomy }}">

<div class="modal-body">
    <div class="container-fluid">

        <div class="col-12 suda_page_body">
            
                <div class="mb-3 row {{ $errors->has('images') ? ' has-error' : '' }}">
                        
                    <label for="images" class="col-sm-3 col-form-label text-right">
                        图标
                    </label>
                    <div class="col-sm-8">
                        @if($term->logo)
                        @uploadBox('media',1,1,$term->logo)
                        @else
                        @uploadBox('media',1,1)
                        @endif
                    </div>
                
                </div>
                    
                  <div class="mb-3 row{{ $errors->has('name') ? ' has-error' : '' }}">
                  
                    <label for="inputName" class="col-sm-3 col-form-label text-right">
                        名称
                    </label>
                    <div class="col-sm-8">
                        <input type="text" name="name" class="form-control" value="{{ $term->term->name }}" id="inputName" placeholder="名称">
                        @if ($errors->has('name'))
                            <span class="help-block">
                                <strong>{{ $errors->first('name') }}</strong>
                            </span>
                        @endif
                    </div>
                
                  </div>
              
                  <div class="mb-3 row{{ $errors->has('slug') ? ' has-error' : '' }}">
                  
                    <label for="inputName" class="col-sm-3 col-form-label text-right">
                        别名
                    </label>
                    <div class="col-sm-8">
                        @if($term->term->slug=='default')
                        <input type="hidden" name="slug" value="default">
                        @endif
                        <input type="text" name="slug" class="form-control" value="{{ $term->term->slug }}" @if($term->term->slug=='default') disabled @endif id="inputName" placeholder="请填写{{ $taxonomy_title }}别名">
                        <span class="help-block">
                            例如 <strong>news</strong>
                        </span>
                    </div>
                
                  </div>


                  <div class="mb-3 row {{ $errors->has('color') ? ' has-error' : '' }}">
                  
                    <label for="color" class="col-sm-3 col-form-label text-right">
                        颜色
                    </label>
                    <div class="col-sm-8">
                        <div class="input-group">
                            <input type="text" class="form-control" name="color" aria-label="color" value="{{ $term->color }}">
                            <div class="input-group-text">
                              <span class="color-picker"><i class="ion-color-palette"></i></span>
                            </div>
                          </div>
                    </div>
                
                  </div>
              
                    <div class="mb-3 row {{ $errors->has('desc') ? ' has-error' : '' }}">
                  
                      <label for="desc" class="col-sm-3 col-form-label text-right">
                          描述
                      </label>
                      <div class="col-sm-8">

                          <textarea name="desc" class="form-control" rows=4 placeholder="{{ $taxonomy_title }}描述">{{ $term->desc }}</textarea>
                      </div>
                
                    </div>
                    
                    @if($term->term->slug!='default')
                    <div class="mb-3 row {{ $errors->has('parent') ? ' has-error' : '' }}">
                  
                      <label for="inputName" class="col-sm-3 col-form-label text-right">
                          上级{{ $taxonomy_title }}
                      </label>
                      <div class="col-sm-8">
                          <select name="parent" class="select-category" placeholder="-上级{{ $taxonomy_title }}-">
                              <option value="0">无</option>
                              @if($categories)
                              
                              @include('view_suda::taxonomy.category.options',['cates'=>$categories,'child'=>0,'select'=>$term->parent,'exclude'=>$term->id])
                              
                              @endif
                          </select>
                          <span class="help-block">
                              默认不选设置为一级{{ $taxonomy_title }}
                          </span>
                      </div>
                
                    </div>
                    @endif
                    
                    <div class="mb-3 row{{ $errors->has('sort') ? ' has-error' : '' }}">
                  
                      <label for="inputName" class="col-sm-3 col-form-label text-right">
                          排序
                      </label>
                      <div class="col-sm-8">
                          <input type="number" name="sort" class="form-control" value="{{ $term->sort }}" id="inputName" placeholder="请填写排序">
                          <span class="help-block">
                              数字越小越靠前
                          </span>
                      </div>
                
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
    
    jQuery(document).ready(function(){
        
        $('.handle-ajaxform').ajaxform();

        $('select.select-category').selectCategory('single');

        $.mediabox({
            modal_url: "{{ admin_url('medias/modal') }}",
            upload_url: "{{ admin_url('medias/upload/image') }}"
        });

        var pickr = Pickr.create({
            el: '.color-picker',
            theme: 'nano', // or 'monolith', or 'nano'
            useAsButton: true,
            default: $('input[name="color"]').val(),
            swatches: [
                'rgba(244, 67, 54, 1)',
                'rgba(233, 30, 99, 1)',
                'rgba(156, 39, 176, 1)',
                'rgba(103, 58, 183, 1)',
                'rgba(63, 81, 181, 1)',
                'rgba(33, 150, 243, 1)',
                'rgba(3, 169, 244, 1)',
                'rgba(0, 188, 212, 1)',
                'rgba(0, 150, 136, 1)',
                'rgba(76, 175, 80, 1)',
                'rgba(139, 195, 74, 1)',
                'rgba(205, 220, 57, 1)',
                'rgba(255, 235, 59, 1)',
                'rgba(255, 193, 7, 1)'
            ],

            components: {

                // Main components
                preview: true,
                opacity: true,
                hue: true,

                // Input / output Options
                interaction: {
                    hex: false,
                    rgba: false,
                    hsla: false,
                    hsva: false,
                    cmyk: false,
                    input: true,
                    clear: false,
                    save: false
                }
            }
        });


        pickr.on('save', (color, instance) => {
            //
        }).on('clear', instance => {
            //
        }).on('change', (color, instance) => {
            pickr.applyColor();
            // pickr.hide();
            pickr.setColorRepresentation('HEX');
            
            $('input[name="color"]').val(pickr.getColor().toHEXA().toString());
            
        });
    });
    
</script>

@endsection

