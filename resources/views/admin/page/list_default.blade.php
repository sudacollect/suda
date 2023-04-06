<div class="card">
                
    <div class="card-body">
        
        <!-- list start -->
        
        @if($data_list && $data_list->count()>0)
        <div class="table-responsive data-list">
            <table class="table table-hover">
                <thead class="bg-light">
                    <tr>
                        <th width="5%">#<i class="stitle"></i></th>
                        <th width="60px">主图</th>
                        
                        <th width="20%">标题</th>
                        <th width="8%">发布</th>
                        <th width="10%">
                            排序&nbsp;<a @if(isset($by_sort) && $by_sort=='sort')  href="{{ admin_url('page/list') }}" class="text-success" @else href="{{ admin_url('page/list/by/sort') }}" class="text-muted" @endif><i class="ion-swap-vertical"></i></a>
                        </th>
                        <th width="15%">发布者</th>
                        <th width="15%">更新时间</th>
                        <th>操作</th>
                    </tr>
                </thead>
                <tbody>
                
                @foreach ($data_list as $item)
                <tr>
                    <td width="5%">{{ $item->id }}</td>
                    <td width="60px">
                        
                        <img src="{{ suda_image(isset($item->heroimage->media)?$item->heroimage->media:'',['size'=>'medium','imageClass'=>'image_icon',"url"=>true],false) }}" class="image_icon" data-toggle="popover" data-image="true" >
                        
                    </td>
                    
                    <td width="20%">

                        @if($item->stick_top==1)
                        <span class="label label-info">{{ __('suda_lang::press.sticked') }}</span>
                        
                        @endif
                        @if(!empty($item->redirect_url))
                        <a target="_blank" href="{{ url($item->redirect_url) }}">{{ $item->title }}&nbsp;<i class="icon ion-open-outline" style="color:#999;"></i></a>
                        
                        @else
                        <a target="_blank" href="{{ $item->real_url }}">{{ $item->title }}</a>
                        @endif
                    </td>
                    <td width="8%">
                        @if($item->disable==1)
                        {{ __('suda_lang::press.no') }}
                        @else
                        {{ __('suda_lang::press.yes') }}
                        @endif
                    </td>
                    <td width="10%">
                    <div class="inline-edit-block">
                        <span id="{{ $item->id }}" class="inedit" edit-id="{{ $item->id }}" edit-value="{{ $item->sort }}">{{ $item->sort }}</span>
                        <a href="{{ admin_url('page/editsort/'.$item->id) }}" class="btn btn-sm inline-edit" title="{{ __('suda_lang::press.set_sort') }}"><i class="ion-create-outline" style="color:#999;"></i></a>
                    </div>
                    </td>
                    <td width="15%">
                        {{ $item->operate->username }}
                    </td>
                    
                    <td width="15%">
                        {{ $item->updated_at }}
                    </td>
                    
                    <td>
                        @if($soperate->id==$item->operate_id || $soperate->can('page.update',$item))
                        <a href="{{ admin_url('page/update/'.$item->id) }}" class="btn btn-light btn-xs tips" title="{{ __('suda_lang::press.edit') }}" data-toggle="tooltip" data-placement="top"><i class="ion-create"></i>&nbsp;{{ __('suda_lang::press.edit') }}</a>
                        @endif
                        <a href="{{ $item->real_url }}" target="_blank" class="btn btn-light btn-xs" title="{{ __('suda_lang::press.preview') }}" data-toggle="tooltip" data-placement="top"><i class="ion-open"></i>&nbsp;{{ __('suda_lang::press.preview') }}</a>
                        @if($soperate->id==$item->operate_id || $soperate->can('page.delete',$item))
                        <button href="{{ admin_url('page/delete/'.$item->id) }}" class="pop-modal-delete btn btn-light btn-xs" data_id="{{ $item->id }}" title="删除" data-toggle="tooltip" data-placement="top"><i class="ion-trash"></i></button>
                        @endif
                    
                    
                    </td>
                </tr>
                @endforeach
                
                </tbody>
            </table>
            {{ $data_list->appends($filter)->links() }}
        </div>

        @else

        <x-suda::empty-block :card=false />
        
        @endif
        
        <!-- list end -->
        
    </div>
    
    
    
</div>