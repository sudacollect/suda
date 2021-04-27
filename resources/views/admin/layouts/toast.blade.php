@if (session('status'))
@php
if(!isset($toast_top)){
    $toast_top = '1rem';
}else{
    $toast_top .= 'px';
}
@endphp
<div class="suda-toast toast" @if(isset(session('status')['autohide'])) data-autohide="session('status')['autohide']" @else data-autohide="true" @endif @if(isset(session('status')['delay'])) data-delay="{{ session('status')['delay'] }}" @else data-delay="2500" @endif style="position: absolute; top:{{ $toast_top }}; right: 1rem;min-width:160px;z-index:9999;">
    <div class="toast-header @if(isset(session('status')['code'])) text-{{ session('status')['code'] }} @else text-dark @endif" style="background:#efefef;">
    <i class="ion-alert-circle"></i>&nbsp;
    <strong class="mr-auto">提示</strong>
    {{-- <small>11 mins ago</small> --}}
    <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
    </div>
    <div class="toast-body">
        {!! session('status')['msg'] !!}
    </div>
</div>
@endif