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
                <li class="breadcrumb-item"><a href="{{ url('/pages') }}">页面</a></li>
                  <li class="breadcrumb-item active" aria-current="page">{{ $page->title }}</li>
                </ol>
              </nav>
            <div class="page-heading">
                <h1>{{ $page->title }}</h1>
                
                <div class="meta-item created_at"><i class="ion-calendar"></i> {{ $page->created_at->format('Y-m-d') }}</div>
                
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