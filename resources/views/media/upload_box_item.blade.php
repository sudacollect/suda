<div class="x-suda-upload-box-div">
        
    <div class="x-suda-upload-item uploadbox @if($media instanceof \Gtd\Suda\Models\Media) uploadbox-filled @endif" id="media-0" _data_type="{{ $media_type }}" _data_crop="{{ $is_crop }}" @if(isset($input_name) && !empty($input_name)) _data_name="{{ $input_name }}" @endif @if(isset($max)) media_max="{{$max}}" @else media_max="1" @endif>
        
        @if($media instanceof \Gtd\Suda\Models\Media)
        <img src="{{ suda_image($media,['size'=>'medium', 'url'=>true]) }}" title="{{ $media->name }}" class="x-suda-upload-image image-medium image_show">    
        <input type="hidden" name="{{ $hidden_input_name }}" value="{{ $media->id }}">
        <div class='x-suda-upload-action'>
            <span class="btn btn-dark btn-xs x-suda-upload-action-switch"><i class='ion-sync-circle'></i>替换</span>
            <span class="btn btn-dark btn-xs x-suda-upload-action-delete"><i class='ion-close-circle'></i>删除</span>
        </div>
        @endif
        
    </div>
    @if(isset($show_remove) && $show_remove)
    <span class="x-suda-remove-upload-item">
        <i class="ion-close-circle"></i>
    </span>
    @endif

</div>