@extends('view_path::layouts.default')

@section('content')

<div class="container container-page py-3">

    <div class="row">

        <div class="col-sm-9">

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/articles') }}">文章</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $article->title }}</li>
                </ol>
            </nav>
            
            <div class="page-heading">

                <h1 class="my-4">{{ $article->title }}</h1>
                
                <div class="meta-item created_at">
                    @if(isset($article_preview))
                    <span class="help-block bg-warning text-dark p-2">
                        当前为预览模式，有效期为1个小时
                    </span><br>
                    @endif
                    <i class="ion-today"></i> {{ $article->updated_at->format('Y-m-d') }}
                    @foreach($cates as $cate)
                        @if($cate->taxonomy && $cate->taxonomy->term->name)
                            <a class="badge badge-pill badge-primary text-white" style="font-size:1rem;" href="{{ url('/category/'.$cate->taxonomy->term->slug) }}"> {{ $cate->taxonomy->term->name }}</a>
                        @endif
                    @endforeach
                </div>
                
                
                {{-- @if(isset($hero_image))
                <img class="hero-img" src="{{ $hero_image['image'] }}">
                @endif --}}
            </div>
            
            <div class="page-content my-3">
                
                {!! $article->content !!}
                
            </div>
            
            
            <div class="page-footer">
                
                @if(isset($tags) && $tags->count()>0)
                
                <ul class="tags-list">
                    <li>标签:</li>
                    @foreach($tags as $tag)
                
                        <li><a href="{{ url('tag/'.$tag->name) }}" target="_blank" title="{{ $tag->name }}">{{ $tag->name }}</a></li>
                
                    @endforeach
                </ul>
                
                
                @endif
                
            </div>
        </div>

        <div class="col-sm-3">
            <div class="widgets-content">
            {!! Theme::widget('content') !!}
            </div>
        </div>
    </div>
    
</div>

@endsection