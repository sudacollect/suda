@foreach ($data_list as $item)

<div class="waterfall-item col-sm-3">

    <div class="card mb-3">
        @if($item->heroimage)
        <img class="card-img-top" src="{{ suda_image(isset($item->heroimage->media)?$item->heroimage->media:'',['size'=>'medium','imageClass'=>'image_icon',"url"=>true],false) }}" alt="Card image cap">
        @else
        <img class="card-img-top" src="{{ suda_image(null,['size'=>'medium','imageClass'=>'image_icon',"url"=>true],false) }}" alt="Card image cap" >
        @endif

        <div class="card-body">
        <div class="card-title">
            
            
            <h5>
                @if($item->stick_top==1)
                    <span class="badge bg-warning me-2" style="float:left;">置顶</span>
                @endif
                {{ $item->title }}&nbsp;
                @if(!empty($item->redirect_url))<i class="icon ion-open-outline" style="color:#999;"></i>@endif
            </h5>
            
        </div>
        
        <p class="card-text">
            <small class="text-muted">
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
                
                @else
                未设定分类
                @endif
            </small>
        </p>
        <div class="card-text text-left">
            @if($soperate->id==$item->operate_id || \Gtd\Suda\Auth\OperateCan::operation($soperate))
            <button href="{{ admin_url('article/delete/'.$item->id) }}" class="pop-modal-delete btn btn-light btn-xs"  data_id="{{ $item->id }}" title="删除" data-toggle="tooltip" data-placement="top"><i class="ion-trash"></i></button>
            @endif
            @if($soperate->id==$item->operate_id || \Gtd\Suda\Auth\OperateCan::operation($soperate))
            <a href="{{ admin_url('article/update/'.$item->id) }}" class="btn btn-light btn-xs tips" title="{{ __('suda_lang::press.edit') }}" data-toggle="tooltip" data-placement="top"><i class="ion-create"></i>&nbsp;{{ __('suda_lang::press.edit') }}</a>
            @endif
            <a href="{{ admin_url('article/preview/'.$item->id) }}" data-id="{{ $item->id }}" target="_blank" class="btn-preview btn btn-light btn-xs" title="{{ __('suda_lang::press.preview') }}" data-toggle="tooltip" data-placement="top"><i class="ion-eye"></i>&nbsp;{{ __('suda_lang::press.preview') }}</a>
            
            <small class="help-block float-right">
                @if($item->disable==0)
                {{ __('suda_lang::press.published') }}
                @endif
                {{ $item->updated_at->format('Y-m-d') }}
            </small>
            

        </div>
        </div>
        
    </div>
    
</div>

@endforeach


