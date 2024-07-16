@extends('view_path::layouts.default')



@section('content')
<div class="container">
    
        
        <div class="page-heading">
            <h1 class="page-title text-nowrap">
                <i class="ion-list"></i>
                {{ __('suda_lang::press.menu_item') }}
                @if($menu->id>1)
            
                <a href="{{ admin_url('menu/item/add/'.$menu->id) }}" class="pop-modal btn btn-primary btn-sm">
                    <i class="ion-add-circle" style="color:white;"></i>&nbsp;{{ __('suda_lang::press.add_menu_item') }}
                </a>
                
                @endif
                <span class="help-block">
                    {{ __('suda_lang::press.menu_tips') }} <code>menu('{{ $menu->name }}') </code>
                </span>
            </h1>

            
            
            
        </div>
        
        <div class="row">
            <div class="col-sm-8">
                <div class="card">
                    <div class="card-header bg-white">
                        <a href="{{ admin_url('menu/cache/update') }}" action_id="{{ $menu->id }}" action_title="Confirmed?" class="x-suda-pop-action btn btn-light btn-sm float-end ms-2" title="{{ __('suda_lang::press.menu_recover.cache') }}">
                            {{ __('suda_lang::press.menu_recover.cache') }}
                        </a>
                        {{ $menu->name }}
                        @if($menu->id==1)
                        <a href="{{ admin_url('menu/recovery') }}" class="pop-modal btn btn-light btn-sm float-end" title="{{ __('suda_lang::press.menu_recover.restore') }}">
                            {{ __('suda_lang::press.menu_recover.restore') }}
                        </a>
                        @endif
                    </div>
                    <div class="card-body">
                        
                        <div id="menu-manage-list" class="menu-list">
                            {!! menu($menu->name, 'manage',['no_cache'=>true]) !!}
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    
    
</div>
@endsection

@push('scripts')
<script>
    
    jQuery(function(){
        
        if($('.menu-items').length>0)
        {
            $('.menu-items').each(function(index,el){
                new Sortable.Sortable(el, {
                    group: 'nested',
                    sort: true,
                    dragoverBubble: false,
                    animation: 150,
                    fallbackOnBody: true,
                    swapThreshold: 0.65,
                    onEnd:function(evt) {
                        var itemEl = evt.item; // dragged HTMLElement
                        var itemParent = $(itemEl).parent('.menu-items');
                        var parent_id = $(itemParent).attr('data-id')

                        var slugs = new Array();
                        $(itemParent).children('.menu-item').each(function(index,e){
                            slugs.push($(e).attr('data-id'));
                        });

                        $.post('{{ route('sudaroute.admin.tool_menu_order',['menu' => $menu->id]) }}', {
                            parent_id: parent_id,
                            order: slugs,
                            _token: '{{ csrf_token() }}',
                            dataType: 'json',
                        }, function (data) {
                            suda.alert(data.response_msg,'primary');
                        });

                    }
                });
            });
        }
        
        
    });
    
</script>
@endpush