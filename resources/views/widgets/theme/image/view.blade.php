<div class="widget widget-image">

    @if(isset($content) && isset($content['image_title']))
    <h3>{{ $content['image_title'] }}</h3>
    @endif

    @if($media)
        @if(isset($content) && isset($content['link']))
        <a href="{{ $content['link'] }}" @if(isset($content) && isset($content['image_title'])) title="{{ $content['image_title'] }}" @endif>
        @endif
        <img src="{{ suda_image($media,['size'=>'large','url'=>true]) }}" @if(isset($content) && isset($content['image_title'])) alt="{{ $content['image_title'] }}" title="{{ $content['image_title'] }}" @endif>
        @if(isset($content) && isset($content['link']))
        </a>
        @endif
    @endif

</div>