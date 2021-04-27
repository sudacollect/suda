<div class="widget widget-articles">

    @if(isset($content) && isset($content['title']))
    <h3>{{ $content['title'] }}</h3>
    @endif

    @if(isset($articles) && $articles->count()>0)

    <ul>
    @foreach($articles as $article)

    <li data-update="{{ $article->updated_at }}" data-stick_top="{{ $article->stick_top }}">
        @if(isset($content['heroimage']) && $content['heroimage']==1)

        <img src="{{ suda_image($article->heroimage->media,['url'=>true,'size'=>'medium']) }}" class="hero-image">

        @endif
        <a href="{{ $article->real_url }}" title="{{ $article->title }}">
            @if($article->stick_top==1) <span class="label label-success stick_top">[置顶]</span>@endif{{ $article->title }}
            @if(isset($content) && isset($content['datetime']))
            <span class="datetime">{{ $article->updated_at->format('Y-m-d') }}</span>
            @endif
        </a>
    </li>

    @endforeach
    </ul>

    @endif

</div>