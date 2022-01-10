<form class="form col-sm-4" id="quick-search" method="POST" action="{{ admin_url('page/search') }}">
    @csrf
    <div class="row">
        <div class="col">
            <select name="search[date]" class="form-select">
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
            <input type="text" name="search[title]" class="form-control" placeholder="页面标题">
            <button type="submit"  class="btn btn-dark" >搜索</button>
            </div>
        </div>
        
    </div>

</form>