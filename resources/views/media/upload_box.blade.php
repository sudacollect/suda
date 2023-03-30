<div class="upload-modal uploadbox-group list-group list-group-column list-images @if(isset($columns))list-images-{{ $columns }}@else list-images-1 @endif">

@if(isset($data) && !empty($data))

    @if(is_array($data) && count($data)>0)
    
        @foreach($data as $k=>$media)
        <div class="list-group-item">
            @if($k>0)
            <span class="remove-modal-item">
                <i class="ion-close-circle"></i>
            </span>
            @endif
            <div class="upload-item uploadbox uploadbox-filled" id="media-{{ $k }}" _data_type="{{ $media_type }}" _data_crop="{{ $is_crop }}" @if(isset($input_name) && !empty($input_name)) _data_name="{{ $input_name }}" @endif media_max="@if(isset($max)){{$max}}@else{{1}}@endif">
        
                <img src="{{ suda_image($media,['size'=>'large','imageClass'=>'image_icon','url'=>true],false) }}" title="{{ $media->name }}" class="zpress-image image-medium image_show">
                
                @if(isset($input_name) && !empty($input_name))
                <input type="hidden" name="images[{{ $input_name }}][]" value="{{ $media->id }}">
                @else
                <input type="hidden" name="images[]" value="{{ $media->id }}">
                @endif
        
            </div>
    
        </div>
        @endforeach
        
        @if(count($data) < $max)
            <!-- 提示可以继续上传 -->
            <div class="list-group-item">
                <span class="remove-modal-item">
                    <i class="ion-close-circle"></i>
                </span>
                <div class="upload-item uploadbox" id="media-0" _data_type="{{ $media_type }}" _data_crop="{{ $is_crop }}" @if(isset($input_name) && !empty($input_name)) _data_name="{{ $input_name }}" @endif media_max="@if(isset($max)){{$max}}@else{{1}}@endif">
                </div>
            </div>
        
        @endif
    
    @else
    
        @if($data instanceof \Illuminate\Database\Eloquent\Collection)

        @if($data->count()>0)

            @foreach($data as $media)

            <div class="list-group-item">
                
                <div class="upload-item uploadbox uploadbox-filled" id="media-0" _data_type="{{ $media_type }}" _data_crop="{{ $is_crop }}" @if(isset($input_name) && !empty($input_name)) _data_name="{{ $input_name }}" @endif media_max="@if(isset($max)){{$max}}@else{{1}}@endif">
            
                    <img src="{{ suda_image($media,['size'=>'large','imageClass'=>'image_icon','url'=>true,false]) }}" title="{{ $media->name }}" class="zpress-image image-medium image_show">

                    @if(isset($input_name) && !empty($input_name))
                    
                    <input type="hidden" name="images[{{ $input_name }}][]" value="{{ $media->id }}">

                    @else

                    <input type="hidden" name="images[]" value="{{ $media->id }}">

                    @endif
                    
            
                </div>
        
            </div>

            @endforeach
            
        @endif

        @else
        
        <div class="list-group-item">
            
            <div class="upload-item uploadbox uploadbox-filled" id="media-0" _data_type="{{ $media_type }}" _data_crop="{{ $is_crop }}" @if(isset($input_name) && !empty($input_name)) _data_name="{{ $input_name }}" @endif @if(isset($max)) media_max="{{$max}}" @else media_max="1" @endif>
                
                
                <img src="{{ suda_image($data,['size'=>'medium','imageClass'=>'image_icon','url'=>true,false]) }}" title="{{ $data->name }}" class="zpress-image image-medium image_show">
                

                @if(isset($input_name) && !empty($input_name))
                    
                <input type="hidden" name="images[{{ $input_name }}]" value="{{ $data->id }}">

                @else

                <input type="hidden" name="images[]" value="{{ $data->id }}">

                @endif


                
                
            </div>
    
        </div>
        
        @endif
        
    @endif
    
@else
    
    <div class="list-group-item">
        
        <div class="upload-item uploadbox" id="media-0" _data_type="{{ $media_type }}" _data_crop="{{ $is_crop }}" @if(isset($input_name) && !empty($input_name)) _data_name="{{ $input_name }}" @endif media_max="@if(isset($max)){{$max}}@else{{1}}@endif">
            
        </div>
    
    </div>
    
    
@endif

</div>