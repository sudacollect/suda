@extends('view_path::layouts.default')

@push('styles')

<style>
.dd{padding:0 15px;}
.dd-list{
    display:block;
    width:100%;
}
.dd-list li{
    display:inline-block;
    width:100%;
    position:relative;
}

.dd-item{
    padding:0 15px;
}

.dd-item span.remove-select{
    position: absolute;
    right: 5px;
    top: 0;
    line-height: 150%;
}
.dd-handle{
    padding:0;
    border:none;
    margin:0;
}
.widget-content{
    margin:5px;
}
.widget-content .card{
    border:1px solid #eee;
    margin-bottom: 5px;
    border-radius: 2px;
}

#widgets-div span.help-block{
    margin: 0;
    line-height: 1.6;
    padding: 0 0.15rem;
    font-size:0.975rem;
}
.card .dd-handle > .card-header{
    padding:0.15rem 0.5rem;
    background:#fff;
    border-bottom: 1px solid #e8e8e8;
    font-size:1.1rem;
    font-weight:normal;
}
.dd-dragel .col-sm-6{
    width:100%;
}
.dd-placeholder{
    display:list-item;
    float:left;
    padding:0 15px;
    margin:0;
    /* border: 2px dashed #a7a7a7; */
    border: 1px dashed #e4ccb8;
    background: #fdfcf7;
}
#widgets-div .card-body,.dd-dragel .card-body{
    display:none;
}
#widgets-div .card-footer,.dd-dragel .card-footer{
    display:none;
}
.dd-dragel span.help-block{
    display:none;
}
.widget-area-list{
    margin-bottom:15px;
}
.widget-area-list .widget-area{
    background: #fff;
    border: 1px solid #eee;
    padding: 0px;
    box-shadow: 0px 0px 5px 0px rgba(1,1,1,0.04);
    display: inline-block;
    width: 100%;
}
.widget-area h3{
    margin: 0;
    padding: 0.375rem 1rem;
    background: #f9f9f9;
    font-size: 1.1rem;
    border-bottom:1px solid #eee;
}
.widget-area h3 span.icon{
    color:#777;
}
.widget-area h3 span.icon:hover{
    color:#333;
    cursor:pointer;
}
.widget-area-empty
{
    background:#fffee7;
    font-size:1rem;
    padding:30px 0px;
}
.dd-list li i.icon-switch{
    position: absolute;
    right: 1rem;
    z-index: 999;
    top: 5px;
    width:32px;
    text-align:center;
    color: #909090;
}
.dd-list li i.icon-switch-content{
    position: absolute;
    right: 1rem;
    top: 8px;
    z-index: 999;
    color: #999;
}
#widgets-div .dd-list li i.icon-switch{
    display:none;
}
.dd-list.dd-dragel li i.icon-switch-content,#widgets-div .dd-list li i.icon-switch-content{
    display:none;
}

</style>

@endpush

