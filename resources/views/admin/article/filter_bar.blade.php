<form class="form" id="quick-search" method="POST" action="{{ admin_url('article/search') }}">
    @csrf
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
            <select name="search[category]" placeholder="{{ __('suda_lang::press.category') }}" class="select-category form-select form-select-sm">
                <option value="">- {{ __('suda_lang::press.category') }} -</option>
                @if($categories)
                
                @include('view_suda::taxonomy.category.options',['cates'=>$categories,'child'=>0,'select'=>$selects])
                
                @endif
            </select>
        </div>
        <div class="col">
            <select name="search[date]" class="form-select form-select-sm ms-2 me-2" aria-placeholder="- {{ __('suda_lang::press.date') }} -" placeholder="- {{ __('suda_lang::press.date') }} -">
                <option value="">- {{ __('suda_lang::press.date') }} -</option>
                @if($dates && $dates->count()>0)
                @foreach($dates as $date)
                <option value="{{ $date->year.'-'.$date->month }}" @if(isset($filter['date']) && $filter['date']== $date->year.'-'.$date->month) selected @endif>{{ $date->year.'-'.$date->month }}</option>
                @endforeach
                @endif
            </select>
        </div>
        <div class="col">
            <div class="input-group input-group-sm">
                <input type="text" name="search[title]" class="form-control" placeholder="{{ __('suda_lang::press.title') }}">
                <button type="submit" class="btn btn-primary">{{ __('suda_lang::press.search') }}</button>
            </div>
        </div>
    </div>
    

    
    

</form>