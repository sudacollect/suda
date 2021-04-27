<form id="widget_image">
    <div class="form-group">

        <label class="control-label">标题</label>
        <input type="text" name="image_title" class="form-control input-sm" @if(isset($content) && isset($content['image_title'])) value="{{ $content['image_title'] }}" @endif>

    </div>
    <div class="form-group">
        
        @if(isset($medias))
        
        @uploadBox('media',6,3,$medias)

        @else
        
        @uploadBox('media',6,3)

        @endif

    </div>

</form>