@php
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

    
    
    
@endphp
@foreach ($items as $item)
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

        $permission = false;
    
        //设置的应用权限名称和菜单的slug是关联的
        if (isset($options->in_extension) && $options->in_extension) {

            $item->extension_slug = $options->extension_slug;
            
            if($item->slug=='dashboard' || $super_permission || $soperate->user_role >= 6){
                $permission = true;
            }

            if($soperate->user_role==2 && array_key_exists($item->slug,$soperate->role->permissions['exts'][$options->extension_slug]))
            {
                $permission = true;
            }

        } else {

            if($item->slug=='dashboard' || $super_permission ||  array_key_exists($item->slug,$soperate->role->permissions['sys'])){
                $permission = true;
            }

            if( $super_permission || ($item->slug=='setting' && array_key_exists('role',$soperate->role->permissions['sys'])) ){
                $permission = true;
            }

            if(!$super_permission && !$permission && property_exists($item,'extension_slug')){

                if(array_key_exists($item->extension_slug,$soperate->role->permissions['exts'])){
                    if(array_key_exists($item->slug,$soperate->role->permissions['exts'][$item->extension_slug])){
                        $permission = true;
                    }
                }

            }
            
        }
        
        $hasChildren = false;
        
        if(count($item->children)>0)
        {
            foreach($item->children as $child)
            {
                $hasChildren = true;
            }
            
            if (!$hasChildren) {
                continue;
            }
            
            
        }
        
        
        
        
        $current_item = false;
        $current_link = false;
        
        if(array_key_exists($item->slug,(array)$current_menu) && $options->sidemenu_style=='flat'){
            $current_item = true;
        }
        
        
    @endphp
    
    
    @if($permission)
    <li class="nav-item @if($hasChildren) dropdown @endif @if(isset($current_item) && $current_item) active @endif">
        
        @if($hasChildren)
        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" role="button" aria-expanded="false">
            <i class="@if(isset($item->icon_class)) {{ $item->icon_class }} @else ion-grid @endif"></i>
            {{ __($item->title) }}
        </a>
        
        <ul class="dropdown-menu" role="menu">
             @foreach($item->children as $mk=>$menu)
                @php
                $menu = (object)$menu;
                if(property_exists($menu,'hidden')){
                        continue;
                }
                @endphp


                @if (isset($options->in_extension) && $options->in_extension)

                @php
                $menu->extension_slug = $options->extension_slug;
                @endphp
                
                    @if($super_permission || $soperate->user_role >= 6 || array_key_exists($menu->slug,$soperate->role->permissions['exts'][$options->extension_slug][$item->slug]))
                    <li>
                        <a href="{{ menu_link($menu) }}" class="dropdown-item @if($current_item && in_array($menu->slug,$current_menu)) active @endif">
                            {{ __($menu->title) }}
                        </a>
                    </li>
                    @endif

                @else

                    @php
                    $show_menu = true;

                    //判断权限管理
                    if($item->slug=='setting' && $menu->slug!='setting_system' && isset($soperate->role->permissions['sys']['role'])){

                        if(array_key_exists($menu->slug,$soperate->role->permissions['sys']['role'])){
                            $show_menu = true;
                        }else{
                            $show_menu = false;
                        }

                    }
                    if(!$super_permission && !array_key_exists('setting',$soperate->role->permissions['sys']) && $menu->slug=='setting_system'){
                        $show_menu = false;
                    }

                    if(!$super_permission && property_exists($menu,'extension_slug')){

                        if(array_key_exists('#suda',$soperate->role->permissions['exts'][$menu->extension_slug])){

                            if($super_permission || array_key_exists($menu->slug,$soperate->role->permissions['exts'][$menu->extension_slug]['#suda'][$item_key])){
                                $show_menu = true;
                            }else{
                                $show_menu = false;
                            }

                        }else{
                            $show_menu = false;
                        }

                    }
                    @endphp

                    @if($show_menu)
                    <li>
                        <a href="{{ menu_link($menu) }}" class=" dropdown-item @if($current_item && in_array($menu->slug,$current_menu)) active @endif">
                            {{ __($menu->title) }}
                        </a>
                    </li>
                    @endif


                @endif

             
             @endforeach
         </ul>
         
         @else
         
         <a href="{{ menu_link($item) }}" class="nav-link @if(isset($current_item) && $current_item) active @endif">
             <i class="@if(isset($item->icon_class)) {{ $item->icon_class }} @else ion-grid @endif"></i>
             {{ __($item->title) }}
         </a>
         
         @endif
            
        
    </li>
    @endif
    
    
@endforeach


@if(!property_exists($options,'in_extension'))

@if(count($custom_navi)>0)
                            
    @foreach($custom_navi as $navi_key=>$navi)

    <li class="nav-item dropdown @if(isset($navi['slug']) && array_key_exists($navi['slug'],$current_menu)) active @endif">
        @if(array_key_exists('children',$navi))
        <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
            <i class="@if(isset($navi['icon'])) {{ $navi['icon'] }} @else fa fa-th-large @endif"></i>
            {{ $navi['name'] }}
        </a>

        <ul class="dropdown-menu" role="menu">
            @foreach($navi['children'] as $mk=>$menu)
            <li @if(isset($menu['slug']) && in_array($menu['slug'],$current_menu)) class="active" @endif>
                <a href="{{ admin_url($menu['url']) }}">
                    {{ $menu['name'] }}
                </a>
            </li>
            @endforeach
        </ul>
        @else
            <a  class="nav-link dropdown-toggle" href="{{ admin_url($navi['url']) }}" target="{{ $navi['target'] }}" title="{{ $navi['name'] }}">@if(isset($navi['icon'])) <i class="{{ $navi['icon'] }}"></i>&nbsp;@endif{{ $navi['name'] }}</a>
        @endif
    </li>

    @endforeach

@endif

@endif