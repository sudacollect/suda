@extends('view_path::layouts.default')

@push('styles')
<style>
    span.tag{
        color:#999;
    }
    .popover-title{
        padding:2px 10px;
    }
    .popover-title span{
        float:right;
        line-height: 2;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="row suda-row suda-row-extension">
        
        
        <div class="page-heading">
            <h1 class="page-title">
                <i class="ion-cube"></i>
                Extensions
            </h1>
            
            <div class="btn-groups ms-auto">
                <a href="{{ admin_url('entry/extensions') }}" target="_blank" class="btn btn-light btn-sm">
                    <i class="ion-apps-outline"></i>&nbsp;{{ __('suda_lang::press.extensions.board') }}
                </a>

                @if(\Gtd\Suda\Auth\OperateCan::operation($soperate))
                <button type="button" href="{{ admin_url('manage/extension/updatecache') }}" class="btn-refresh-cache btn btn-sm btn-light">
                    <i class="ion-sync"></i>&nbsp;{{ __('suda_lang::press.extensions.update_cache') }}
                </button>
                @endif
                
                <a href="{{ url('http://suda.gtd.xyz') }}" target="_blank" class="btn btn-light btn-sm">
                    <i class="ion-bag-handle-outline"></i>&nbsp;{{ __('suda_lang::press.extensions.market') }}
                </a>

            </div>

        </div>
        
        
        
        <div class="col-sm-12 suda_page_body">   
            <a  class="btn btn-md @if($active=="enabled") btn-primary @else btn-light  @endif" href="{{ admin_url('manage/extension/enabled') }}">{{ __('suda_lang::press.extensions.installed') }}</a>
            <a  class="btn btn-md @if($active=="available") btn-secondary @else btn-light @endif" href="{{ admin_url('manage/extension/available') }}">{{ __('suda_lang::press.extensions.available') }}</a>
            <a  class="btn btn-md @if($active=="package") btn-secondary @else btn-light @endif" href="{{ admin_url('manage/extension/package') }}">{{ __('suda_lang::press.extensions.package') }}</a>
            @include('view_path::extension.list_gallery')
        </div>
        
        
    </div>
</div>
@endsection


@push('scripts')

<script>
$(document).ready(function() {
    
    $('.suda-row-extension').find('.btn-refresh-cache').suda_ajax({
        type:'POST',
        confirm:false,
    });

    $('.suda-row-extension').find('.quickin-change').suda_ajax({
        type:'POST',
        confirm:false,
        on:'change',
    });
    
    $('.suda-row-extension').find('.btn-install').suda_ajax({
        type:'POST',
        title:"{{ __('suda_lang::press.extensions.ask_before_install') }}",
    });

    $('.suda-row-extension').find('.btn-uninstall').suda_ajax({
        type:'POST',
        title:"{{ __('suda_lang::press.extensions.ask_before_uninstall') }}",
    });
    
    $('.suda-row-extension').find('.btn-refresh').suda_ajax({
        type:'POST',
        confirm:false,
    });
    
    if(document.getElementById('app_sort_list'))
    {
        new Sortable.Sortable(document.getElementById('app_sort_list'), {
            animation: 150,
            onEnd: function (/**Event*/ evt) {
                var itemEl = evt.item; // dragged HTMLElement
                var slugs = new Array();
                $(evt.to).find('.ext-title').each(function(index,e){
                    slugs.push($(e).attr('data-slug'));
                });
                
                $.post('{{ admin_url("manage/extensionsort") }}', {
                    order: slugs,
                    _token: '{{ csrf_token() }}'
                }, function (data) {
                    // suda.alert('排序完成','primary');
                    // suda.modal('菜单排序完成','info');
                });
            },
        });
    }
    
});
</script>
@endpush
