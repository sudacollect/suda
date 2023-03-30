@extends('view_path::layouts.default')



@section('content')
<div class="container-fluid">
    <div class="row suda-row">
        
        @include('view_path::media.header')
        
        
        <div class="col-sm-12 media-upload mb-3" style="display:none">
            <div class="card">
                
                <div class="card-body">
                    
                    @uploadBox('media','6','6')
                    
                </div>
            </div>
            
        </div>
        
        @if($medias->count()>0)
        
            @if($medias)
                @foreach ($medias as $item)
                <div class="col-sm-2  suda_page_body mb-4">
                    <div class="card">
                
                        <div class="card-body" style="padding:0px;height:120px;line-height:120px;position:relative;overflow:hidden;">
                            <div class="media-box" style="position:absolute;left:0;top:0;width:100%;height:100%;text-align:center;overflow:hidden;border-radius:4px;background:url({{ suda_image($item,['size'=>'medium','imageClass'=>'image_icon',"url"=>true],false) }}) center center no-repeat;background-size:cover;">
                                
                                @if($soperate->id==$item->operate_id || $soperate->user_role>7)
                                <a href="{{ admin_url('media/update/'.$item->id) }}" class="pop-modal" style="display:block;width:100%;height:100%;font-size:0;" title="{{ __('suda_lang::press.edit') }}">
                                    <i class="ion-create"></i>&nbsp;{{ __('suda_lang::press.edit') }}
                                </a>
                                @endif
                                
                            </div>
                        </div>
                    </div>
            
                </div>
                @endforeach
            @endif
        @else
        
        <x-suda::empty-block empty="没有媒体文件" />
        
        @endif
    </div>
</div>
@endsection


@push('scripts')

<script type="text/javascript">

    $(document).ready(function(){
        $.mediabox({
            box_url: "{{ admin_url('medias/load-modal/') }}",
            modal_url: "{{ admin_url('medias/modal/') }}",
            upload_url: "{{ admin_url('medias/upload/image/') }}",
            remove_url: "{{ admin_url('medias/remove/image/') }}"
        });
        
        $('#show-media-upload').on('click',function(){
            
            $('.media-upload').slideToggle();
            
        });
    });

</script>
@endpush
