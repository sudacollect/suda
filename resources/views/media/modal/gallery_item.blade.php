@if($medias)
@foreach ($medias as $item)
<li class="media-content">
    <div class="media-content-div">
        <div class="media-div">

            <a href="{{ url('sudarun/media/view/'.$item->id) }}" class="media-div-a" media_id="{{ $item->id }}" style="background:url({{ suda_image($item,['size'=>'medium','imageClass'=>'image_icon',"url"=>true],false) }}) center center no-repeat;background-size:cover;@if($item->original_type=='file') border:1px solid #eee; @endif">
                {!! suda_image($item,['size'=>'medium','withLarge'=>true,'imageClass'=>'image_show d-none'],false) !!}
            </a>
            
        
        </div>
    </div>
</li>
@endforeach
@endif