@extends('view_path::layouts.default')

@section('content')

<div class="col-lg-8 mx-auto pb-md-5 px-3">

    @if(isset($page_preview))
    <div class="toast-container position-fixed top-0 end-0 p-3">
        <div id="liveToast" class="toast show text-bg-warning" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
            <i class="ion-balloon me-1"></i>
            <strong class="me-auto">预览模式</strong>
            {{-- <small>11 mins ago</small> --}}
            {{-- <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button> --}}
            </div>
            <div class="toast-body">
            当前为预览模式，有效期为1个小时
            </div>
        </div>
    </div>
    @endif

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
                    
                    <i class="ion-today"></i> {{ $article->updated_at->format('Y-m-d') }}

                    @foreach($cates as $cate)
                        @if($cate->taxonomy && $cate->taxonomy->term->name)
                            <a class="badge rounded-pill bg-primary text-white" style="font-size:1rem;" href="{{ url('/category/'.$cate->taxonomy->term->slug) }}"> {{ $cate->taxonomy->term->name }}</a>
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