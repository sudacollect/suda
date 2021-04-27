@if(isset($breadcrumbs) && count($breadcrumbs)>0)
<nav aria-label="breadcrumb">
<ol class="breadcrumb">
    @foreach($breadcrumbs as $k=>$bread)
    
    <li class="breadcrumb-item">
    @if(!empty($bread))
    <a href="{{ url($bread) }}">{{ $k }}</a>
    @else
    {!! $k !!}
    @endif
    </li>

    @endforeach
</ol>
</nav>
@endif