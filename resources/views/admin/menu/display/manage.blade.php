@if($items)

<ul class="menu-items" @if(isset($cur_item_id) && $cur_item_id) data-id="{{ $cur_item_id }}" @else data-id="0" @endif>
@foreach ($items as $k=>$item)
    @php
        
        $linkAttributes = null;
        $href = $item->link();

        $permission = '';
        $hasChildren = false;
        
        if(!$item->children->isEmpty())
        {
            foreach($item->children as $child)
            {
                $hasChildren = true;
            }
            
            if (!$hasChildren) {
                continue;
            }
            
            $linkAttributes = 'href="#' . $item->id;
        }
        else
        {
            $linkAttributes =  'href="' . $href .'"';
        }
        
        
    @endphp
    
    
    
        <li class="menu-item" data-id="{{ $item->id }}">
            
            
            
            <div class="menu-item-content border rounded py-2 px-2 mb-2 d-flex">
                <span class="title badge bg-light text-dark fs-6">
                    
                    @if(isset($item->blade_icon))
                    @svg($item->blade_icon,['width'=>'16px'])
                    @elseif(isset($item->icon_class))
                    <i class=" {{ $item->icon_class }} "></i>
                    @endif

                    &nbsp;{{ __($item->title) }}
                </span>
                <span class="url text-muted ms-2 fs-6 badge bg-light fw-light">
                    {{ $item->link() }}
                </span>
                @if(!$item->enable)
                <span class="url text-danger ms-2 fs-6 badge bg-light fw-light">
                    已隐藏
                </span>
                @endif
                
                <div class="btn-group ms-auto" role="group">

                    <button href="{{ admin_url('menu/item/edit/'.$item->id) }}" id="edit-item" type="button" class="pop-modal btn btn-primary btn-xs"><i class="ion-create"></i>&nbsp;编辑</button>
                    <button id="delete-item" type="button" class="pop-modal-delete btn btn-danger btn-xs" href="{{ admin_url('menu/item/delete/'.$item->menu_id.'/'.$item->id) }}" data_id="{{ $item->id }}"><i class="ion-trash"></i></button>

                </div>
                
            </div>
            
            @if($hasChildren)

                @include('view_suda::admin.menu.display.manage', ['items' => $item->children,'cur_item_id'=>$item->id,'menu'=>$menu])

            @endif
            
        </li>
        
        

@endforeach
</ul>
@endif

