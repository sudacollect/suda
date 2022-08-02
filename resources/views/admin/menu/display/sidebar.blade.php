@php
    $empty_sub_menu  = true;
    if(!$options->operate){
        exit();
    }
    $soperate = $options->operate;
    $super_permission = false;
    
    if($soperate->superadmin==1){
        $super_permission = true;
    }
    
    $current_menu = [];
    if(isset($options->current_menu)){
        $current_menu = $options->current_menu;
    }
    
    //按照 order 排序
    array_multisort(array_column((array)$items, 'order'), SORT_ASC, $items);
    
    if(isset($return_menus) && is_array($items)){
        
        $items = array_merge($return_menus,$items);
        
    }

    $first_menu = [];
    if(count($current_menu)>0)
    {
        $first_menu = isset($items[array_key_first($current_menu)])?$items[array_key_first($current_menu)]:[];
    }
    
@endphp


@foreach ($items as $item_key=>$item)
    @php
        
        if(isset($options->in_extension) && $options->in_extension){
            if(array_key_exists('extend',$item)){
                continue;
            }
        }
        
        $item = (object)$item;

        if(property_exists($item,'hidden')){
            continue;
        }
        if(property_exists($item,'enable') && !$item->enable){
            continue;
        }
        
        $permission = false;
        $root_group = [];
        
        //设置的应用权限名称和菜单的slug是关联的
        if(isset($item->slug)){
            
            if (isset($options->in_extension) && $options->in_extension) {

                $item->extension_slug = $options->extension_slug;
                
                if($item->slug=='dashboard' || $super_permission){
                    $permission = true;
                }else{

                    if(array_key_exists($options->extension_slug,$soperate->role->permissions['exts'])){

                        if(array_key_exists($item->slug,$soperate->role->permissions['exts'][$options->extension_slug])){
                            $permission = true;
                            $root_group = $soperate->role->permissions['exts'][$options->extension_slug][$item->slug];
                        }

                    }elseif($soperate->user_role >= 6)
                    {
                        $permission = true;
                    }

                }

            } else {
                
                if($item->slug=='dashboard' || $super_permission || array_key_exists($item->slug,$soperate->role->permissions['sys'])){
                    $permission = true;
                    if(!$super_permission && $item->slug!='dashboard')
                    {
                        $root_group = $soperate->role->permissions['sys'][$item->slug];
                    }
                }

                if( $super_permission || ($item->slug=='setting' && array_key_exists('role',$soperate->role->permissions['sys'])) ){
                    $permission = true;
                }
                
                if(!$super_permission && !$permission && property_exists($item,'extension_slug')){
                    
                    if(array_key_exists($item->extension_slug,$soperate->role->permissions['exts'])){
                        if(array_key_exists($item->slug,$soperate->role->permissions['exts'][$item->extension_slug])){
                            $permission = true;
                            $root_group = $soperate->role->permissions['exts'][$options->extension_slug][$item->slug];
                        }
                    }

                }
                
            }
            
            
        }else{
            
            $item->slug = $item_key;
            $permission = true;
        }
        
        
        $linkAttributes = null;
        $href = menu_link($item);
        
        $hasChildren = false;
        $children_level = 0;
        if(property_exists($item,'children') && count($item->children)>0)
        {
            $hasChildren = true;
            
            $linkAttributes = 'href=#' . $item->slug;
        }
        else
        {
            $linkAttributes =  'href="' . $href .'"';
        }
        
        //没有子菜单就显示自身
        // if (!$hasChildren) {
        //     continue;
        // }
        
        
        $current_item = false;
        $current_link = false;

        if(count($current_menu)>0 && array_key_exists($item->slug,$current_menu)){
            $current_item = true;
        }
        
        
        
        
    @endphp
    
    
    @if($permission)
    
    <div class="panel" id="panel-{{ $loop->iteration }}">
        <div class="panel-heading @if(!$hasChildren) only-link @endif collapse show  @if(!isset($current_item) || !$current_item) collapsed @endif" @if($hasChildren) data-bs-toggle="collapse" data-bs-target="#sidebar-menu-{{ $item->slug }}" @endif aria-controls=".sidebar-menu-{{ $item->slug }}" aria-expanded="true">
            
            <a title="{{ __($item->title) }}" {!! $linkAttributes !!} target="{{ $item->target }}" {{ (isset($item->color) && $item->color != '#000000' ? 'style="color:'.$item->color.'"' : '') }}>
                
                @if(isset($item->blade_icon))
                @svg($item->blade_icon,['width'=>'16px'])
                @else
                <i class="@if(isset($item->icon_class)) {{ $item->icon_class }} @else icon ion-settings @endif @if($sidemenu_style=='icon') sidebar-icon @endif" ></i>
                @endif
                
                <span>{{ __($item->title) }}</span>
                
            </a>

        </div>
        
        {{-- ['hasChildren'=>$hasChildren,'children_level'=>$children_level,'item'=>$item,'current_item'=>$current_item,'options'=>$options] --}}
        
        @include('view_suda::admin.menu.display.sidebar_children',['root_group'=>$root_group])

    </div>
    
    @endif


    
    
@endforeach
