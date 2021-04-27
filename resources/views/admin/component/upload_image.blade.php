<div class="component component-image @if(isset($columns))component-columns-{{ $columns }} @else component-columns-1 @endif ">
    <div id="s-upload" class="s-upload" media_type="{{ $media_type }}" media_max="@if(isset($max)){{$max}}@else{{1}}@endif">
        <div class="filelists">
    	<ol class="filelist complete">
            @if(isset($data))
            
            @foreach($data as $k=>$media)
                <li data-index="{{$k}}">
                    <div class="file">
                        <img src="{{ suda_image($media,['size'=>'large','imageClass'=>'image_icon','url'=>true],false) }}" title="{{ $media->name }}" class="zpress-image image-medium image_show"><input type="hidden" name="images[{{ $media->id }}]" value="{{ $media->id }}">
                    </div>
                </li>
            @endforeach
            
            @endif
    	</ol>
    	<ol class="filelist queue"></ol>
        </div>
    </div>
</div>
