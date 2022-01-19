<form id="widget_article">
    <div class="mb-3">

        <label class="control-label">标题</label>
        <input type="text" name="title" class="form-control input-sm" @if(isset($content) && isset($content['title'])) value="{{ $content['title'] }}" @endif>

    </div>
    <div class="mb-3 form-inline">

        {{-- 选择分类 --}}
        <label class="control-label">选择分类</label>
        @if($catgories->count()>0)
        <select name="category" class="form-control input-sm">
            <option value="0">所有分类</option>
            @foreach($catgories as $category)
            <option value="{{ $category->id }}" @if(isset($content) && isset($content['category']) && $content['category']==$category->id) selected @endif>{{ $category->term->name }}</option>
            @endforeach
        </select>
        @endif

    </div>

    <div class="mb-3 form-inline">

        <label class="control-label">最大数量</label>
        <input type="number" name="num"  @if(isset($content) && isset($content['num'])) value="{{ $content['num'] }}" @else value="5" @endif class="form-control input-sm" style="width:80px;">

    </div>

    <div class="mb-3 form-inline">

        <label class="control-label">显示图片</label>
        <input type="checkbox" name="heroimage"  @if(isset($content) && isset($content['heroimage']) && $content['heroimage']==1) checked @endif value="1">

    </div>

    <div class="mb-3 form-inline">

        <label class="control-label">显示时间</label>
        <input type="checkbox" name="datetime"  @if(isset($content) && isset($content['datetime']) && $content['datetime']==1) checked @endif value="1">

    </div>

</form>