<form id="widget_image">
    <div class="form-group">

        <label class="control-label">标题</label>
        <input type="text" name="image_title" class="form-control input-sm" @if(isset($content) && isset($content['image_title'])) value="{{ $content['image_title'] }}" @endif>

    </div>
    <div class="form-group">

        <label class="control-label">链接</label>
        <input type="text" name="link" class="form-control input-sm" @if(isset($content) && isset($content['link'])) value="{{ $content['link'] }}" @endif>

    </div>
    <div class="form-group">
        
        @if(isset($media))
        
        @uploadBox('media',1,1,$media)

        @else
        
        @uploadBox('media',1,1)

        @endif

    </div>

</form>