@if($data_list->count()>0)
<div class="table-responsive">
    <table class="table table-hover">
        
        <thead class="bg-light">
            <tr>
                <th width="60px">{{ __('suda_lang::press.articles.kv_image') }}</th>
                <th width="20%">{{ __('suda_lang::press.articles.title') }}</th>
                <th width="12%">{{ __('suda_lang::press.category') }}</th>
                <th width="8%">{{ __('suda_lang::press.publish') }}</th>
                <th width="10%">
                    {{ __('suda_lang::press.articles.sort') }} <a @if(isset($by_sort) && $by_sort=='sort')  href="{{ admin_url('articles') }}" class="text-success" @else href="{{ admin_url('articles/list/sorted') }}" class="text-muted" @endif><i class="ion-swap-vertical"></i></a>
                </th>
                <th width="10%">{{ __('suda_lang::press.publish') }}</th>
                <th width="15%">{{ __('suda_lang::press.updated_at') }}</th>
                <th>{{ __('suda_lang::press.operation') }}</th>
            </tr>
            </thead>

        <tbody>
        @if($data_list)
        @foreach ($data_list as $item)
        <tr>
            <td width="60px">
                @if($item->heroimage)
                <img src="{{ suda_image(isset($item->heroimage->media)?$item->heroimage->media:'',['size'=>'medium','imageClass'=>'image_icon',"url"=>true],false) }}" class="image_icon" data-toggle="popover" data-image="true" >
                @else
                <img src="{{ suda_image(null,['size'=>'medium','imageClass'=>'image_icon',"url"=>true],false) }}" class="image_icon" data-toggle="popover" data-image="true" >
                @endif
            </td>
            <td width="20%">
                @if($item->stick_top==1)
                <span class="badge bg-warning">{{ __('suda_lang::press.sticked') }}</span>
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
                {{ __('suda_lang::press.no') }}
                @else
                {{ __('suda_lang::press.yes') }}
                @endif
            </td>
            <td width="10%">
            <div class="inline-edit-block">
                <span id="{{ $item->id }}" class="inedit" edit-id="{{ $item->id }}" edit-value="{{ $item->sort }}">{{ $item->sort }}</span>
                <a href="{{ admin_url('article/editsort/'.$item->id) }}" class="btn btn-sm inline-edit" title="{{ __('suda_lang::press.set_sort') }}"><i class="ion-create" style="color:#999;"></i></a>
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
                    @if($soperate->id==$item->operate_id || \Gtd\Suda\Auth\OperateCan::operation($soperate))
                    
                    <button href="{{ admin_url('article/restore/'.$item->id) }}" class="btn-restore btn btn-light btn-xs" data-id="{{ $item->id }}" title="恢复" >恢复</button>
                    <button href="{{ admin_url('article/forcedelete/'.$item->id) }}" class="pop-modal-delete btn btn-light btn-xs"  data_id="{{ $item->id }}" title="删除" data-toggle="tooltip" data-placement="top">删除</button>
                    @endif
                @else
                    @if($soperate->id==$item->operate_id || \Gtd\Suda\Auth\OperateCan::operation($soperate))
                    <a href="{{ admin_url('article/update/'.$item->id) }}" class="btn btn-light btn-xs tips" title="{{ __('suda_lang::press.edit') }}" data-toggle="tooltip" data-placement="top"><i class="ion-create"></i>&nbsp;{{ __('suda_lang::press.edit') }}</a>
                    @endif
                    <a href="{{ admin_url('article/preview/'.$item->id) }}" data-id="{{ $item->id }}" target="_blank" class="btn-preview btn btn-light btn-xs" title="{{ __('suda_lang::press.preview') }}" data-toggle="tooltip" data-placement="top"><i class="ion-eye"></i>&nbsp;{{ __('suda_lang::press.preview') }}</a>
                    @if($soperate->id==$item->operate_id || \Gtd\Suda\Auth\OperateCan::operation($soperate))
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