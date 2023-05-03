
<div class="col-sm-8 offset-sm-2 suda_page_body mt-5">
{{-- <h3 class="py-3 text-center">Board</h3> --}}

@if(isset($exts) && count($exts) > 0)
<div class="row px-3">

@foreach ($exts as $item)
<div class="col-sm-2 dashboard-item dashboard-item-extensions">

    <div class="card border-0 bg-transparent shadow-none">

        <div class="card-body dashboard_quickin app_quickin">
            <ul style="display:block;width:100%;">
                <li>
                    @php
                    $url = $item['slug'].'/index';
                    if(isset($item['ext_entrance_menu'])){
                        isset($item['ext_entrance_menu']['url'])?$url=$item['slug'].'/'.$item['ext_entrance_menu']['url']:'';
                    }
                    @endphp
                    <a href="{{ admin_ext_url($url) }}" title="{{ $item['name'] }}">
                        <img src="{{ extension_logo($item['slug']) }}" class="icon icon-extension">
                        <span>{{ $item['name'] }}</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>

</div>

@endforeach
</div>

@else
<div class="row px-3">
    <div class="col-12">
        <div class="card">

            <div class="card-body">
                请联系管理员增加权限
            </div>
        </div>
    </div>
</div>

@endif



</div>






       