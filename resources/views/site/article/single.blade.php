@extends('view_path::layouts.default')

@section('content')

<div class="container-fluid pb-md-5">

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
                
                <div class="meta-item created_at d-flex justify-content-between">
                    <span class="date"><i class="ion-today-outline me-2"></i>{{ $article->updated_at->format('Y-m-d') }}</span>
                    <span class="cate">
                    @foreach($cates as $cate)
                        @if($cate->taxonomy && $cate->taxonomy->term->name)
                            <a class="badge bg-secondary text-white" href="{{ url('/category/'.$cate->taxonomy->term->slug) }}"> {{ $cate->taxonomy->term->name }}</a>
                        @endif
                    @endforeach
                    </span>
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
                
                <div class="col">
                    <h5>标签:</h5>
                    @foreach($tags as $tag)
                
                    <span class="badge bg-light text-white">
                        <a href="{{ url('tag/'.$tag->name) }}" target="_blank" title="{{ $tag->name }}" class="text-dark">{{ $tag->name }}</a>
                    </span>
                
                    @endforeach
                </div>
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