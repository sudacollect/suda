@if($categories->count()>0)
<ul>
@foreach($categories as $category)

<li data-parent="{{ $category->parent }}">
    <a href="{{ url('category/'.$category->term->slug) }}" title="{{ $category->term->name }}">
        {{ $category->term->name }}
    </a>

    @if(!isset($content['parent']))

    @if($category->children)
    @include('view_suda::widgets.theme.category.view_content',['categories'=>$category->children])
    @endif

    @endif

</li>

@endforeach
</ul>
@endif