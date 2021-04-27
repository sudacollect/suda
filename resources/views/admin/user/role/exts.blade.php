@extends('view_path::layouts.default')



@section('content')
<div class="container-fluid">

    <div class="page-heading">
        <h1 class="page-title">
            角色应用授权 <span class="help-popover"><i class="ion-help-circle"></i></span>
            <span class="help-block">
            选择应用并设置权限
            </span>
        </h1>
    </div>

    @if($ext_count>0)

    <form class="form-ajax" method="POST" id="exts-form" action="{{ admin_url('user/roles/saveexts') }}">

        <div class="row">
            <div class="col-sm-12 mb-3">
                <button type="submit" class="btn btn-primary btn-md" id="submit">保存设置</button>
            </div>
        </div>
        <div class="row suda-row">
            
            

                {{ csrf_field() }}
                <input type="hidden" name="role_id" value="{{ $role->id }}" >

                @foreach ($ext_list as $slug=>$item)

                <div class="col-sm-3  suda_page_body">
                    <div class="card card-role-exts" ext-slug="{{ $item['slug'] }}">
                        <div class="card-body">
                            <span class="check_ext @if(array_key_exists($item['slug'],$role->permissions['exts'])) checked @endif" style="position:absolute;left:10px;top:5px;">
                            <i class="zly-check-circle" style="font-size:2rem;"></i>
                            </span>

                            <p class="ext-logo @if(array_key_exists($item['slug'],$role->permissions['exts'])) checked @endif">
                                <img src="{{ extension_logo($item['slug']) }}" class="icon icon-extension" style="border-radius:50%; @if($ext_count<9) max-width:120px; @else max-width:80px; @endif">
                            </p>
                            <p class="ext-name">
                                {{ $item['name'] }}
                            </p>

                            <p class="ext-name">
                                <button type="button" href="{{ admin_url('user/roles/extDetail/'.$role->id.'/'.$item['slug']) }}" class="ext-permission pop-modal btn btn-primary btn-sm">设置权限</button>
                            </p>

                        </div><!-- panel-body -->
                        @if(array_key_exists($item['slug'],$role->permissions['exts']))
                            <input type="hidden" name="select_exts[{{ $item['slug'] }}]" value="{{ $item['slug'] }}">
                            @php

                            $build_permission = http_build_query(['permission'=>$role->permissions['exts'][$item['slug']]], '', '&');

                            @endphp
                            <input type="hidden" name="select_permission[{{ $item['slug'] }}]" value="{{ $build_permission }}">
                        @endif
                    </div><!-- panel -->
                </div><!-- suda_page_body -->

                @endforeach
                
                

            
        </div>
        <div class="row">
            <div class="col-sm-12 mt-3">
                <button type="submit" class="btn btn-primary btn-md" id="submit">保存设置</button>
            </div>
        </div>

    </form>

    @else

    @include('view_suda::admin.component.empty',['type'=>'content','empty'=>'Oops... 还没有启用任何应用'])

    @endif
</div>



@endsection

@push('styles')
<style>

.popover-content .show-help{
    width:260px;
}

.popover-content .show-help div{
    display:inline-block;width:100%;padding:2px 5px;font-size:1.2rem;
}

</style>
@endpush

@push('scripts')

<script type="text/javascript">

$(document).ready(function(){


    $('.help-popover').popover({
        html:true,
        
        content:function(){
            var description="可点选左上角的按钮 <i class='icon ion-checkmark-circle' style='color:#0088ff;'></i> 启用或取消应用授权";
            
            var content_html = "<div class='show-help'>";
            
            content_html += '<div>'+description+'</div>';
            
            
            content_html += '</div>';
            
            return content_html;
            
        },
        trigger:'hover',
        placement:'right'
    });

    $('.card-role-exts').on('click','span.check_ext',function(e){

        e.preventDefault();
        var panel_ext = $(this).parents('.card-role-exts');
        var check_ext = $(panel_ext).find('span.check_ext');
        var ext_logo = $(panel_ext).find('.ext-logo');

        if(check_ext.hasClass('checked')){

            check_ext.removeClass('checked');
            ext_logo.removeClass('checked');
            
            $(panel_ext).find('input[name^=select_exts]').remove();
            $(panel_ext).find('button.ext-permission').attr('disabled','disabled');
        }else{
            check_ext.addClass('checked');
            ext_logo.addClass('checked');
            $(panel_ext).find('button.ext-permission').removeAttr('disabled');
            $(panel_ext).append('<input type="hidden" name="select_exts['+$(panel_ext).attr('ext-slug')+']" value="'+$(panel_ext).attr('ext-slug')+'">');
        }

    });

    

});

</script>

@endpush
