<div class="card mt-3">
                
        <div class="card-body">
            
            <!-- list start -->
        @if(count($ext_list)>0)
        
                <div class="table-responsive data-list">
                    <table class="table table-hover">
                    <thead class="bg-light">
                        <tr>
                            <th width="60px" class="border-top-0">图标</th>
                            <th width="20%" class="border-top-0">应用</th>
                            <th width="10%" class="border-top-0">开发者</th>
                            <th width="8%" class="border-top-0">启用</th>
                            <th width="15%" class="border-top-0">更新</th>
                            @if($active=="enabled")
                            <th width="10%" class="border-top-0">快速进入</th>
                            @endif
                            <th class="border-top-0">操作</th>
                        </tr>
                    </thead>
                        <tbody @if($active=="enabled") id="app_sort_list" @endif>
                        @if($ext_list)
                        @foreach ($ext_list as $ext_slug=>$item)
                        <tr>
                            <td width="60px">
                                
                                <img src="{{ extension_logo($item['slug']) }}" class="icon icon-extension" style="max-width:48px;border-radius:6px;">
                                
                            </td>
                            
                            <td width="20%">
                                <div data-slug="{{ $item['slug'] }}" class="ext-title extension-popover" style="display:inline;">
                                    <strong>{{ $item['name'] }}</strong>
                                    <span class="tag tag-version">v{{ $item['version'] }}</span>
                                </div>
                                <span class="help-block" title="{{ $item['description'] }}">
                                    {{ Illuminate\Support\Str::limit($item['description'], $limit = 24, $end = '...') }}
                                </span>
                            </td>
                            
                            <td width="10%">
                                <a href="{{ $item['website'] }}" target="_blank" title="关于{{ $item['author'] }}">
                                    {{ $item['author'] }}
                                </a>
                            </td>

                            <td width="8%">
                                @if(array_key_exists($item['slug'],$available_ext_list))
                                    是
                                @else
                                    否
                                @endif
                            </td>
                            
                            <td width="15%">
                                {{ $item['date'] }}
                            </td>

                            
                            @if($active=="enabled")
                            <td width="10%">
                                
                            <div class="custom-control custom-switch">
                                <input type="checkbox" href="{{ admin_url('manage/extension/'.$item['slug'].'/setQuickin') }}" data-id={{ $item['slug'] }} class="quickin-change custom-control-input" @if(array_key_exists($item['slug'],$quickins)) data-content="checked" checked @endif id="{{ $item['slug'] }}" >
                                <label class="custom-control-label" for="{{ $item['slug'] }}">&nbsp;</label>
                                </div>
                            </td>
                            @endif

                            
                            
                            <td>
                                
                                @if(array_key_exists($item['slug'],$available_ext_list))
                                    @if(isset($item['default_page_url']))
                                    <a href="{{ admin_url($item['default_page_url']) }}" class="btn btn-light btn-xs" title="进入应用" ><i class="ion-home"></i>&nbsp;首页</a>
                                    @endif
                                    @if(isset($item['setting']['setting_page']))
                                    <a href="{{ admin_url('extension/'.$item['setting']['setting_page']) }}" class="btn btn-primary btn-xs" title="应用配置" ><i class="ion-settings-sharp"></i>&nbsp;配置</a>
                                    @endif
                                    
                                    <button class="btn btn-light btn-xs btn-refresh" title="刷新应用" href="{{ admin_url('manage/extension/'.$item['slug'].'/refresh') }}" data-id="{{ $item['slug'] }}"><i class="ion-sync-circle"></i>&nbsp;刷新</button>
                                    <button class="btn btn-light btn-xs btn-uninstall" href="{{ admin_url('manage/extension/'.$item['slug'].'/uninstall') }}" title="卸载应用" data-id="{{ $item['slug'] }}"><i class="ion-trash"></i>&nbsp;卸载</button>
                                @else
                                    <button class="btn btn-light btn-xs btn-install" href="{{ admin_url('manage/extension/'.$item['slug'].'/install') }}" title="安装" data-id="{{ $item['slug'] }}"><i class="ion-flag"></i>&nbsp;安装</button>
                                @endif
                            
                            </td>
                            
                        </tr>
                        @endforeach
                        @endif
                        </tbody>
                    </table>
                
                </div>
            
            
            <!-- list end -->
        @else

            <div class="col-sm-12  z-empty z-empty-content">
                <div class="empty-icon"></div>
                <p>
                    <a target="_blank" href="{{ url('https://panel.cc') }}">发现更多应用</a>
                </p>
    
            </div>

        @endif
            
        </div>
        
        <div class="card-footer">
            当前共有 {{ $data_count }} 个应用
        </div>
        
    </div>
    
</div>