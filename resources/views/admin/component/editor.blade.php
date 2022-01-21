
@php
    $height = !isset($height)?300:intval($height);
    $content = !isset($content)?'':$content;
    $input_name = !isset($input_name)?'content':$input_name;
@endphp

<textarea id="{{ isset($editor)?$editor:'summernote' }}" class="form-control" name="{{ $input_name }}" placeholder="请输入内容" style="width:100%;padding:5px;" rows=5>{{ $content }}</textarea>

@push('styles-head')
<link href="{{ suda_asset('editor/summernote-bs5.min.css') }}" rel="stylesheet">
@endpush


@push('scripts')

@once
<script src="{{ suda_asset('editor/summernote-bs5.min.js') }}"></script>
<script src="{{ suda_asset('editor/lang/summernote-zh-CN.js') }}"></script>
<script src="{{ suda_asset('js/plugins/summernote-ext-media.js') }}"></script>
<script src="{{ suda_asset('js/plugins/summernote-cleaner.js') }}"></script>
{{-- <script src="{{ suda_asset('js/plugins/summernote-image-shapes.js') }}"></script> --}}
{{-- <script src="{{ suda_asset('js/plugins/summernote-image-captionit.js') }}"></script> --}}
<script src="{{ suda_asset('/js/plugins/editor.js') }}"></script>
@endonce

<script type="text/javascript">
    $(document).ready(function(){
        suda.editor_height = {{ $height }};
        
        $.fn.zeditor("{{ isset($editor)?'#'.$editor:'#summernote' }}","{{ isset($load_url)?$load_url:admin_url('component/loadlayout/image/editor') }}","{{ isset($modal_url)?$modal_url:admin_url('medias/modal/') }}","{{ isset($upload_url)?$upload_url:admin_url('medias/upload/image/') }}");
    })
</script>


@endpush