@extends('view_path::layouts.default')

@section('content')

<div class="container container-page py-3" >

    <div class="row">
        
        <div class="col-sm-9">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/pages') }}">页面</a></li>
                  <li class="breadcrumb-item active" aria-current="page">{{ $page->title }}</li>
                </ol>
              </nav>
            <div class="page-heading">
                <h1>{{ $page->title }}</h1>
                @if(isset($page_preview))
                    <span class="help-block bg-warning text-dark p-2">
                        当前为预览模式，有效期为1个小时
                    </span><br>
                    @endif
                <div class="meta-item created_at"><i class="ion-calendar"></i> {{ $page->created_at->format('Y-m-d') }}</div>
                <!-- @if(isset($hero_image))
                <img class="hero-img" src="{{ $hero_image['image'] }}">
                @endif -->
            </div>
            
            <div class="page-content my-3">
                
                {!! $page->content !!}
                
            </div>
            
            
            {{-- <div class="page-footer">
                
                    
                
            </div> --}}
    </div>
    <div class="col-sm-3">
        
                <div class="widgets-content">
                    <div class="card mb-3">
                        <div class="card-body">
                            {!! Theme::widget('sidebar') !!}
                        </div>
                    </div>
                </div>
            
    </div>
    </div>
   
    
    
</div>

@endsection