@section('content')
<div class="container">
    <div class="page-heading">
        <h1 class="page-title">
            <i class="ion-albums-outline"></i>&nbsp;挂件
            <span class="badge bg-light text-dark">{{ $app_name.'/'.$theme_name }}</span>
            <span class="help-block">
                拖动挂件到挂件区，即时启用
                <a href="https://suda.gtd.xyz" style="color:#999;">
                    <i class="ion-help-circle"></i> 如何使用?
                </a>
            </span>
        </h1>
        
    </div>

    <div class="row suda-row">
        
        
        
        <input type="hidden" name="app_name" value="{{ $app_name }}">
        <input type="hidden" name="theme_name" value="{{ $theme_name }}">
        
        @if(isset($widget_areas))
        <div class="col-sm-5" id="widgets-div">
            <div class="row">
            <ul class="dd-list ul-widget col-sm-6">
                
                @if(count($widgets)>0)

                @foreach($widgets as $key=>$widget)
                <li data-id="{{ $loop->iteration }}" data-ctl="{{ $widget['controller'] }}" data-slug="{{ $widget['slug'] }}" class="col-sm-12 dd-item">
                    <div class="widget-content">
                        
                        <div class="card">
                            
                            <div class="card-header bg-white d-flex">
                                {{ $widget['name'] }}
                                <i class="ion-ellipsis-horizontal ms-auto"></i>
                                {{-- <i class="ion-chevron-down icon-switch-content ms-auto"></i> --}}
                            </div>
                        
                            <div class="card-body">
                                {{-- 加载挂件的配置项目 --}}
                                {{ Sudacore::widget($widget['controller'],['view'=>'config']) }}
                            </div>

                            <div class="card-footer">
                                <button class="cancel-widget btn btn-light btn-sm">删除</button>
                                <button class="save-widget btn btn-primary btn-sm">保存</button>
                            </div>

                        </div>
                        <span class="help-block">{{ $widget['description'] }}</span>    
                    </div>
                    
                </li>

                @if($loop->iteration%5==0)
            </ul>

            <ul class="dd-list ul-widget col-sm-6">
                @endif

                @endforeach

                

                @endif

            </ul>

            </div>

        </div>
        @endif
        



        <div class="col-sm-7">
            @if(isset($widget_areas))

            @foreach($widget_areas as $key=>$area)
            <div class="col-sm-6  widget-area-list" >
                
                <div class="widget-area" data-area='{{ $key }}' @if(isset($area['max'])) data-max="{{ $area['max'] }}" @endif>
                    <h3 class="d-flex">
                        {{ $area['name'] }}
                        <i class="ion-chevron-down icon-switch ms-auto"></i>
                    </h3>

                    @if(count($theme_widgets)>0 && isset($theme_widgets[$key]))
                    <ul class="dd-list ul-area">
                        @foreach($theme_widgets[$key] as $t_key=>$t_widget)
                        
                        @if(isset($widgets[$t_widget['widget_slug']]))
                        @php
                            $_widget = $widgets[$t_widget['widget_slug']];
                        @endphp
                        <li  data-id="{{ $t_widget['widget_id'] }}" data-ctl="{{ $_widget['controller'] }}" data-slug="{{ $t_widget['widget_slug'] }}" data-area='{{ $key }}' class="col-sm-12 dd-item">
                            
                            <div class="widget-content">
                                
                                <div class="card">
                                        <div class="card-header bg-white d-flex">
                                            {{ $_widget['name'] }}
                                            <i class="ion-chevron-down icon-switch-content ms-auto"></i>
                                        </div>
                                    <div class="card-body" style="display:none;">
                                        {{-- 加载挂件的配置项目 --}}
                                        {{ Sudacore::widget($_widget['controller'],['view'=>'config','content'=>$t_widget['contents']]) }}
                                    </div>
                                    <div class="card-footer" style="display:none;">
                                        <button class="cancel-widget btn btn-light btn-sm">删除</button>
                                        <button class="save-widget btn btn-primary btn-sm">保存</button>
                                    </div>
                                </div>
                            </div>
                        </li>
                        @endif
                        @endforeach

                    </ul>
                    <div class="d-flex justify-content-center flex-fill widget-area-empty" style="display:none !important;">
                        拖动挂件到这里
                    </div>
                    @else
                    <ul class="dd-list ul-area"></ul>
                    <div class="d-flex justify-content-center flex-fill widget-area-empty">
                        拖动挂件到这里
                    </div>
                    @endif

                </div>
                
            </div>
            @endforeach

            @else

            <div class="col-sm-6  widget-area-list" >
                
                <div class="widget-area-empty">
                    <p style="color:#999;">
                        本模板无可用挂件区域
                    </p>

                </div>
                
            </div>

            @endif

        </div>  
        

        
        
    </div>
</div>
@endsection


@push('scripts')

