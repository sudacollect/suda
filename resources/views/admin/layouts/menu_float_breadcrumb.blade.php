@if(isset($sdcore->menu_breadcrumbs) && count($sdcore->menu_breadcrumbs)>0)
<nav aria-label="breadcrumb" class="col-12 bg-transparent float-breadcrumb">
    <ol class="breadcrumb py-0 px-0 mb-1">
        
        @foreach($sdcore->menu_breadcrumbs as $bitem)
            <li class="breadcrumb-item @if(!$loop->first && $loop->last) active @endif">
                <a @if(!empty($bitem['route'])) href="{{ route($bitem['route']) }}" @else href="{{ admin_url($bitem['url']) }}" @endif>
                    @if(isset($bitem['icon'])){!! $bitem['icon'] !!}@endif{{ __($bitem['title']) }}
                </a>
            </li>
        @endforeach
        
        
    </ol>
</nav>
@endif