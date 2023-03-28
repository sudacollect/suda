<form class="form" id="quick-search" method="POST" action="{{ admin_url('article/search') }}">
    {{ csrf_field() }}
    <input type="hidden" name="view_type" value="{{ $view_type }}">
    <!-- 分类搜索 -->
    @php
                        
    $selects = [];

    if(isset($filter['category'])){
        $selects = $filter['category'];
        
    }
    
    @endphp
    
    <div class="row">
        <div class="col">
            <select name="search[category]"  placeholder="请选择分类" class="select-category form-select">
                <option value="">-选择分类-</option>
                @if($categories)
                
                @include('view_suda::taxonomy.category.options',['cates'=>$categories,'child'=>0,'select'=>$selects])
                
                @endif
            </select>
        </div>
        <div class="col">
            <select name="search[date]" class="form-select ms-2 me-2" aria-placeholder="- 选择日期 -" placeholder="- 选择日期 -">
                <option value="">-选择日期-</option>
                @if($dates && $dates->count()>0)
                @foreach($dates as $date)
                <option value="{{ $date->year.'-'.$date->month }}" @if(isset($filter['date']) && $filter['date']== $date->year.'-'.$date->month) selected @endif>{{ $date->year.'-'.$date->month }}</option>
                @endforeach
                @endif
            </select>
        </div>
        <div class="col">
            <div class="input-group">
                <input type="text" name="search[title]" class="form-control me-2" placeholder="文章标题">
                <button type="submit" class="btn btn-dark">搜索</button>
            </div>
        </div>
    </div>
    

    
    

</form>