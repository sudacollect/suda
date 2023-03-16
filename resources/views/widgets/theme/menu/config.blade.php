<form id="widget_menu">
    <div class="mb-3">

        <label class="control-label">标题</label>
        <input type="text" name="title" class="form-control input-sm" @if(isset($content) && isset($content['title'])) value="{{ $content['title'] }}" @endif>

    </div>
    <div class="mb-3">

        {{-- 选择菜单组 --}}
        <label class="control-label">选择菜单</label>
        @if($menus->count()>0)
        <select name="menu" class="form-control input-sm">
            @foreach($menus as $menu)
            <option value="{{ $menu->id }}" @if(isset($content) && isset($content['menu']) && $content['menu']==$menu->id) selected @endif>{{ $menu->name }}</option>
            @endforeach
        </select>
        @endif

    </div>

</form>