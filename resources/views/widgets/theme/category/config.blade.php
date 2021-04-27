<form id="widget_category" class="form">
    <div class="form-group">

        <label class="control-label">标题</label>
        <input type="text" name="title" class="form-control input-sm" @if(isset($content) && isset($content['title'])) value="{{ $content['title'] }}" @endif>

    </div>

    <div class="form-group form-inline">

        <input type="checkbox" name="parent" value="1" @if(isset($content) && isset($content['parent']) && $content['parent']==1) checked @endif> 只显示一级分类

    </div>

</form>