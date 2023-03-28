@if(count($ext_list)>0)
<div class="row mt-3" id="app_sort_list">
@foreach ($ext_list as $ext_slug=>$item)
<div class="col-sm-3 mb-5">
    <div class="card">
        
        

        <div class="card-body">
            <div class="row">
                <div class="col-sm-3">
                    <img src="{{ extension_logo($item['slug']) }}" class="w-100 icon-extension" alt="...">
                </div>

                <div class="col-sm-9">

                    <h5 class="card-title">
                        <strong class="ext-title" data-slug="{{ $item['slug'] }}">{{ $item['name'] }}</strong><small class="text-dark ms-2">v{{ $item['version'] }}</small>
                    </h5>

                    <a href="{{ $item['website'] }}" target="_blank" title="关于{{ $item['author'] }}" class="help-block">
                        <i class="ion-person"></i>&nbsp;{{ $item['author'] }}
                    </a>
                    
                    <h6 class="help-block mb-2 text-muted" title="{{ $item['description'] }}">
                        {{ Illuminate\Support\Str::limit($item['description'], $limit = 24, $end = '...') }}
                    </h6>
                    <span class="help-block">{{ $item['date'] }}</span>

                </div>

            </div>
            

        </div>
        <div class="card-footer bg-white">
            <div class="float-right">
            @if(array_key_exists($item['slug'],$available_ext_list))

                @if($soperate->user_role >=9)
                <button class="btn btn-light btn-xs btn-uninstall" href="{{ admin_url('manage/extension/'.$item['slug'].'/uninstall') }}" title="卸载应用" data-id="{{ $item['slug'] }}"><i class="ion-trash-outline"></i>&nbsp;卸载</button>

                <button class="btn btn-light btn-xs btn-refresh" title="刷新应用" href="{{ admin_url('manage/extension/'.$item['slug'].'/refresh') }}" data-id="{{ $item['slug'] }}"><i class="ion-sync"></i>&nbsp;刷新</button>
                @endif

                @if(isset($item['default_page_url']))
                <a href="{{ admin_url($item['default_page_url']) }}" class="btn btn-light btn-xs" title="进入应用" ><i class="ion-home-outline"></i>&nbsp;首页</a>
                @endif
                
                @if(isset($item['setting']['setting_page']))
                <a href="{{ admin_url('extension/'.$item['setting']['setting_page']) }}" class="btn btn-primary btn-xs" title="应用配置" ><i class="ion-settings-sharp"></i>&nbsp;配置</a>
                @endif
                
                <div class="form-check form-switch float-start">
                    <input type="checkbox" class="quickin-change form-check-input" href="{{ admin_url('manage/extension/'.$item['slug'].'/setQuickin') }}" data-id="{{ $item['slug'] }}" @if(array_key_exists($item['slug'],$quickins)) data-content="checked" checked @else data-content="" @endif id="{{ $item['slug'] }}"  >
                    <label class="form-check-label" for="{{ $item['slug'] }}">&nbsp;</label>
                </div>

            @else
                @if($soperate->user_role >=9)
                <button class="btn btn-success btn-sm btn-install" href="{{ admin_url('manage/extension/'.$item['slug'].'/install') }}" title="安装" data-id="{{ $item['slug'] }}"><i class="ion-flag"></i>&nbsp;安装</button>
                @endif
            @endif
            </div>
            
        </div>
    </div>
</div>
@endforeach
</div>
@endif