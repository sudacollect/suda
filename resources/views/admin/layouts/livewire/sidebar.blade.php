@php
    
    $sidebar_class = 'press-sidebar ';
    $sidebar_view = 'sidebar';

    if(config('sudaconf.sidemenu_style','')=='pro')
    {
        $sidebar_class .= 'press-sidebar-pro ';
        $sidebar_view = 'sidebar_pro';
    }else {
        if($sidemenu_style=='flat')
        {
            $sidebar_class .= 'in';
        }

        if($sidemenu_style=='icon')
        {
            $sidebar_class .= ' press-sidebar-icon ';
        }
    }

    

@endphp

<div class="col-12 {{ $sidebar_class }}" x-ref="sidebar" x-init="$store.menuStyle.name='{{ $sidemenu_style }}'">

    <div class="sidebar-head">
        <a class="sidebar-brand @if($sidemenu_style=='icon' || config('sudaconf.sidemenu_style','')=='pro') only @endif" 
            @if(isset($sdcore->settings->dashboard->dashboard_logo) && !empty($sdcore->settings->dashboard->dashboard_logo))
             style="background:url({{ $sdcore->settings->dashboard->dashboard_logo }}) no-repeat center center;background-size:contain;"
            @endif
             href="{{ admin_url('/') }}">
            {{ config('app.name', 'Suda') }}
        </a>
    </div>

    <div class="sidebar-content" x-data="{
        show_current_menu: 0,
        toggle(iteration) {
            if(this.show_current_menu===iteration)
            {
                this.show_current_menu = 0
            }else{
                this.show_current_menu = iteration
            }
            
        },
        isShow(iteration) {
            if(this.$store.menuStyle.name=='icon')
            {
                return true
            }
            if(this.show_current_menu == iteration)
            {
                return true
            }
            return false
        }
    }">
        
        @include('view_suda::admin.livewire.menu.'.$sidebar_view,$sdcore->sidemenus)
        
    </div>
    
    
    
</div>

