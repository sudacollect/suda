@extends('view_path::component.modal')



@section('content')
<form class="handle-ajaxform" role="form" method="POST" action="{{ $buttons['save'] }}">
    @csrf                
    <input type="hidden" name="id" value="{{ $term->id }}">

<div class="modal-body">
    <div class="container-fluid">

        <div class="col-12 suda_page_body">
            
                    
                    
                  <div class="mb-3 row {{ $errors->has('name') ? ' has-error' : '' }}">
                  
                    <label for="inputName" class="col-sm-3 col-form-label text-right">
                        {{ __('suda_lang::press.tags.name') }}
                    </label>
                    <div class="col-sm-6">
                        <input type="text" name="name" class="form-control" value="{{ $term->term->name }}" id="inputName" placeholder="{{ __('suda_lang::press.input_placeholder',['column'=>__('suda_lang::press.tags.name')]) }}">
                        @if ($errors->has('name'))
                            <span class="help-block">
                                <strong>{{ $errors->first('name') }}</strong>
                            </span>
                        @endif
                    </div>
                
                  </div>
              
                  <div class="mb-3 row {{ $errors->has('slug') ? ' has-error' : '' }}">
                  
                    <label for="inputName" class="col-sm-3 col-form-label text-right">
                        {{ __('suda_lang::press.slug') }}
                    </label>
                    <div class="col-sm-6">
                        @if($term->term->slug=='default')
                        <input type="hidden" name="slug" value="default">
                        @endif
                        <input type="text" name="slug" class="form-control" value="{{ $term->term->slug }}" @if($term->term->slug=='default') disabled @endif id="inputName" placeholder="{{ __('suda_lang::press.slug') }}">
                        <span class="help-block">
                            {{ __('suda_lang::press.for_example') }} https://abc.com/tag/<strong>news</strong>
                        </span>
                    </div>
                
                  </div>
              
                    <div class="mb-3 row {{ $errors->has('desc') ? ' has-error' : '' }}">
                  
                      <label for="desc" class="col-sm-3 col-form-label text-right">
                        {{ __('suda_lang::press.description') }}
                      </label>
                      <div class="col-sm-6">

                          <textarea name="desc" class="form-control" rows=4 placeholder="{{ __('suda_lang::press.description') }}">{{ $term->desc }}</textarea>
                      </div>
                
                    </div>
                    
                    
                    <div class="mb-3 row {{ $errors->has('sort') ? ' has-error' : '' }}">
                  
                      <label for="inputName" class="col-sm-3 col-form-label text-right">
                        {{ __('suda_lang::press.sort') }}
                      </label>
                      <div class="col-sm-6">
                          <input type="number" name="sort" class="form-control" value="{{ $term->sort }}" id="inputName" placeholder="{{ __('suda_lang::press.sort') }}">
                          <span class="help-block">
                            {{ __('suda_lang::press.sort_asc') }}
                          </span>
                      </div>
                
                    </div>
            
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

    });
    
</script>

@endsection