<script type="text/javascript">
    
    $(document).ready(function(){

        $.mediabox({
            box_url: "{{ admin_url('medias/load-modal/') }}",
            modal_url: "{{ admin_url('medias/modal/') }}",
            upload_url: "{{ admin_url('medias/upload/image/') }}",
            remove_url: "{{ admin_url('medias/remove/image/') }}"
        });

        var app_name = $('input[name="app_name"]').val();
        var theme_name = $('input[name="theme_name"]').val();

        // console.log(window);

        $('ul.ul-widget').each(function(index,el){
            new Sortable.Sortable(el, {
                group: {
                    name: 'widget-item',
                    pull: 'clone',
                    put: false // Do not allow items to be put into this list
                },
                animation: 150,
                sort: true,
                onClone: function (evt){
                    var cloneEl = evt.clone;
                },
                onEnd: function (/**Event*/ evt) {
                    var itemEl = evt.item;
                    var moment = window.moment();
                    $(itemEl).attr('data-id',$(itemEl).attr('data-slug')+moment.format('x'));
                    
                    $(itemEl).find('span.help-block').hide();
                    $(itemEl).find('.ion-ellipsis-horizontal').removeClass('ion-ellipsis-horizontal')
                        .addClass('ion-chevron-up icon-switch-content');
                    
                    if($(itemEl).parent('.ul-area').children().length > 0)
                    {
                        $(itemEl).parents('.widget-area').find('.widget-area-empty').attr('style','display:none !important');
                    }
                },
                onUnchoose: function (/**Event*/ evt) {
                    var itemEl = evt.item;
                    var moment = window.moment();
                    $(itemEl).attr('data-id',moment.format('x'));
                    $(itemEl).find('span.help-block').show();
                },
            });
        });

        $('ul.ul-area').each(function(index,el){
            new Sortable.Sortable(el, {
                group: {
                    name: 'widget-item',
                    pull: false,
                    put: true // Do not allow items to be put into this list
                },
                animation: 150,
                sort: true, // To disable sorting: set sort to false
                onSort: function (/**Event*/ evt) {
                    var itemEl = evt.item;

                    var slugs = new Array();
                    $(evt.to).find('li.dd-item').each(function(index,e){
                        slugs.push($(e).attr('data-id'));
                    });
                    changeOrder(slugs);
                },
            });
        });
        
        
        //收起缩放
        $('.widget-area-list').on('click','i.icon-switch',function(e){
            e.preventDefault();

            $(this).parents('.widget-area').find('.dd-list').toggle();
            
            if($(this).hasClass('ion-chevron-down')){
                $(this).addClass('ion-chevron-up');
                $(this).removeClass('ion-chevron-down');
            }else{
                $(this).addClass('ion-chevron-down');
                $(this).removeClass('ion-chevron-up');
            }
        });

        //收起缩放
        $('.widget-area').on('click','.widget-content .card-header',function(e){
            e.preventDefault();

            $(this).parents('li').find('.card-body,.card-footer').toggle();
            
            if($(this).find('i').hasClass('ion-chevron-down')){
                $(this).find('i').addClass('ion-chevron-up');
                $(this).find('i').removeClass('ion-chevron-down');
            }else{
                $(this).find('i').addClass('ion-chevron-down');
                $(this).find('i').removeClass('ion-chevron-up');
            }
        });

        //移除挂件
        $('.widget-area').on('click','button.cancel-widget',function(e){
            e.preventDefault();

            var widget_area = $(this).parents('.widget-area');
            var widget_content = $(this).parents('li');
            var widget_id = $(this).parents('li').attr('data-id');

            $(widget_content).remove();
            removeWidget(widget_id);
            if($(this).parents('.ul-area').children().length<1)
            {
                $(widget_area).find('.widget-area-empty').attr('style','');
            }
            
        });

        $('.widget-area').on('click','button.save-widget',function(e){
            e.preventDefault();

            var widget_area_el = $(this).parents('.widget-area');
            var widget_area_slug = $(widget_area_el).attr('data-area');
            var widget_content = $(this).parents('.widget-content');
            var widget_id = $(this).parents('li').attr('data-id');
            var widget_slug = $(this).parents('li').attr('data-slug');
            var widget_ctl = $(this).parents('li').attr('data-ctl');
            var form_data = $(this).parents('li').find('form').serialize();

            var action = suda.link(suda.data('adminPath')+'/widget/'+widget_slug+'/save');
            $.ajax({
                type    : 'POST', 
                url     : action,
                cache   : false,
                data    : {
                    app: app_name,
                    theme: theme_name,
                    _token:suda.data('csrfToken'),
                    data:form_data,
                    widget_area:widget_area_slug,
                    widget_ctl:widget_ctl,
                    widget_id:widget_id
                },
                success : function(data){
                    widget_content.find('.card-footer')
                        .css('background','#A5FFBE')
                        .append('<span id="save-result" style="float:right;color:#196C30;"><i class="icon ion-checkmark-circle"></i></span>');
                    widget_content.find('.card-footer').find('span#save-result').animate({
                        opacity: 0.15,
                    }, 1000, function() {
                        $(this).parent('.card-footer').removeAttr('style');
                        $(this).remove();
                    });
                },
                error : (function(xhr){
                    if(xhr.status == 422){
                        var errors = xhr.responseJSON;
                        suda.modal(errors.response_msg,errors.response_type);
                    }else{
                        suda.modal('请求异常，请稍后重试','warning');
                    }
                }),
                fail : (function() {
                    suda.modal({error:'加载失败，请重试'},'warning');
                })
            });

        });


        var removeWidget = function(widget_id){

            $.post('{{ admin_url("widget/remove") }}', {
                app: app_name,
                theme: theme_name,
                widget_id: widget_id,
                _token: '{{ csrf_token() }}'
            }, function (data) {
                //suda.alert('已删除');
            });
        }

        
        var changeOrder = function(sorts_data){

            $.post('{{ admin_url("widget/sort/order") }}', {
                app: app_name,
                theme: theme_name,
                order: sorts_data,
                _token: '{{ csrf_token() }}'
            }, function (data) {
                suda.alert('完成排序');
            });
        }
        

    });
    
</script>
@endpush