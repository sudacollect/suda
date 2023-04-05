@if($hasChildren)
    <div class="panel-body collapse @if(isset($current_item) && $current_item) show @endif sidebar-menu sidebar-menu-item sidebar-menu-{{ $item->slug }}" id="sidebar-menu-{{ $item->slug }}" aria-expanded="true">
        @if($children_level==0)
        <div class="menu-group-title">
            {{-- <i class="@if(isset($item->icon_class)) {{ $item->icon_class }} @else icon ion-settings @endif"></i> --}}
            {{ __($item->title) }}
        </div>
        @endif
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
                    @if($children_level>0 || $super_permission || \Gtd\Suda\Auth\OperateCan::operation($soperate) || array_key_exists($menu->slug,$root_group))
                    <li class="mb-2 @if($current_item && in_array($menu->slug,$current_menu)) active @endif">
                        
                        @if(property_exists($menu,'children') && count($menu->children)>0)
                        
                        <a href="{{ menu_link($menu) }}" class="has_children">
                            {{ __($menu->title) }}
                        </a>

                        @php
                            $children_level++;
                        @endphp

                        @include('view_suda::admin.menu.display.sidebar_pro_children',['hasChildren'=>true,'item'=>$menu,'root_group'=>isset($root_group[$menu->slug])?$root_group[$menu->slug]:[],'children_level'=>$children_level])
                        
                        @else
                        <a href="{{ menu_link($menu) }}">
                            {{ __($menu->title) }}
                        </a>
                        @endif

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
                    <li class="mb-2 @if($current_item && in_array($menu->slug,$current_menu)) active @endif">
                        
                        @if(property_exists($menu,'children') && count($menu->children)>0)
                        
                        <a href="{{ menu_link($menu) }}" class="has_children">
                            {{ __($menu->title) }}
                        </a>

                        @php
                            $children_level++;
                        @endphp
                        @include('view_suda::admin.menu.display.sidebar_pro_children',['hasChildren'=>true,'item'=>$menu,'root_group'=>isset($root_group[$menu->slug])?$root_group[$menu->slug]:[],'children_level'=>$children_level])

                        @else
                        <a href="{{ menu_link($menu) }}">
                            {{ __($menu->title) }}
                        </a>
                        @endif
                    </li>
                    @endif
                    

                @endif
                
                

                <!-- end of menu -->
                @endif
                
                @endforeach
                
        </ul>
    </div>
@endif