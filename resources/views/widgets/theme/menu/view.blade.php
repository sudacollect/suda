<div class="widget widget-menus">

    @if(isset($content) && isset($content['title']))
    <h3>{{ $content['title'] }}</h3>
    @endif

    @if(isset($items) && count($items)>0)

    @if(isset($items['parent_items']) && count($items['parent_items'])>0)
    @include('view_suda::widgets.theme.menu.view_content',['items'=>$items['parent_items']])
    @endif

    

    @endif

</div>