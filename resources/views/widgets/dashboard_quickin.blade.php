
<div class="col-sm-8 offset-sm-2 suda_page_body mt-5">
<h3 class="py-3 text-center">运营中心</h3>

@if(isset($exts))
<div class="row px-3">
@foreach ($exts as $item)
<div class="col-sm-2 dashboard-item dashboard-item-extensions">

    <div class="card border-0 bg-transparent shadow-none">

        <div class="card-body app_quickin">
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
@endif



</div>






       