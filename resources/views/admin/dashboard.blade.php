@extends('view_path::layouts.default',['no_breadcrumb'=>true])


@section('content')

<div class="container container-fluid py-3">

    @if(!isset($sdcore->settings->dashboard) || (isset($sdcore->settings->dashboard->dashboard_apps) && count((array)$sdcore->settings->dashboard->dashboard_apps)<1))
    <div class="row suda-row d-flex flex-row justify-content-start flex-wrap">
        {{ Sudacore::widget('\Gtd\Suda\Widgets\Intro') }}
    </div>
    @endif

    @if(isset($sdcore->settings->dashboard) && isset($sdcore->settings->dashboard->dashboard_apps))
    <div class="row suda-row d-flex flex-row justify-content-start flex-wrap">
        
        {{-- {{ Sudacore::widget('dashaboard.start',['soperate'=>$soperate]) }} --}}

        <div class="col-sm-9">

            <div class="row">
                @if(isset($sdcore->settings->dashboard->dashboard_apps->quickin) && $sdcore->settings->dashboard->dashboard_apps->quickin=='on')
                {{ Sudacore::widget('dashaboard.quickin') }}
                @endif
                
                @if(count(config('suda_custom.widget_extends',[]))>0)
                    @foreach(config('suda_custom.widget_extends.left',[]) as $extend)
                    {{ Sudacore::widget('suda_widget_extend',['extend'=>$extend]) }}
                    @endforeach
                @endif
            </div>

        </div>

        <div class="col-sm-3">

            <div class="row">
                @if(isset($sdcore->settings->dashboard->dashboard_apps->welcome) && $sdcore->settings->dashboard->dashboard_apps->welcome=='on')
                {{ Sudacore::widget('\Gtd\Suda\Widgets\Welcome') }}
                @endif

                {{-- {{ Sudacore::widget('dashaboard.dashhelp') }} --}}

                {{ Sudacore::widget('dashaboard.news') }}

                @if(count(config('suda_custom.widget_extends',[]))>0)
                    @foreach(config('suda_custom.widget_extends.right',[]) as $extend)
                    {{ Sudacore::widget('suda_widget_extend',['extend'=>$extend]) }}
                    @endforeach
                @endif

            </div>

        </div>

    </div>
    @endif

</div>
@endsection

@push('styles')
<style>

.userinfo{
    text-align:center;
    font-size:1.8rem;
    font-weight:bold;
}
.userinfo .avatar{
    max-width:100%;
    max-width:64px;
    margin:0 auto;
    display:block;
    border-radius:50%;
}

.welcome .list-group-item .badge{
    font-size:1.2rem;
}

h4.card-header i.dash-switch{
    font-size:1rem;
}


@media (max-width: 767px) {
    .userinfo .avatar{
        max-width:100%;
    }
}

</style>
@endpush

@push('scripts')

<script>
$(document).ready(function(){
    
    $('.suda-row').on('click','i.dash-switch',function(){
        
        if($(this).hasClass('ion-chevron-up')){
            $(this).parent('.card-header').next('.card-body').slideDown('fast',function(){
                
            });
            $(this).addClass('ion-chevron-down').removeClass('ion-chevron-up');

        }else if($(this).hasClass('ion-chevron-down')){
            $(this).parent('.card-header').next('.card-body').slideUp('fast',function(){
                
            });
            
            $(this).addClass('ion-chevron-up').removeClass('ion-chevron-down');
        }

    });

});

</script>

@endpush