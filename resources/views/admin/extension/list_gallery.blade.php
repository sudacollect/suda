@if(count($ext_list)>0)
<div class="row mt-3" @if($active=="enabled") id="app_sort_list" @endif>
@foreach ($ext_list as $ext_slug=>$item)
<div class="col-sm-3 mb-3">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-3">
                    <img src="{{ admin_url('manage/extension/'.$item['slug'].'/logo') }}" class="w-100 icon-extension" alt="{{ $item['slug'] }}">
                    @if($active == 'enabled')
                        <div class="form-check form-switch p-0 d-flex justify-content-center mt-3">
                            <input type="checkbox" class="quickin-change form-check-input m-0" href="{{ admin_url('manage/extension/'.$item['slug'].'/setQuickin') }}" data-id="{{ $item['slug'] }}" @if(array_key_exists($item['slug'],$quickins)) data-content="checked" checked @else data-content="" @endif id="{{ $item['slug'] }}"  >
                        </div>
                    @endif
                </div>

                <div class="col-9">

                    <h5 class="card-title">
                        <strong class="ext-title" data-slug="{{ $item['slug'] }}">{{ $item['name'] }}</strong><small class="text-dark ms-2">v{{ $item['version'] }}</small>
                    </h5>

                    @if(isset($item['author']))
                    <a href="{{ $item['website'] }}" target="_blank" title="{{ $item['author'] }}" class="help-block">
                        <i class="ion-person"></i>&nbsp;{{ Illuminate\Support\Str::limit($item['author'], $limit = 24, $end = '...') }}
                    </a>
                    @endif
                    
                    @if(isset($item['description']))
                    <div class="help-block text-muted" title="{{ $item['description'] }}">
                        {{ Illuminate\Support\Str::limit($item['description'], $limit = 24, $end = '...') }}
                    </div>
                    @endif

                    @php
                    try {
                        $item['date'] = \Illuminate\Support\Carbon::parse($item['date']);
                    } catch (\Exception $e) {
                        // echo 'invalid date, enduser understands the error message';
                        $item['date'] = \Illuminate\Support\Carbon::parse('2019-12-12');
                    }
                    @endphp
                    @if(isset($item['date']))
                    <span class="help-block" style="font-size:0.8rem;">{{ $item['date']->format('Y-m-d') }}</span>
                    @endif

                    <div class="d-flex justify-content-start gap-2">
                        @if($active == 'enabled')

                            @if(\Gtd\Suda\Auth\OperateCan::superadmin($soperate))
                            <button class="btn btn-light btn-xs pop-modal" href="{{ admin_url('manage/extension/'.$item['slug'].'/uninstall-confirm') }}" title="卸载应用" data-id="{{ $item['slug'] }}"><i class="ion-trash-outline"></i></button>

                            <button class="btn btn-light btn-xs btn-refresh" title="刷新应用" href="{{ admin_url('manage/extension/'.$item['slug'].'/refresh') }}" data-id="{{ $item['slug'] }}"><i class="ion-sync"></i>&nbsp;{{ __('suda_lang::press.extensions.refresh') }}</button>
                            @endif

                            @if(isset($item['setting']['setting_page']))
                            <a href="{{ admin_ext_url($item['slug'],$item['setting']['setting_page']) }}" class="btn btn-light btn-xs" ><i class="ion-settings-sharp"></i></a>
                            @endif
                            
                            @if(isset($item['default_page_url']))
                            <a href="{{ admin_url($item['default_page_url']) }}" class="btn btn-light btn-xs" title="Home" ><i class="ion-home"></i></a>
                            @endif
                            
                        @else
                            @if(\Gtd\Suda\Auth\OperateCan::operation($soperate))
                            <button class="btn btn-success btn-sm btn-install" href="{{ admin_url('manage/extension/'.$item['slug'].'/install') }}" data-id="{{ $item['slug'] }}"><i class="ion-arrow-down-circle-outline me-1"></i>{{ __('suda_lang::press.extensions.install') }}</button>
                            @endif
                        @endif
                    </div>
                </div>

            </div>
            

        </div>
        
    </div>
</div>
@endforeach
</div>

@else

<div class="row mt-3">
    <div class="col-12">
        <x-suda::empty-block empty="extensions not found" />
    </div>
</div>

@endif