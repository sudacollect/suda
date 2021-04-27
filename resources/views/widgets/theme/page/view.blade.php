<div class="widget widget-pages">

    @if(isset($content) && isset($content['title']))
    <h3>{{ $content['title'] }}</h3>
    @endif

    @if(isset($pages) && $pages->count()>0)

    <ul>
    @foreach($pages as $page)

    <li data-update="{{ $page->updated_at }}" data-stick_top="{{ $page->stick_top }}">
        @if(isset($content['heroimage']) && $content['heroimage']==1)

        <img src="{{ suda_image($page->heroimage->media,['url'=>true,'size'=>'medium']) }}" class="hero-image">

        @endif
        <a href="{{ $page->real_url }}" title="{{ $page->title }}">@if($page->stick_top==1) <span class="label label-success stick_top">[置顶]</span>@endif{{ $page->title }}</a>
    </li>

    @endforeach
    </ul>

    @endif

</div>