<form class="form-inline" id="quick-search" method="POST" action="{{ admin_url('page/search') }}">
    {{ csrf_field() }}
    
        <select name="search[date]" class="form-control form-control-sm mr-sm-2">
                <option value="">-选择日期-</option>
                @if($dates && $dates->count()>0)
                @foreach($dates as $date)
                <option value="{{ $date->year.'-'.$date->month }}" @if(isset($filter['date']) && $filter['date']== $date->year.'-'.$date->month) selected @endif>{{ $date->year.'-'.$date->month }}</option>
                @endforeach
                @endif
            </select>
    
        <input type="text" name="search[title]" class="form-control form-control-sm mr-sm-2" placeholder="页面标题">
    
    
        <button type="submit"  class="btn btn-light btn-sm" >搜索</button>
    
</form>