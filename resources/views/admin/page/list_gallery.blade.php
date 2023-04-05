<style>
    .waterfall-item{
        flex-shrink: 0;
        
        padding-right: calc(var(--bs-gutter-x)*0.5);
        padding-left: calc(var(--bs-gutter-x)*0.5);
        margin-top: var(--bs-gutter-y);
    }
</style>


    
    @if($data_list->count() > 0)
    <div class="data-list row" id="waterfall-container">    
    
        @foreach ($data_list as $k=>$item)

        <div class="waterfall-item col-sm-3">
            
            <div class="card mb-3">
                @if($item->heroimage)
                <img class="card-img-top" src="{{ suda_image(isset($item->heroimage->media)?$item->heroimage->media:'',['size'=>'medium','imageClass'=>'image_icon',"url"=>true],false) }}" alt="Card image cap">
                @else
                <img class="card-img-top" src="{{ suda_image(null,['size'=>'medium','imageClass'=>'image_icon',"url"=>true],false) }}" alt="Card image cap" >
                @endif
        
                <div class="card-body">
                    <h5 class="card-title">
                        @if($item->stick_top==1)
                            <span class="badge bg-warning">置顶</span>
                            
                            @endif
                        @if(!empty($item->redirect_url))
                        <a target="_blank" href="{{ url($item->redirect_url) }}">{{ $item->title }}&nbsp;<i class="icon ion-open-outline" style="color:#999;"></i></a>
                        
                        @else
                        <a target="_blank" href="{{ $item->real_url }}">{{ $item->title }}</a>
                        @endif
                    </h5>
                    
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
                            
                            @endif
                        </small>
                        <small class="help-block">
                            <small class="help-block float-right">
                                @if($item->disable==0)
                                {{ __('suda_lang::press.published') }}
                                @else
                                {{ __('suda_lang::press.unpublished') }}
                                @endif
                            </small>{{ $item->updated_at->format('Y-m-d') }}
                        </small>
                    </p>
                    <div class="card-text">
                        @if($soperate->id==$item->operate_id || \Gtd\Suda\Auth\OperateCan::operation($soperate))
                        <button href="{{ admin_url('page/delete/'.$item->id) }}" class="pop-modal-delete btn btn-light btn-sm"  data_id="{{ $item->id }}" data_title="确认删除?" title="删除" data-toggle="tooltip" data-placement="top"><i class="ion-trash"></i></button>
                        @endif
                        @if($soperate->id==$item->operate_id || \Gtd\Suda\Auth\OperateCan::operation($soperate))
                        <a href="{{ admin_url('page/update/'.$item->id) }}" class="btn btn-light btn-sm tips" title="{{ __('suda_lang::press.edit') }}" data-toggle="tooltip" data-placement="top"><i class="ion-create"></i>&nbsp;{{ __('suda_lang::press.edit') }}</a>
                        @endif
                        
                        <a href="{{ admin_url('page/preview/'.$item->id) }}" data-id="{{ $item->id }}" class="btn-preview btn btn-light btn-sm" title="{{ __('suda_lang::press.preview') }}" ><i class="ion-eye"></i>&nbsp;{{ __('suda_lang::press.preview') }}</a>
                        
                        
                    </div>
                </div>
                
            </div>

        </div>

        
        @endforeach
    </div>
    @else
    
    
        
    
            <div class="card mb-3">
                <div class="card-body">
                    <x-suda::empty-block :card=false />
                </div>
            </div>
    
        
    
        
    @endif
    
    



@if($data_list->count() > 0)
    <div class="col-12">
        {{ $data_list->appends($filter)->links() }}
    </div>
@endif
    
    
    
    
    
    
    