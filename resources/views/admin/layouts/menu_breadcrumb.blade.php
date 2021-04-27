@if(isset($sdcore->menu_breadcrumbs) && count($sdcore->menu_breadcrumbs)>0)
<nav aria-label="breadcrumb" class="bg-transparent pt-0 float-breadcrumb">
    <ol class="breadcrumb">
        
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