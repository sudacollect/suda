@if($medias)
@foreach ($medias as $item)
<li class="col-sm-2 col-sm-3 col-6">
    <div style="display:block;width:100%;height:100%;position:relative;">
        <div class="media-div" style="position:absolute;left:0;top:0;width:100%;height:100%;text-align:center;overflow:hidden;border-radius:4px;background:url({{ suda_image($item,['size'=>'medium','imageClass'=>'image_icon',"url"=>true],false) }}) center center no-repeat;background-size:cover;">
        
            
            <a href="{{ url('sdone/media/view/'.$item->id) }}" class="pop-modal" media_id="{{ $item->id }}" style="display:block;width:100%;height:100%;font-size:0;" title="{{ __('suda_lang::press.edit') }}">
                {!! suda_image($item,['size'=>'medium','withLarge'=>true,'imageClass'=>'image_show hidden'],false) !!}
            </a>
            
        
        </div>
    </div>
</li>
@endforeach
@endif