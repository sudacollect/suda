<div class="widget widget-categories">

    @if(isset($content) && isset($content['title']))
    <h3>{{ $content['title'] }}</h3>
    @endif

    @if(isset($categories) && $categories->count()>0)

    @include('view_suda::widgets.theme.category.view_content',['categories'=>$categories])

    @endif

</div>