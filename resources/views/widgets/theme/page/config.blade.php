<form id="widget_page">
    <div class="mb-3">

        <label class="control-label">标题</label>
        <input type="text" name="title" class="form-control input-sm" @if(isset($content) && isset($content['title'])) value="{{ $content['title'] }}" @endif>

    </div>

    <div class="mb-3">

        <label class="control-label">最大数量</label>
        <input type="number" name="num"  @if(isset($content) && isset($content['num'])) value="{{ $content['num'] }}" @else value="5" @endif class="form-control input-sm" style="width:80px;">

    </div>

    <div class="mb-3">

        <label class="control-label">显示图片</label>
        <input type="checkbox" name="heroimage"  @if(isset($content) && isset($content['heroimage']) && $content['heroimage']==1) checked @endif value="1">

    </div>

</form>