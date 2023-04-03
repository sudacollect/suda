<div class="x-suda-upload-box uploadbox-group list-images @if(isset($columns))list-images-{{ $columns }}@else list-images-1 @endif">

@if(isset($data) && !empty($data))

    
    
    @if($data instanceof \Illuminate\Database\Eloquent\Collection)

        @if($data->count()>0)

            @foreach($data as $k=>$media)
            
            @php
                $hidden_input_name = 'images[]';
                if(isset($input_name) && !empty($input_name))
                {
                    $hidden_input_name = 'images['.$input_name.'][]';
                }
            @endphp

            @include('view_suda::media.upload_box_item',['show_remove'=>$k>0?1:0,'hidden_input_name'=>$hidden_input_name])

            @endforeach

            @if($data->count() < $max)
            <!-- 提示可以继续上传 -->
            <div class="x-suda-upload-box-div">
                <div class="x-suda-upload-item uploadbox" id="media-0" _data_type="{{ $media_type }}" _data_crop="{{ $is_crop }}" @if(isset($input_name) && !empty($input_name)) _data_name="{{ $input_name }}" @endif media_max="@if(isset($max)){{$max}}@else{{1}}@endif">
                </div>
                <span class="x-suda-remove-upload-item">
                    <i class="ion-close-circle"></i>
                </span>
            </div>
        
            @endif
            
        @endif

    @elseif($data instanceof \Gtd\Suda\Models\Media)
    
        @php
            $hidden_input_name = 'images[]';
            if(isset($input_name) && !empty($input_name))
            {
                $hidden_input_name = 'images['.$input_name.']';
            }
        @endphp

        @include('view_suda::media.upload_box_item',['media'=>$data ,'hidden_input_name'=>$hidden_input_name])
        
    
    @endif
    
    
@else
    
    @include('view_suda::media.upload_box_item',['media'=>null ,'hidden_input_name'=>'images[]'])
    
    
@endif

</div>