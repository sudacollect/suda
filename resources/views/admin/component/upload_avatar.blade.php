<div class="component component-image component-avatar @if(isset($columns))component-columns-{{ $columns }} @else component-columns-1 @endif ">
    <div id="avatar-upload" class="avatar-upload" media_type="{{ $media_type }}" media_max="@if(isset($max)){{$max}}@else{{1}}@endif">
        
        <div class="fileinput-avatar">
            <div class="icons">
                <i class="glyphicon glyphicon-user"></i>
                <span>上传头像</span>
            </div>
            
            <input id="fileupload" type="file" media_type="operate" name="files[]">
            
            @if(isset($data))
            
            <img src="{{ suda_image($data->media,['size'=>'medium','url'=>true],false) }}" class="zpress-image avatar">
            
            @endif
            
        </div>
        <div id="progress" class="progress progress-mini" style="display:none;width:120px;">
            <div class="progress-bar progress-bar-success"></div>
        </div>
    </div>
    
</div>
