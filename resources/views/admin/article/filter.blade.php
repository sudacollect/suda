
<div class="more-filter-section" id="filter-options">
    {{-- <a href="javascript:void(0)" class="filter-close"></a> --}}
    <h5 class="title d-flex justify-content-between">
        <i class="ion-search"></i>&nbsp;{{ __('suda_lang::press.search') }}
        <i class="ion-close-circle-outline float-end filter-close color: #6b6b6b;"></i>
    </h5>

    <div class="col-sm-12 content px-3">


        <form action="{{ admin_url('article/filter') }}" class="filter-form">
            
            <input type="hidden" name="view_type" value="{{ $view_type }}">

            <div class="mb-3{{ $errors->has('title') ? ' has-error' : '' }}" >
                <input type="text" name="title" class="form-control" id="title" placeholder="请输入标题">
            </div>

            @php

            $selects = [];

            if(isset($filter['category'])){
                $selects = $filter['category'];
                
            }
            
            @endphp

            <div class="mb-3{{ $errors->has('category') ? ' has-error' : '' }}" >
                <select name="category"  placeholder="请选择分类" class="select-category-search form-control">
                    <option value="">-选择分类-</option>
                    <option value="0">所有分类</option>
                    @if($categories)
                    
                    @include('view_suda::taxonomy.category.options',['cates'=>$categories,'child'=>0,'select'=>$selects])
                    
                    @endif
                </select>
            </div>

            <div class="mb-3{{ $errors->has('date') ? ' has-error' : '' }}" >
                <select name="date" class="form-control ">
                <option value="">-选择日期-</option>
                    @if($dates && $dates->count()>0)
                    @foreach($dates as $date)
                    <option value="{{ $date->year.'-'.$date->month }}" @if(isset($filter['date']) && $filter['date']== $date->year.'-'.$date->month) selected @endif>{{ $date->year.'-'.$date->month }}</option>
                    @endforeach
                    @endif
                </select>
            </div>

            <div class="mb-3{{ $errors->has('author') ? ' has-error' : '' }}" >
                <input type="text" name="author" class="form-control " id="filter_search_author" placeholder="请输入发布者名称">
            </div>

            <div class="mb-3{{ $errors->has('updated_at') ? ' has-error' : '' }}" >
                <label for="updated_at">
                    更新时间
                </label>
                <div class="input-group">
                    
                        <div class="col-sm-4">
                            <select name="updated_at_method" class="form-select">
                                <option value="equal">等于</option>
                                <option value="than">大于</option>
                                <option value="less">小于</option>
                            </select>
                        </div>
                        <div class="col-sm-8">
                            <input type="text" data-toggle="datetimepicker" name="updated_at" class="form-control " id="updated_at" placeholder="请选择时间">
                        </div>
                    
                </div>
            </div>

            <div class="mb-3">
                              
                <label for="stick_top">
                    置顶
                </label>
                <div class="form-check form-check-inline">
                    <input type="radio" class="form-check-input" name="stick_top" placeholder="{{ __('suda_lang::press.yes') }}" value="1" >
                    <label class="form-check-label" for="stick_top">{{ __('suda_lang::press.yes') }}</label>
                </div>
                <div class="form-check form-check-inline">
                    <input type="radio" class="form-check-input" name="stick_top" placeholder="{{ __('suda_lang::press.no') }}" value="0" checked>
                    <label class="form-check-label" for="stick_top">{{ __('suda_lang::press.no') }}</label>
                </div>

            </div>

            <div class="mb-3">
                    <label for="disable">
                            发布
                    </label>    
                    <div class="form-check form-check-inline">
                        <input type="radio" class="form-check-input" name="disable" placeholder="{{ __('suda_lang::press.yes') }}" value="0" >
                        <label class="form-check-label" for="disable">{{ __('suda_lang::press.yes') }}</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input type="radio" class="form-check-input" name="disable" placeholder="{{ __('suda_lang::press.no') }}" value="1" checked>
                        <label class="form-check-label" for="disable">{{ __('suda_lang::press.no') }}</label>
                    </div>

            </div>


            <div class="mb-3">
                <button class="btn btn-primary btn-sm filter-submit">立即搜索</button>
                <button class="btn btn-light btn-sm filter-reset">重置条件</button>
            </div>
        
        </form>

        
    </div>


</div>