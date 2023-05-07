@extends('view_path::layouts.default')

@section('content')

<div class="container-fluid pb-md-5">

    @if(isset($page_preview))
    <div class="toast-container position-fixed bottom-0 start-0 p-3">
        <div id="liveToast" class="toast show text-bg-light" role="alert" aria-live="assertive" aria-atomic="true">
            {{-- <div class="toast-header"> --}}
            {{-- <i class="ion-balloon me-1"></i> --}}
            {{-- <strong class="me-auto">预览模式</strong> --}}
            {{-- <small>11 mins ago</small> --}}
            {{-- <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button> --}}
            {{-- </div> --}}
            <div class="toast-body">
            当前为预览模式
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
                
                <div class="meta-item created_at">
                    <i class="ion-calendar-outline"></i> {{ $page->created_at->format('Y-m-d') }}
                </div>
                
            </div>
            
            <div class="page-content my-3 trix-content">
                
                {!! $page->content !!}
                
            </div>
            
            
            {{-- <div class="page-footer">
                
                    
                
            </div> --}}
    </div>
    <div class="col-sm-3">
        
        <div class="widgets-content">
            {!! Theme::widget('sidebar') !!}
        </div>
            
    </div>
    </div>
   
    
    
</div>

@endsection

@push('styles')
<style>
    .trix-content {
  line-height: 1.5; }
  .trix-content * {
    box-sizing: border-box;
    margin: 0;
    padding: 0; }
  .trix-content h1 {
    font-size: 1.2em;
    line-height: 1.2; }
  .trix-content blockquote {
    border: 0 solid #ccc;
    border-left-width: 0.3em;
    margin-left: 0.3em;
    padding-left: 0.6em; }
  .trix-content [dir=rtl] blockquote,
  .trix-content blockquote[dir=rtl] {
    border-width: 0;
    border-right-width: 0.3em;
    margin-right: 0.3em;
    padding-right: 0.6em; }
  .trix-content li {
    margin-left: 1em; }
  .trix-content [dir=rtl] li {
    margin-right: 1em; }
  .trix-content pre {
    display: inline-block;
    width: 100%;
    vertical-align: top;
    font-family: monospace;
    font-size: 0.9em;
    padding: 0.5em;
    white-space: pre;
    background-color: #eee;
    overflow-x: auto; }
  .trix-content img {
    max-width: 100%;
    height: auto; }
  .trix-content .attachment {
    display: inline-block;
    position: relative;
    max-width: 100%; }
    .trix-content .attachment a {
      color: inherit;
      text-decoration: none; }
      .trix-content .attachment a:hover, .trix-content .attachment a:visited:hover {
        color: inherit; }
  .trix-content .attachment__caption {
    text-align: center; }
    .trix-content .attachment__caption .attachment__name + .attachment__size::before {
      content: ' \2022 '; }
  .trix-content .attachment--preview {
    width: 100%;
    text-align: center; }
    .trix-content .attachment--preview .attachment__caption {
      color: #666;
      font-size: 0.9em;
      line-height: 1.2; }
  .trix-content .attachment--file {
    color: #333;
    line-height: 1;
    margin: 0 2px 2px 2px;
    padding: 0.4em 1em;
    border: 1px solid #bbb;
    border-radius: 5px; }
  .trix-content .attachment-gallery {
    display: flex;
    flex-wrap: wrap;
    position: relative; }
    .trix-content .attachment-gallery .attachment {
      flex: 1 0 33%;
      padding: 0 0.5em;
      max-width: 33%; }
    .trix-content .attachment-gallery.attachment-gallery--2 .attachment, .trix-content .attachment-gallery.attachment-gallery--4 .attachment {
      flex-basis: 50%;
      max-width: 50%; }
</style>
@endpush