@extends('view_path::layouts.default')



@section('content')
<div class="container-fluid">
    <div class="row suda-row">
        
        @include('view_path::media.header')
        
        
        <div class="col-sm-12 media-upload mb-3" style="display:none">
            <div class="card">
                
                <div class="card-body">
                    
                    @uploadBox('media','6','6',null,1)
                    
                </div>
            </div>
            
        </div>
        
        
        <div class="col-12 suda_page_body">

            @include('view_path::media.header_tab')

            <div class="card card-with-tab">
                    <div class="card-header bg-white">

                        @if(isset($tag))
                        <span class="btn btn-sm btn-primary me-3 float-left list-tag-filter"><i class="ion-close"></i>&nbsp;{{ __('suda_lang::press.tags.tag') }}：{{ $tag->term->name }}</span>
                        @endif

                        <div class="dropdown d-inline-flex">
                            <a class="btn btn-light btn-sm dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                {{ __('suda_lang::press.medias.batch_manage') }}
                            </a>
                          
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                <li><a id="batch_retag" class="dropdown-item" href="{{ admin_url('media/batchtag') }}">{{ __('suda_lang::press.tags.tag') }}</a></li>
                                {{-- <li><a id="batch_rebuild" class="dropdown-item" href="#">批量缩略图</a></li> --}}
                                <li><a  class="batch-action dropdown-item" href="{{ admin_url('media/showbatch') }}">{{ __('suda_lang::press.medias.batch_show') }}</a></li>
                                <li><a  class="batch-action dropdown-item" href="{{ admin_url('media/hiddenbatch') }}">{{ __('suda_lang::press.medias.batch_hide') }}</a></li>
                                <li><a  class="batch-action dropdown-item" href="{{ admin_url('media/deletebatch') }}">{{ __('suda_lang::press.medias.batch_delete') }}</a></li>
                            </ul>
                        </div>

                        
                        
                    </div>
                <div class="card-body">
                    @if($medias->count()>0)
                        <!-- list start -->
                    
                        <div class="table-responsive data-list">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                <th width="20px"><input type="checkbox" id="select_all" value="1"></th>
                                {{-- <th width="5%">#<i class="stitle"></i></th> --}}
                                <th width="60px">{{ __('suda_lang::press.medias.image') }}</th>
                                <th width="20%">{{ __('suda_lang::press.medias.path') }}</th>
                                <th width="20%">{{ __('suda_lang::press.tags.tag') }}</th>
                                <th width="10%">{{ __('suda_lang::press.medias.size') }}</th>
                                <!-- <th width="10%">用户类型</th> -->
                                <th width="15%">{{ __('suda_lang::press.created_at') }}</th>
                                <th>
                                    {{ __('suda_lang::press.operation') }}
                                </th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($medias)
                                @foreach ($medias as $item)
                                <tr>
                                <td width="20px"><input type="checkbox" name="select[]" value="{{ $item->id }}"></td>
                                {{-- <td width="5%">{{ $item->id }}</td> --}}
                                <td width="60px">
                                    <img src="{{ suda_image($item,['size'=>'medium','imageClass'=>'image_icon',"url"=>true],false) }}" class="image_icon" data-toggle="popover" data-image="true" >
                                </td>
                                <td width="20%">
                                    {{ dirname($item->path) }}<br>
                                    {{ $item->name }}
                                    @if($item->hidden==1)
                                    <small class="badge bg-light text-dark">HIDDEN</small>
                                    @endif
                                </td>
                                <td width="20%">
                                    @if($item->tags)
                                    @foreach($item->tags as $tag)

                                    @if($tag && isset($tag->taxonomy) && isset($tag->taxonomy->term))
                                    <span class="badge bg-light text-dark rounded-pill">{{ $tag->taxonomy->term->name }}</span>
                                    @endif

                                    @endforeach
                                    @endif
                                </td>
                                <td width="10%">
                                    
                                    {{ ceil($item->size/1024) }}KB
                                    
                                </td>
                                <!-- <td width="10%">
                                    {{ $item->user_type }}
                                </td> -->
                                
                                <td width="15%">
                                    {{ $item->updated_at }}
                                </td>
                                
                                <td>
                                    @if($soperate->id==$item->operate_id || \Gtd\Suda\Auth\OperateCan::superadmin($soperate))
                                    <a href="{{ admin_url('media/update/'.$item->id) }}" class="pop-modal btn btn-light btn-xs tips" title="{{ __('suda_lang::press.edit') }}" data-toggle="tooltip" data-placement="top"><i class="ion-create"></i>&nbsp;{{ __('suda_lang::press.edit') }}</a>
                                    @if(strpos($item->type,'FILE')===false)
                                    <a href="{{ admin_url('media/rebuild/'.$item->id) }}" class="pop-modal btn btn-light btn-xs tips" title="{{ __('suda_lang::press.medias.reset_thumb') }}" data-toggle="tooltip" data-placement="top"><i class="ion-crop"></i>&nbsp;{{ __('suda_lang::press.medias.reset_thumb') }}</a>
                                    @endif
                                    @endif
                                    
                                    
                                    @if($soperate->superadmin==1)
                                    <button href="{{ admin_url('media/delete/'.$item->id) }}" class="pop-modal-delete btn btn-light btn-xs" data_id="{{ $item->id }}" title="{{ __('suda_lang::press.btn.delete') }}" data-toggle="tooltip" data-placement="top"><i class="ion-trash"></i></button>
                                    @endif
                                
                                
                                </td>
                                </tr>
                                @endforeach
                                @endif
                            </tbody>
                        </table>
                    
                        @if($medias)
                        {{ $medias->links() }}
                        @endif
                    
                        @if(isset($filter_str))
                    
                        <input type="hidden" id="filter_str" value="{{ $filter_str }}">
                    
                        @endif
                    
                        </div>
                        
                        
                        <!-- list end -->

                    @else
                    
                    <x-suda::empty-block empty="没有媒体文件" :card=false />
                    
                    @endif
                </div>
            </div>
        </div>
        
        
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

            $('#select_all').on('change',function(e){
                if($(this).prop('checked')){
                    
                    $('.data-list').find('input[name="select[]"]').prop('checked',true);

                }else{
                    $('.data-list').find('input[name="select[]"]').prop('checked',false);
                }
            });

            $('#batch_retag').on('click',function(e){
                e.preventDefault();
                
                if($('.data-list').find('input[name="select[]"]:checked').length<1)
                {
                    suda.modal('请选择图片');
                    return;
                }

                var batch_medias = [];
                $('.data-list').find('input[name="select[]"]:checked').each(function(index,e){
                    batch_medias[index] = $(e).val();
                });
                
                var href_url = $(this).attr('href');
                var csrfToken = suda.data('csrfToken');
                // data = new FormData();
                // data.append("_token", suda.data('csrfToken'));
                // data.append("medias", batch_medias);

                var elem = this;

                $.ajax({
                    url:href_url,
                    // data:data,
                    type:"GET",
                    cache: false,
                    contentType: false,
                    processData: false,
                    success:function(data){
                        if(data){
                            if(data.hasOwnProperty('response_type')){
                                    suda.modal(data.response_msg);
                                    return false;
                            }
                            $(elem).popModal(data,csrfToken);
                            $('.modal-layout').find('form').append('<input type="hidden" name="formids" value="'+batch_medias+'">');
                        }
                    },
                    error: function(xhr)
                    {
                        var res = xhr.responseJSON;
                        if(res.response_msg){
                            suda.modal(res.response_msg);
                        }else{
                            suda.modal('请求出错，请重试');
                        }
                    }
                    
                });
                

            });

            $('#batch_rebuild').on('click',function(e){
                e.preventDefault();
                suda.modal('需设置队列后方可启用批量更新');
            });

            $('.batch-action').on('click',function(e){
                e.preventDefault();
                if($('.data-list').find('input[name="select[]"]:checked').length<1)
                {
                    suda.modal('请选择需要批量操作的图片');
                    return;
                }

                var delete_medias = [];
                $('.data-list').find('input[name="select[]"]:checked').each(function(index,e){
                    delete_medias[index] = $(e).val();
                });
                
                var href_url = $(this).attr('href');
                data = new FormData();
                data.append("_token", suda.data('csrfToken'));
                data.append("medias", delete_medias);

                var delete_statu = window.confirm("{{ __('suda_lang::press.medias.confirm_batch') }}?");
                if(delete_statu){
                    $.ajax({
                        url:href_url,
                        data:data,
                        type:"POST",
                        cache: false,
                        contentType: false,
                        processData: false,
                        success:function(res){
                            suda.modal(res.response_msg,res.response_type);
                            window.location.reload();
                        },
                        error: function(xhr)
                        {
                            var res = xhr.responseJSON;
                            if(res.response_msg){
                                suda.modal(res.response_msg);
                            }else{
                                suda.modal('请求出错，请重试');
                            }
                        }
                        
                    });
                }
                

            });

            $('.list-tag-filter').on('click',function(e){
                e.preventDefault();
                window.location.href = "{{ admin_url('media') }}";
            });
        });
    
    </script>
@endpush
