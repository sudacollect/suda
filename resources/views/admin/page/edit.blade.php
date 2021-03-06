@extends('view_path::layouts.default')



@section('content')

<div class="container">

    <form role="form" method="POST" action="{{ admin_url('page/save') }}" class="form-ajax">
        <div class="page-title"><i class="ion-create"></i>&nbsp;{{ __('suda_lang::press.menu_items.page_update') }}</div>
    <div class="row suda-row">
        {{ csrf_field() }}
        <input type="hidden" name="previous_url" value="{{ URL::previous() }}">
        <input type="hidden" name="id" value="{{$page->id}}">
        
        <div class="col-sm-9">
            <div class="card">
                <div class="card-body">

                    <div class="mb-3 {{ $errors->has('title') ? ' has-error' : '' }}" >
                      <label for="inputName" >
                        {{ __('suda_lang::press.pages.title') }}<i class="optional">*</i>
                      </label>
                      <input type="text" name="title" class="form-control" id="inputName" placeholder="title" value="{{ $page->title }}">
                    </div>
                    
                    <div class="mb-3 {{ $errors->has('content') ? ' has-error' : '' }}" >
                      <label for="inputName" >
                        {{ __('suda_lang::press.pages.content') }}<i class="optional">*</i>
                      </label>
                      @include('view_app::component.editor',['height'=>$editor_height,'content'=>$page->content])
                    </div>
                    
                </div>

            </div>
            
            
        </div>


        <div class="col-sm-3">
            <div class="card">

                <div class="card-body">
                    
                    <div class="mb-3 {{ $errors->has('keyword') ? ' has-error' : '' }}" >
                        <label for="keyword" >
                            {{ __('suda_lang::press.tags.tag') }}
                        </label>
                        <select name="keyword[]" multiple="multiple" class="select-keyword form-control" placeholder="{{ __('suda_lang::press.tags.select_tag') }}">
                            @if($tags)
                            
                            @foreach($tags as $tag)
                            <option value="{{ $tag->name }}" selected>{{ $tag->name }}</option>
                            @endforeach
                            
                            @endif
                        </select>
                    </div>
                    
                    
                    
                    <div class="mb-3 ">
                        <label for="inputName" >
                            {{ __('suda_lang::press.pages.kv_image') }}<i class="optional">*</i>
                        </label>
                        @if(isset($page->heroimage->media))
                        @uploadBox('page@input_page','1','1',$page->heroimage->media)
                        @else
                        @uploadBox('page@input_page','1','1')
                        @endif
                    </div>

                    <div class="mb-3 {{ $errors->has('slug') ? ' has-error' : '' }}" >
                        <label for="slug" >
                            {{ __('suda_lang::press.pages.slug') }}
                        </label>
                        <input type="text" name="slug" class="form-control" id="slug" placeholder="slug" value="{{ $page->slug }}">
                        <span class="help-block">
                            {{ __('suda_lang::press.pages.slug_tips') }}
                        </span>
                    </div>
                    
                    <div class="mb-3 {{ $errors->has('redirect_url') ? ' has-error' : '' }}" >
                        <label for="redirect_url" >
                            {{ __('suda_lang::press.pages.redirect_url') }}
                        </label>
    
                        <input type="text" name="redirect_url" class="form-control" id="redirect_url" placeholder="{{ __('suda_lang::press.pages.redirect_url') }}" value="{{ $page->redirect_url }}">
                        <span class="help-block">
                            {{ __('suda_lang::press.pages.redirect_tip') }}
                        </span>

                    </div>

                    <div class="mb-3 ">
                              
                        <label for="published_at" >
                            {{ __('suda_lang::press.publish_date') }}
                        </label>
                        <input type="text" name="published_at" data-toggle="datepicker" value="{{ $page->published_at }}" class="form-control" placeholder="{{ __('suda_lang::press.publish_date') }}">
                    </div>

                    <div class="mb-3 ">
                        
                        <label for="stick_top" >
                            {{ __('suda_lang::press.stick_top') }}
                        </label>

                        <div class="form-check form-check-inline">
                            <input type="radio" class="form-check-input" name="stick_top" placeholder="???" value="1" @if($page->stick_top=='1') checked @endif>
                            <label class="form-check-label" for="stick_top">???</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input type="radio" class="form-check-input" name="stick_top" placeholder="???" value="0" @if($page->stick_top=='0') checked @endif>
                            <label class="form-check-label" for="stick_top">???</label>
                        </div>

                    </div>

                    <div class="mb-3 ">
                        
                        <label for="enable" >
                            {{ __('suda_lang::press.publish') }}
                        </label>

                        <div class="form-check form-check-inline">
                            <input type="radio" class="form-check-input" name="disable" placeholder="???" value="0" @if($page->disable=='0') checked @endif>
                            <label class="form-check-label" for="disable">???</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input type="radio" class="form-check-input" name="disable" placeholder="???" value="1" @if($page->disable=='1') checked @endif>
                            <label class="form-check-label" for="disable">???</label>
                        </div>

                    </div>
                    
                    <button type="submit" class="btn btn-primary ">{{ __('suda_lang::press.save') }}</button>
                    
                </div>

            </div>
        </div>
        

        
        
        
    </div>
    </form>
</div>

@endsection



@push('scripts')

<script type="text/javascript">
    
    $(document).ready(function(){

        $.mediabox({
            modal_url: "{{ admin_url('medias/modal') }}",
            upload_url: "{{ admin_url('medias/upload/image') }}"
        });
        

        $('[data-toggle="datepicker"]').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            showClear:true,
            sideBySide:false,
            useCurrent:'minute',
            locale:'zh-CN',
        });

        $('select.select-keyword').selectTag({taxonomy:'post_tag',placeholder:'????????????'});
    });
    
</script>
@endpush