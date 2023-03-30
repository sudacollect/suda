@if($data_list->count()>0)
<div class="table-responsive">
    <table class="table table-hover">
        
        <thead class="bg-light">
            <tr>
                <th width="5%">#<i class="stitle"></i></th>
                <th width="60px">主图</th>
                <th width="20%">标题</th>
                <th width="12%">分类</th>
                <th width="8%">发布</th>
                <th width="10%">
                    排序 <a @if(isset($by_sort) && $by_sort=='sort')  href="{{ admin_url('article/list') }}" class="text-success" @else href="{{ admin_url('articles/list/sorted') }}" class="text-muted" @endif><i class="ion-swap-vertical"></i></a>
                </th>
                <th width="10%">发布者</th>
                <th width="15%">更新时间</th>
                <th>操作</th>
            </tr>
            </thead>

        <tbody>
        @if($data_list)
        @foreach ($data_list as $item)
        <tr>
            <td width="5%">{{ $item->id }}</td>
            <td width="60px">
                @if($item->heroimage)
                <img src="{{ suda_image(isset($item->heroimage->media)?$item->heroimage->media:'',['size'=>'medium','imageClass'=>'image_icon',"url"=>true],false) }}" class="image_icon" data-toggle="popover" data-image="true" >
                @else
                <img src="{{ suda_image(null,['size'=>'medium','imageClass'=>'image_icon',"url"=>true],false) }}" class="image_icon" data-toggle="popover" data-image="true" >
                @endif
            </td>
            <td width="20%">
                @if($item->stick_top==1)
                <span class="badge bg-warning">置顶</span>
                
                @endif
                @if(!empty($item->redirect_url))
                <a target="_blank" href="{{ url($item->redirect_url) }}">
                    {{ $item->title }}
                </a>
                
                @else
                <a target="_blank" href="{{ $item->real_url }}">{{ $item->title }}</a>
                @endif
            </td>
            <td width="12%">
                
                @if($item->categories)
                
                @php
                $comma = '';
                @endphp
                
                @foreach ($item->categories as $category)
                
                @php
                    if($category->taxonomy){
                        echo $comma.$category->taxonomy->term->name;
                    }
                    
                    $comma=",";
                @endphp
                
                
                @endforeach
                
                @endif
                
            </td>
            <td width="8%">
                @if($item->disable==1)
                否
                @else
                是
                @endif
            </td>
            <td width="10%">
            <div class="inline-edit-block">
                <span id="{{ $item->id }}" class="inedit" edit-id="{{ $item->id }}" edit-value="{{ $item->sort }}">{{ $item->sort }}</span>
                <a href="{{ admin_url('article/editsort/'.$item->id) }}" class="btn btn-sm inline-edit" title="设置排序"><i class="ion-create" style="color:#999;"></i></a>
            </div>
            </td>
            <td width="10%">
                {{ $item->operate->username }}
            </td>
            
            <td width="15%">
                {{ $item->updated_at }}
            </td>
            
            <td>
                @if($item->trashed())
                    @if($soperate->id==$item->operate_id || $soperate->user_role>7)
                    
                    <button href="{{ admin_url('article/restore/'.$item->id) }}" class="btn-restore btn btn-light btn-xs" data-id="{{ $item->id }}" title="恢复" >恢复</button>
                    <button href="{{ admin_url('article/forcedelete/'.$item->id) }}" class="pop-modal-delete btn btn-light btn-xs"  data_id="{{ $item->id }}" title="删除" data-toggle="tooltip" data-placement="top">删除</button>
                    @endif
                @else
                    @if($soperate->id==$item->operate_id || $soperate->user_role>7)
                    <a href="{{ admin_url('article/update/'.$item->id) }}" class="btn btn-light btn-xs tips" title="{{ __('suda_lang::press.edit') }}" data-toggle="tooltip" data-placement="top"><i class="ion-create"></i>&nbsp;{{ __('suda_lang::press.edit') }}</a>
                    @endif
                    <a href="{{ admin_url('article/preview/'.$item->id) }}" data-id="{{ $item->id }}" target="_blank" class="btn-preview btn btn-light btn-xs" title="{{ __('suda_lang::press.preview') }}" data-toggle="tooltip" data-placement="top"><i class="ion-eye"></i>&nbsp;{{ __('suda_lang::press.preview') }}</a>
                    @if($soperate->id==$item->operate_id || $soperate->user_role>7)
                    <button href="{{ admin_url('article/delete/'.$item->id) }}" class="pop-modal-delete btn btn-light btn-xs"  data_id="{{ $item->id }}" title="删除" data-toggle="tooltip" data-placement="top"><i class="ion-trash"></i></button>
                    @endif
                @endif
            
            </td>
        </tr>
        @endforeach
        @endif
        </tbody>
    </table>

    @if($data_list)
    {{ $data_list->appends($filter)->links() }}
    @endif
    
</div>

@else

<x-suda::empty-block :card=false />

@endif