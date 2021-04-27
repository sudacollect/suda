
<div class="col-sm-12 ">
    <div class="page-heading" style="float:initial;width:100%;">
        <h3 href="{{ admin_url('entry/extension/'.$sdcore->extension->slug) }}"><i class="ion-boat"></i>&nbsp;管理面板</h3>
    </div>

    <div class="row">
    @if(isset($config['menus']))
    @foreach ($config['menus'] as $item)
    @if(!isset($item['slug']) || !isset($item['title']))
        @continue
    @else 
        @php
        $item['extension_slug'] = $sdcore->extension->slug;
        @endphp
    @endif
    <div class="col-sm-2">
       <div class="card mb-3 entry_block">
            
       <div class="card-body entry_extension" @if(isset($item['icon_bg_color'])) style="background-color:{{ $item['icon_bg_color'] }}" @endif>
                <ul>
                    <li>
                        <div class="quickin-content">
                        <div class="quickin">
                            <a class="app-info" href="{{ menu_link((object)$item) }}" title="{{ $item['title'] }}">
                                @if(isset($item['icon_url']))
                                <img src="{{ extension_asset($sdcore->extension->slug,$item['icon_url']) }}" class="icon icon-extension-menu">
                                @else
                                <i class="icon-extension-menu {{ $item['icon_class'] }}" @if(isset($item['font_color'])) style="color:{{ $item['font_color'] }}" @endif></i>
                                @endif
                                <span @if(isset($item['font_color'])) style="color:{{ $item['font_color'] }}" @endif>{{ $item['title'] }}</span>
                            </a>
                        </div>
                        </div>
                        
                    </li>
                
                    </ul>
            </div>

        </div>
    </div>
    @endforeach
    @endif
    </div>
    
</div>






       