<form id="widget_textarea">
    <div class="form-group">

        <label class="control-label">标题</label>
        <input type="text" name="textarea_title" class="form-control input-sm" @if(isset($content) && isset($content['textarea_title'])) value="{{ $content['textarea_title'] }}" @endif>

    </div>
    <div class="form-group">

        <label class="control-label">内容</label>
        <textarea name="textarea" class="form-control input-sm">@if(isset($content) && isset($content['textarea'])){{ $content['textarea'] }}@endif</textarea>

    </div>

</form>