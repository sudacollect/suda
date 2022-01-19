<form id="widget_image">
    <div class="mb-3">

        <label class="control-label">标题</label>
        <input type="text" name="image_title" class="form-control input-sm" @if(isset($content) && isset($content['image_title'])) value="{{ $content['image_title'] }}" @endif>

    </div>
    <div class="mb-3">
        
        @if(isset($medias))
        
        @uploadBox('media',6,3,$medias)

        @else
        
        @uploadBox('media',6,3)

        @endif

    </div>

</form>