@if(count($items)>0)
<ul>
@foreach($items as $item)

<li data-slug="{{ $item['slug'] }}">
    <a href="{{ menu_link((object)$item,'site') }}" target="{{ $item['target'] }}" title="{{ $item['title'] }}">
        {{ $item['title'] }}
    </a>

    @if(isset($item['children']) && count($item['children'])>0)
        @include('view_suda::widgets.theme.menu.view_content',['items'=>$item['children']])
    @endif

</li>

@endforeach
</ul>
@endif