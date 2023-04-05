<form class="form col-sm-4" id="quick-search" method="POST" action="{{ admin_url('page/search') }}">
    @csrf
    <div class="row">
        <div class="col">
            <select name="search[date]" class="form-select form-select-sm">
                <option value="">-{{ __('suda_lang::press.date') }}-</option>
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
            <button type="submit"  class="btn btn-primary" >{{ __('suda_lang::press.search') }}</button>
            </div>
        </div>
        
    </div>

</form>