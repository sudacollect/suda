<div class="widget widget-gallery">

    @if(isset($content) && isset($content['image_title']))
    <h3>{{ $content['image_title'] }}</h3>
    @endif
    
    @if(isset($medias) && count($medias) > 0)
        
    <div class="owl-carousel owl-theme">
        @foreach($medias as $media)
        <div class="item">

            <img src="{{ suda_image($media,['url'=>true,'size'=>'medium']) }}">

        </div>
        @endforeach
    </div>

    @endif

</div>

@include('view_suda::widgets.theme.gallery.view_set')