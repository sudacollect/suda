@if($hasChildren)
    <div x-show="isShow({{ $iteration }})" x-collapse x-ref="menuChildren" :class="$store.menuStyle.name=='icon'?'':'show'" class="panel-body collapse sidebar-menu sidebar-menu-item sidebar-menu-{{ $item->slug }}" id="sidebar-menu-{{ $item->slug }}" aria-expanded="true">
        
        <ul class="list-unstyled menu">
                
            @foreach($item->children as $mk=>$menu)
            @php
            $menu = (object)$menu;
            if(property_exists($menu,'hidden')){
                continue;
            }
            if(property_exists($menu,'enable') && !$menu->enable){
                continue;
            }
            @endphp
            
            @if(isset($menu->title))

            @if (isset($options->in_extension) && $options->in_extension)
                
            @php
            $menu->extension_slug = $options->extension_slug;
            @endphp
            
                @if($children_level>0 || $super_permission || $soperate->user_role >= 6 || array_key_exists($menu->slug,$root_group))
                <li @if($current_item && in_array($menu->slug,$current_menu)) class="active" @endif>
                    
                    <a href="{{ menu_link($menu) }}">
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
                    
                    $show_menu = true;

                }
                @endphp

                @if($show_menu)
                <li @if($current_item && in_array($menu->slug,$current_menu)) class="active" @endif>
                    
                    <a href="{{ menu_link($menu) }}">
                        {{ __($menu->title) }}
                    </a>
                    
                </li>
                @endif
                

            @endif
            
            

            <!-- end of menu -->
            @endif
            
            @endforeach
                
        </ul>

    </div>
@endif