@if(isset($breadcrumbs) && count($breadcrumbs)>0)
<ol class="breadcrumb">
    @foreach($breadcrumbs as $k=>$bread)
    
    <li>
    @if(!empty($bread))
    <a href="{{ admin_url($bread) }}">{{ $k }}</a>
    @else
    {{ $k }}
    @endif
    </li>

    @endforeach
</ol>
@endif