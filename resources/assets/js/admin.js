jQuery(function(){
    
    // Firefox和Chrome早期版本中带有前缀
    var MutationObserver = window.MutationObserver || window.WebKitMutationObserver || window.MozMutationObserver

    // 选择目标节点
    var target = document.querySelector('body');
    
    // 创建观察者对象
    var observer = new MutationObserver(function(mutations) {
        // 
    });
 
    // 配置观察选项:
    var config = { attributes: true, childList: true}
 
    // 传入目标节点和观察选项
    observer.observe(target, config);

    if($('.suda-toast').length>0){
        var suda_flat_width = $('.suda-flat').width();
        var suda_toast_width = $('.suda-toast').width();

        $('.suda-toast').css({
            'right':(suda_flat_width-suda_toast_width)/2
        }).toast('show');
    }
    
    
    $('.navbar-take-toggle').on('click',function(e){
        
        var sidemenu_style = 'flat';
        if($('.sidebar-brand').hasClass('only')){
            sidemenu_style = 'flat';
            $('.sidebar-brand').removeClass('only');
        }else{
            $('.sidebar-brand').addClass('only');
            sidemenu_style = 'icon';
        }

        const update_url = $(this).attr('href');
        
        if($('.press-sidebar').hasClass('in')) {
            //small
            $('.press-sidebar').removeClass('in').addClass('press-sidebar-icon');
            
            $('.press-sidebar').removeAttr('style');
            
            if($('.suda-flat').hasClass('suda-flat-lg')){
                $('.suda-flat').removeClass('suda-flat-lg');
            }else{
                $('.suda-flat').addClass('suda-flat-lg');
            }
            
            $('.press-sidebar-icon').find('.sidebar-menu-item.collapse').collapse('hide');
            
            $('.press-sidebar').find('.sidebar-menu-item').on('show.bs.collapse',function(){
                $('.press-sidebar-icon').find('.sidebar-menu-item.collapse').collapse('hide');
            });

            $('.navbar-suda').addClass('navbar-suda-icon');
            
            sidemenu_style = 'icon';
            
        } else {
            // $('.press-sidebar').css('overflow-y','auto');
            //big
            $('.press-sidebar').addClass('in');
            $('.press-sidebar').addClass('in').removeClass('press-sidebar-icon');
            
            if($('.suda-flat').hasClass('suda-flat-lg')){
                $('.suda-flat').removeClass('suda-flat-lg');
            }else{
                $('.suda-flat').addClass('suda-flat-lg');
            }

            if($('.navbar-suda').hasClass('navbar-suda-icon'))
            {
                $('.navbar-suda').removeClass('navbar-suda-icon')
            }
            $('.sidebar-menu-item').collapse('hide');
        }
        
        $.ajax({
            
            type    : 'POST', 
            url     : update_url,
            cache   : false,
            data: {_token:suda.data('csrfToken'),style:sidemenu_style},
            success : function(data){
                //suda.modal(data.response_msg);
            },
            error : (function(xhr){
                //
            }),
            fail : (function() {
                suda.modal({error:'加载失败，请重试'});
                $(this).removeAttr('disabled');
            })
            
        });
        
    });

    //侧边栏菜单
    $('.press-sidebar').find('.sidebar-menu-item').on('show.bs.collapse', function () {
        $('.press-sidebar').find('.sidebar-menu-item[id!='+$(this).attr('id')+']').collapse('hide');
    })
    
    // $('[data-toggle="tooltip"]').tooltip();
    
    $('img[data-toggle="popover"]').popover({
        html:true,
        content:function(){
            if($(this).attr('data-image')=='true'){
                return "<img src='"+$(this).attr('src')+"'>";
            }
        },
        trigger:'hover',
        placement:'right'
    });
    
    $.fn.ajaxform = function(){
        var elem = this;
        $(elem).find('button[type="submit"]').on('click',function(e){
             e.preventDefault();

             var aform = $(this).parents('form');
             var aurl = $(aform).attr('action');
         
             //$(this).attr('disabled','disabled');
         
             $.ajax({
                 type    : 'POST', 
                 url     : aurl,
                 cache   : false,
                 data: $(aform).serialize(),
                 success : function(data){
                     suda.modal(data.response_msg,data.response_type);
                     if(data.response_url=='ajax.close'){
                        $('.modal.show:visible').modal('hide');
                        return;
                     }
                     if(data.response_url=='self.refresh'){
                        var timesRun = 0;
                         var interval = setInterval(function(){
                             timesRun += 1;
                             if(timesRun === 3){
                                 clearInterval(interval);
                             }
                         
                             window.location.reload(); 
                         
                         }, 2000);
                         return;
                     }
                     if(data.response_url && (data.response_url!='ajax.close' || data.response_url!='self.refresh')){
                     
                         var timesRun = 0;
                         var interval = setInterval(function(){
                             timesRun += 1;
                             if(timesRun === 3){
                                 clearInterval(interval);
                             }
                         
                             window.location.href = data.response_url;
                         
                         }, 2000);
                     }
                 },
                 error : (function(xhr){
                     if(xhr.status == 422){
                         
                        if(typeof xhr.responseJSON != 'undefined'){
                            var result = xhr.responseJSON;
                            if(typeof result.errors != 'undefined'){
                                var errors = result.errors;
                                var error_str = '';
                                $.each(errors,function(k,v){
                                    error_str += v[0]+'<br>';
                                });
                                suda.modal(error_str,'fail');
                            }
                            if(typeof result.response_msg != 'undefined'){
                                suda.modal(result.response_msg,result.response_type);
                            }
                         }
                         
                     }else{
                         suda.modal('请求异常，请稍后重试','warning');
                     }
                     $(this).removeAttr('disabled');
                 }),
                 fail : (function() {
                         suda.modal({error:'加载失败，请重试'},'warning');
                         $(this).removeAttr('disabled');
                   })
             });
         
        });
        
    };

    //自动处理页面中的form-ajax事件
    if($('body').find('form.form-ajax').length > 0)
    {
        $('body').find('form.form-ajax').ajaxform();
    }

    
    $('body').on('click','.pop-modal-delete',function(e){
        e.preventDefault();
        var ele = this;
        var status_str = $(this).attr('data_title')?$(this).attr('data_title'):'是否操作';
        var delete_statu = window.confirm(status_str);
        var delete_url = $(this).attr('href');
        var delete_id = $(this).attr('data_id');
        if(delete_statu){
            
            $.ajax({
                type    : 'POST', 
                url     : delete_url,
                cache   : false,
                dataType: 'json',
                data: { id:delete_id, _token:suda.data('csrfToken') },
                success : function(data){
                    suda.modal(data.response_msg,'',true);
                    $(ele).parents('tr').remove();
                    if(data.response_url){
                        var timesRun = 0;
                        var interval = setInterval(function(){
                            timesRun += 1;
                            if(timesRun === 3){
                                clearInterval(interval);
                            }
                            if(data.response_url=='self.refresh'){
                                window.location.reload(); 
                            }else{
                                window.location.href = data.response_url;
                            }
                            
                        }, 2000);
                    }
                },
                error : (function(xhr){
                    if(xhr.status == 422){
                        if(typeof xhr.responseJSON != 'undefined'){
                            var result = xhr.responseJSON;
                            if(typeof result.errors != 'undefined'){
                                var errors = result.errors;
                                var error_str = '';
                                $.each(errors,function(k,v){
                                    error_str += v[0]+'<br>';
                                });
                                suda.modal(error_str,'fail');
                            }
                            if(typeof result.response_msg != 'undefined'){
                                suda.modal(result.response_msg,result.response_type);
                            }
                         }
                    }
                    if(xhr.status == 405){
                        if(xhr.responseText){
                            suda.modal(xhr.responseText);
                        }else{
                            alert('错误的请求');
                        }
                    }
                    $(this).removeAttr('disabled');
                }),
                fail : (function() {
                        suda.modal({error:'加载失败，请重试'});
                        $(this).removeAttr('disabled');
                  })
            });
            
        }else{
            //
        }
        
    });

    

    $('body').on('click','.x-suda-pop-action',function(e){
        e.preventDefault();
        var ele = this;
        var status_str = $(this).attr('action_title')?$(this).attr('action_title'):'是否操作';
        var action = $(this).attr('action')?$(this).attr('action'):null;
        var action_remove = $(this).attr('action_remove')?$(this).attr('action_remove'):null;
        var delete_statu = window.confirm(status_str);
        var action_url = $(this).attr('href');
        var action_id = $(this).attr('action_id');
        if(delete_statu){
            
            $.ajax({
                type    : 'POST', 
                url     : action_url,
                cache   : false,
                dataType: 'json',
                data: { id:action_id,action:action, _token:suda.data('csrfToken') },
                success : function(data){
                    suda.modal(data.response_msg,'',true);
                    if(action_remove){
                        $(action_remove).remove();
                    }
                    if(data.response_url){
                        var timesRun = 0;
                        var interval = setInterval(function(){
                            timesRun += 1;
                            if(timesRun === 3){
                                clearInterval(interval);
                            }
                            
                            if(data.response_url=='self.refresh'){
                                window.location.reload(); 
                            }else{
                                window.location.href = data.response_url;
                            }

                        }, 2000);
                    }
                },
                error : (function(xhr){
                    if(xhr.status == 422){
                        if(typeof xhr.responseJSON != 'undefined'){
                            var result = xhr.responseJSON;
                            if(typeof result.errors != 'undefined'){
                                var errors = result.errors;
                                var error_str = '';
                                $.each(errors,function(k,v){
                                    error_str += v[0]+'<br>';
                                });
                                suda.modal(error_str,'fail');
                            }
                            if(typeof result.response_msg != 'undefined'){
                                suda.modal(result.response_msg,result.response_type);
                            }
                         }
                    }
                    if(xhr.status == 405){
                        if(xhr.responseText){
                            suda.modal(xhr.responseText);
                        }else{
                            alert('错误的请求');
                        }
                    }
                    $(this).removeAttr('disabled');
                }),
                fail : (function() {
                        suda.modal({error:'加载失败，请重试'});
                        $(this).removeAttr('disabled');
                  })
            });
            
        }else{
            //
        }
    });

    // ajax请求函数
    $.fn.suda_ajax = function(custom_options){

        var options = {
            type:'GET',
            confirm:true,
            title:'确认操作?',
            dataType:'json',
            remove:false,
            action:'',
            on:'click',
        };

        $.extend(options, custom_options);

        var fn_elem = this;

        $(fn_elem).on(options.on,function(e){
            e.preventDefault();
            
            if(options.confirm)
            {
                var confirm_result = window.confirm(options.title);
            }else{
                var confirm_result = true;
            }
            var elem = this;

            if(confirm_result)
            {
                var ajax_href = $(elem).attr('href');
                var ajax_id = $(elem).attr('data-id')?$(elem).attr('data-id'):0;
                var ajax_content = $(elem).attr('data-content')?$(elem).attr('data-content'):'';
                
                $.ajax({
                    type    : options.type, 
                    url     : ajax_href,
                    cache   : false,
                    dataType: options.dataType,
                    data: { id:ajax_id,content:ajax_content,action:options.action,_token:suda.data('csrfToken') },
                    success : function(data){
                        suda.modal(data.response_msg,'',true);
                        if(options.remove){
                            if($(options.remove).length>0)
                            {
                                $(options.remove).remove();
                            }
                        }
                        if(data.response_url){
                            var timesRun = 0;
                            var interval = setInterval(function(){
                                timesRun += 1;
                                if(timesRun === 3){
                                    clearInterval(interval);
                                }
                                if(data.response_url=='self.refresh'){
                                    window.location.reload(); 
                                }else{
                                    window.location.href = data.response_url;
                                }
    
                            }, 2000);
                        }
                    },
                    error : (function(xhr){
                        if(xhr.status == 422){
                            if(typeof xhr.responseJSON != 'undefined'){
                                var result = xhr.responseJSON;
                                if(typeof result.errors != 'undefined'){
                                    var errors = result.errors;
                                    var error_str = '';
                                    $.each(errors,function(k,v){
                                        error_str += v[0]+'<br>';
                                    });
                                    suda.modal(error_str,'fail');
                                }
                                if(typeof result.response_msg != 'undefined'){
                                    suda.modal(result.response_msg,result.response_type);
                                }
                             }
                        }
                        if(xhr.status == 405){
                            if(xhr.responseText){
                                suda.modal(xhr.responseText);
                            }else{
                                alert('错误的请求');
                            }
                        }
                        $(elem).removeAttr('disabled');
                    }),
                    fail : (function() {
                            suda.modal({error:'加载失败，请重试'});
                            $(elem).removeAttr('disabled');
                    })
                });
            }
        });

    };

    // 弹窗函数
    $.fn.popModal = function(data){

        var elem = this;
        var set_content = $(elem);
        if(set_content){
            var set_content_type = set_content.attr('_data_type');
        }
        
        var data_html = $(data)[0];
        var attr_id = $(data_html).attr('id');
        if (typeof attr_id !== typeof undefined && attr_id !== false) {
            
        }else{
            var elem_id = $(elem).attr('data-modal-id');
            if (typeof elem_id !== typeof undefined && elem_id !== false) {
                var attr_id = elem_id;
                $(data_html).attr('id',attr_id);
            }else{
                var moment = window.moment();
                var attr_id = 'modal_'+moment.format('x');
                $(data_html).attr('id',attr_id);
            }
        }
        
        $('body').append($(data_html));

        var attr_modal = $('#'+attr_id);

        attr_modal.on('hidden.bs.modal', function (e) {
            attr_modal.remove();
        });
        
        attr_modal.modal('show');
    };
    
    // 弹窗图片
    $.fn.popMediaModel = function(data,elem,token,img_size){
        
        var set_content = $(elem);
        if(set_content){
            var set_content_type = set_content.attr('_data_type');
            var set_content_max = set_content.attr('media_max')||1;
        }else{
            if(elem=='made-by-suda'){
                var set_content_type = 'editor';
                var set_content_max = 5;
            }
        }
        
        $('body').append($(data));
        
        var modalLayout = $('.modal-image-upload');
        
        modalLayout.on('hidden.bs.modal', function (e) {
            modalLayout.remove();
        });
        
        //upload-box
        var modal_upload_complete = function (e, file, response) {
            response = JSON.parse(response);
        	if (response.error) {
        		suda.modal(response.error);
                //suda.infobox(response.error);
        	} else {
        		var $target = $(this).find(".filelist.queue").find("li[data-index=" + file.index + "]");
                
                
                //trigger header tab
                $(modalLayout).find('.modal-title-tabs').find('li a[affect-id="media-box"]').trigger('click',true);
                
                //=====TODO: delete insert page
                $image_html = response.image;
                $image_html += "<input type='hidden' name='images["+response.media_name+"]' value='"+response.media_id+"'>";
        
        		$target.find(".file").html($image_html);
        		$target.find(".progress").remove();
        		$target.find(".cancel").remove();
        		$target.appendTo($(this).find(".filelist.complete"));
        
                //var modalUpload = $('.modal-image-upload');
            
                // if(modalUpload.find(".filelist.complete li:has('div.checked')").length<1){
//                     modalUpload.find('.filelist.complete li:first-child').append('<div class="checked"><i class="ion-checkmark-circle"></i></div>');
//                 }
                //=====TODO: delete insert page
                
        	}
        };    

        var media_type = $(modalLayout).find('.suda-upload-modal').attr('media_type');
        var media_crop = $(modalLayout).find('.suda-upload-modal').attr('media_crop');

        
        var upload_url = suda.link(window.suda.meta['.mediabox_upload_url']+media_type);
        var remove_url = suda.link(window.suda.meta['.mediabox_remove_url']+media_type);
        
        

        const media_name = $(modalLayout).find('.suda-upload-modal').attr('media_name');
        let media_max = $(modalLayout).find('.suda-upload-modal').attr('media_max')||1;
        const upload_label = $(modalLayout).find('.suda-upload-modal').children('#upload_label').html();
        const has_number = set_content.parents('.x-suda-upload-box').children('.x-suda-upload-box-div:has(".uploadbox-filled")').length
        media_max = media_max - has_number

        // if(media_type!='' && media_type!=undefined){
        //     upload_url += "/"+media_type;
        // }
        
        $(modalLayout).find('.suda-upload-modal').zpupload({
            action:upload_url,
            removeUrl:remove_url,
            label:upload_label,
            leave:'Files uploading，quit?',
            maxQueue:1,
            maxFiles:false,
            multiple:true,
            postData:{_token:suda.data('csrfToken'),media_type:media_type,media_crop:media_crop,media_name:media_name},
            delete:false,
            postKey:'img',
            onComplete:modal_upload_complete
        });
        
        var modal_load = function(load_url,data,modal_body,check_first){
            var data = data?data:{};
            var check_first = check_first?check_first:false;
            
            var modal_content = $(modal_body).parents('.modal-content');
            var modal_footer = $(modal_content).find('.modal-footer');
            
            var spinner = '<div class="spinner-grow text-primary spinner-grow-sm" role="status"><span class="sr-only">Loading...</span></div>';
            if($(modal_body).prev('.modal-header').find('.nav-tabs').find('.media-loading').length<1){
                $(modal_body).prev('.modal-header').find('.nav-tabs').append('<li class="media-loading nav-item"><a class="nav-link">'+spinner+'</a></li>');
            }

            var columnWidth = $( window ).width() < 640 ? 135 : 150;

            var media_lists_outerWidth = modalLayout.width()-100;
            var media_lists_innerWidth = media_lists_outerWidth-30;

            var columnSet = Math.min( Math.round( media_lists_innerWidth / columnWidth ), 12 ) || 1;

            var data = $.extend({column:columnSet,mediabox_width:media_lists_innerWidth}, data);

            $.ajax({
                type    : 'GET', 
                url     : load_url,
                data    : data,
                cache   : false,
                success : function(res){
                   if(res){
                       $(modal_body).html($(res));

                       $(modal_body).find('.media-lists').attr('data-columns',columnSet);

                       $(modal_body).prev('.modal-header').find('.media-loading').remove();
                       if($(modal_footer).find('.pagination').length>0){
                           $(modal_footer).find('.pagination').remove();
                       }
                       $(modal_footer).prepend($(modal_body).find('.pagination').clone());
                       if(check_first==true){
                           $(modal_body).find('.media-lists li:first-child').find('a').trigger('click');
                           
                       }
                   }
                },
                error : (function(xhr){
                    if(xhr.status==422){
                        var errors = xhr.responseJSON;
                        suda.modal(errors);
                    }
                }),
                fail : (function() {
                    suda.modal({error:'加载失败，请重试'});
                })
    
            });
        }
        
        var layout_height = $(window).innerHeight()-200;
        layout_height = layout_height<500?500:layout_height;
        
        modalLayout.find('.modal-body').css({
            'height':layout_height,
            'overflow-y':'hidden'
        });
        
        modalLayout.find('.modal-title-tabs').on('click','li a',function(e,check_first){
            
            e.preventDefault();
            var affect_id = $(this).attr('affect-id');
            var check_first = check_first?check_first:false;
            
            $(this).parents('ul').children('li').removeClass('active');
            $(this).parents('ul').children('li').find('a').removeClass('active bg-white');
            $(this).parent('li').addClass('active');
            $(this).addClass('active bg-white');
            
            modalLayout.find('.modal-body').find('div.checked').remove();
            
            modalLayout.find('.modal-body[id!="'+affect_id+'"]').hide();
            modalLayout.find('.modal-body[id="'+affect_id+'"]').show();
            
            if(affect_id=='media-box'){

                //刷新载入数据
 modal_load(suda.link(suda.meta['.mediabox_modal_url']+media_type),{},modalLayout.find('.modal-body[id="media-box"]'),check_first);
                
                var modal_body = modalLayout.find('.modal-body[id="'+affect_id+'"]');
                
                $(modal_body).find('form#search-box-form').on('submit',function(e){
                    e.preventDefault();
                    var href = $(this).attr('action');
            
                    $.ajax({
                        type    : 'POST',
                        url     : href,
                        data    : $(this).serialize(),
                        cache   : false,
                        success : function(data){
                            $(modal_body).html($(data));
                        }
                    });
            
                });
                
                
                
            }else{
                //隐藏已上传文件
                modalLayout.find('.filelist.complete').children('li').remove();
                modalLayout.find('.modal-footer .pagination').hide();
            }
            
        });
        
        //预先载入数据
        modal_load(suda.link(suda.meta['.mediabox_modal_url']+media_type),{},modalLayout.find('.modal-body[id="media-box"]'));
        
        //分页点击
        modalLayout.find('.modal-footer').on('click','ul.pagination li a',function(e){
            e.preventDefault();
            var href = $(this).attr('href');
            modal_load(href,{},modalLayout.find('.modal-body[id="media-box"]'));
        });

        //标签
        modalLayout.find('.modal-body').on('click','span.modal-tag-filter',function(e){
            e.preventDefault();
            var tag_id = $(this).attr('tag-id')||0;
            var request_href = suda.link(suda.meta['.mediabox_modal_url']+media_type);
            modal_load(request_href,{tag_id:tag_id},modalLayout.find('.modal-body[id="media-box"]'));
        });
        
        //搜索框
        
        //图片点击动作
        modalLayout.on('click','.media-lists li a',function(e){
            e.preventDefault();
            
            var $checked = $(this).find('div.checked');
            var media_id = $(this).attr('media_id');
            var columnWidth = $( window ).width() < 640 ? 135 : 150;
            columnWidth = columnWidth-16;

            if($checked.length>0){
                $checked.remove();
                $(this).find('input').remove();
            }else{
                if(modalLayout.find('div.checked').length>0){
                    if(media_max>1){
                        if(modalLayout.find('div.checked').length >= media_max){
                            suda.modal('最多可选择'+media_max+'张图片');
                            return false;
                        }
                    }else{
                        modalLayout.find('div.checked').remove();
                        modalLayout.find('input').remove();
                    }
                    
                }
                $(this).append('<div class="checked" style="line-height:'+columnWidth+'px"><i class="ion-checkmark-circle"></i></div>');
                if(media_max>1){
                    if(media_name){
                        $(this).append("<input type='hidden' name='images["+media_name+"][]' value='"+media_id+"'>");
                    }else{
                        $(this).append("<input type='hidden' name='images[]' value='"+media_id+"'>");
                    }
                    
                }else{
                    if(media_name){
                        $(this).append("<input type='hidden' name='images["+media_name+"]' value='"+media_id+"'>");
                    }else{
                        $(this).append("<input type='hidden' name='images[]' value='"+media_id+"'>");
                    }
                    
                }
            }
        });
        
        modalLayout.modal('show');
        
        //保存内容
        modalLayout.find('#btn-save').on('click',function(){
            var modalBody = modalLayout.find('.modal-body:visible');
            
            var files = modalLayout.find('.media-lists li:has("div.checked")');
            
            if(files.length < 1){
                return false;
            }
            var images = '';
            
            if(set_content){

                var set_content_clone = set_content.clone();
                set_content_clone.addClass('uploadbox-filled');
                var media_modal_group = set_content.parents('.x-suda-upload-box');

                var current_count = media_modal_group.children('.x-suda-upload-box-div:has(".uploadbox-filled")').length;
                var avialiable_count = set_content_max - current_count + 1;
                if(avialiable_count > set_content_max)
                {
                    avialiable_count = set_content_max;
                }

                files.each(function(index,e){
                    if(index < avialiable_count){
                        var tt = $(e).find('img').parent().clone();
                        $(tt).find('div.checked').remove();
                        //images = $(tt).prop('innerHTML');

                        var $items =$('<div class="image_show">' + $(tt).prop('innerHTML') + '</div>');
                        $items.find('img').removeClass('d-none');
                        $items.append("<div class='x-suda-upload-action'><span class='btn btn-dark btn-xs x-suda-upload-action-switch'><i class='ion-sync-circle'></i>替换</span><span class='btn btn-dark btn-xs x-suda-upload-action-delete'><i class='ion-close-circle'></i>删除</span></div>");
                        
                        if(index === 0)
                        {
                            set_content.parent('.x-suda-upload-box-div').find('.x-suda-upload-item').addClass('uploadbox-filled').html($items);
                        }else{
                            var list_group_item = set_content.parent('.x-suda-upload-box-div').clone();
                            if(index>0){
                                if(list_group_item.find('.x-suda-remove-upload-item').length<1){
                                    list_group_item.append('<span class="x-suda-remove-upload-item"><i class="ion-close-circle"></i></span>');
                                }
                            }
                            
                            list_group_item.find('.x-suda-upload-item').addClass('uploadbox-filled').html($items);
                            media_modal_group.append(list_group_item);
                        }
                    }
                });

                // set_content.parent('.x-suda-upload-box-div').remove();
                media_modal_group.children('.x-suda-upload-box-div:not(:has(".uploadbox-filled"))').remove();

                //判断可上传的数量
                var media_filled_count = media_modal_group.children('.x-suda-upload-box-div').length;
                
                if(set_content_max > media_filled_count){
                    var modal_item = set_content.parent('.x-suda-upload-box-div').clone();
                    modal_item.find('.image_show').remove();
                    modal_item.find('input').remove();
                    modal_item.find('.x-suda-upload-action').remove();
                    modal_item.find('.x-suda-upload-item').removeClass('uploadbox-filled');
                    modal_item.append('<span class="x-suda-remove-upload-item"><i class="ion-close-circle"></i></span>');
                    media_modal_group.append(modal_item);
                }
                
            }
            
            modalLayout.modal('hide');
            
        });
        
    };
    
    $('body').on('click','.x-suda-upload-item',function(e){
        
        //var csrfToken = $('input[name="_token"]').val();
        
        var csrfToken = suda.meta['csrfToken'];
        var elem = this;
        var elem_id = $(this).attr('id');
        
        var media_type  = $(elem).attr('_data_type');
        var media_crop  = $(elem).attr('_data_crop');
        var media_name  = $(elem).attr('_data_name');
        var media_max   = $(elem).attr('media_max')||1;
        
        var elemParent = $(elem).parent('.x-suda-upload-box-div');
        
        if(!media_type){
            alert('上传异常，请确认数据类型');
            return false;
        }
        
        //$(elemParent).fadeOut();
        
        const box_href = suda.link(suda.meta['.mediabox_box_url'] + media_type);
        
        $.ajax({
            type    : 'GET',
            url     : box_href,
            cache   : false,
            data: { media_name: media_name,media_max:media_max,media_type:media_type,media_crop:media_crop,_token:suda.data('csrfToken') },
            success : function(data){
               if(data){
                   $.fn.popMediaModel(data,$(elem),csrfToken);
               }
            },
            error : (function(xhr){
                if(xhr.status==422){
                    var errors = xhr.responseJSON;
                    suda.modal(errors);
                }
            }),
            fail : (function() {
                    suda.modal({error:'加载失败，请重试'});
            })
        });
        
        $('.modal-image-upload').modal('show');
    });
    
    
    $('body').on('click','.x-suda-remove-upload-item',function(e){
        
        e.preventDefault();
        
        if($(this).parents('.x-suda-upload-box').children('.x-suda-upload-box-div').length<2){
            alert('必须至少保留一个');
        }else{
            $(this).parent('.x-suda-upload-box-div').remove();
        }
        
    });
    
    $('body').on('click','.x-suda-upload-action-switch',function(e){
        e.preventDefault();
        // NOTHING TO DO
        // auto trigger
    });

    $('body').on('click','.x-suda-upload-action-delete',function(e){
        e.preventDefault();
        var upload_item_el = $(this).parents('.x-suda-upload-item');
        $(upload_item_el).removeClass('uploadbox-filled');
        $(upload_item_el).children().remove();
        return false;
    });
    
    $('body').on('click','.pop-modal',function(e){
        
        e.preventDefault();
        
        var csrfToken = suda.data('csrfToken');
        var elem = this;
        var elem_id = $(this).attr('data-modal-id');
        
        var href = $(elem).attr('href');
        
        if(!href){
            suda.modal({error:'加载异常，没有对应的数据类型'});
            return false;
        }
        
        $.ajax({
            type    : 'GET', 
            url     : href,
            cache   : false,
            data: { id: elem_id, _token:suda.data('csrfToken') },
            success : function(data){
               if(data){
                   if(data.hasOwnProperty('response_type')){
                        suda.modal(data.response_msg);
                        return false;
                   }
                   $(elem).popModal(data);
               }
            },
            error : (function(xhr){
                if(xhr.status==422){
                    var errors = xhr.responseJSON;
                    errors = errors.response_msg;
                    suda.modal(errors);
                }
            }),
            fail : (function() {
                    suda.modal({error:'加载失败，请重试'});
              })
        });
        
    });
    
    
    //定义侧滑搜索框
    $.fn.zfilter = function(){
        var page_no = 1;
        var filter_source = $('.more-filter-section');
        if($(filter_source).length>0){
            var filter_form = $(filter_source).find('form.filter-form');

            $(filter_source).find('.filter-close').on('click',function(ec){
                ec.preventDefault();
                $(filter_source).removeClass('active');    
            });

            $(filter_source).find('.filter-reset').on('click',function(er){
                er.preventDefault();
                $(filter_form)[0].reset();
            });

            //查询显示
            $(filter_source).find('.filter-submit').on('click',function(er){
                er.preventDefault();
                
                var form_data = $(filter_form).serialize() || '';

                var action_href = $(filter_form).attr('action');

                $.ajax({
                    type    : 'POST', 
                    url     : action_href,
                    cache   : false,
                    data    : {page:page_no,search:1,filter:form_data,_token:suda.data('csrfToken')},
                    success : function(data){
                        $(data_element).attr('data-filter',1);
                        $(data_element).html(data);

                        //增加相关分页
                        if($(data_element).find('.pagination').length>0){

                            var pagination = $(data_element).find('.pagination');
                            $(pagination).find('.page-link[href]').on('click',function(plink){
                                plink.preventDefault();
                                var pa = this;
                                var pnum = $(pa).text();
                                page_no = pnum;

                                $(filter_source).find('.filter-submit').trigger('click');

                            });
                        }
                    },
                    error : (function(xhr){
                        if(xhr.status == 422){
                            if(typeof xhr.responseJSON != 'undefined'){
                                var result = xhr.responseJSON;
                                if(typeof result.errors != 'undefined'){
                                    var errors = result.errors;
                                    var error_str = '';
                                    $.each(errors,function(k,v){
                                        error_str += v[0]+'<br>';
                                    });
                                    suda.modal(error_str,'fail');
                                }
                                if(typeof result.response_msg != 'undefined'){
                                    suda.modal(result.response_msg,result.response_type);
                                }
                             }
                        }else{
                            suda.modal('请求异常，请稍后重试','warning');
                        }
                    }),
                    fail : (function() {
                        suda.modal({error:'加载失败，请重试'},'warning');
                    })
                });

            });
        }
        

        var openFilter = function(){

            if($(filter_source).length<1){
                suda.modal('内容加载失败');
                return false;
            }

            $(filter_source).css('height',$(window).height()-$('nav.navbar').height());
            $(filter_source).addClass('active');
        };

        var closeFilter = function(){
            $(filter_source).removeClass('active');
        };

        var el = this;
        var data_element = null;
        data_element = $(el).attr('data-element');

        $(el).on('click',function(e){
            e.preventDefault();
            openFilter();
        });
        
    };
    
    $('body').find('.view_icon').on('click',function(e){
        e.preventDefault();
        var query_str='';
        if($('body').find('#filter_str').length>0){
            if($('body').find('#filter_str').val()!='' && $('body').find('#filter_str').val()!=undefined){
                query_str = '?'+$('body').find('#filter_str').val();
            }
        }
        
        
        window.location.href = $(this).attr('href')+query_str;
        
    });
    
    
    
    var inlineEdit = function(event,el){
        event.preventDefault();
        
        var trigger = $(el);
        var editElem = trigger.prev('span.inedit');
        var editBock = $(el).parent('.inline-edit-block');
        
        var saveUrl = $(el).attr('href');
        
        $(el).hide();
        $(editElem).hide();
        
        var editValue = editElem.attr('edit-value');
        var editID = editElem.attr('edit-id');
        
        var editHtml = "<input type='text' name='inedit' value='"+editValue+"'>";
        
        var editHtml = '<div class="input-group input-group-sm col-sm-12 inedit-active px-0">'+
                      '<input type="text" class="form-control" name="inedit" placeholder="请填写数字" value="'+editValue+'">'+
                      '<div class="input-group-append">'+
                        '<button class="btn btn-primary btn-sm btn-save" type="button">保存</button>'+
                      '</div>'+
                        '</div>';
        $(editBock).append(editHtml);
        
        $(editBock).find('.btn-save').on('click',function(e){
            
            var editValue = $(editBock).find('input[name^=inedit]').val();
            
            $.ajax({
            
                type    : 'POST', 
                url     : saveUrl,
                cache   : false,
                data: {_token:suda.data('csrfToken'),inedit_id:editID,inedit_value:editValue},
                success : function(data){
                    
                    $(editBock).find('.inedit-active').fadeOut(300,function(){
                        $(editBock).find('.inedit-active').remove();
                        $(el).show();
                        $(editElem).html(editValue).show();
                    });
                    
                    suda.alert(data.response_msg);

                    if(data.response_url=='self.refresh'){
                        var timesRun = 0;
                         var interval = setInterval(function(){
                             timesRun += 1;
                             if(timesRun === 3){
                                 clearInterval(interval);
                             }
                         
                             window.location.reload(); 
                         
                         }, 2000);
                         return;
                     }
                },
                error : (function(xhr){
                    var errors = xhr.responseJSON;
                    var error = errors.response_msg
                    if(!errors.response_msg){
                        error = '提交异常，请稍后重试';
                    }
                    suda.modal({error:error});
                    return;
                }),
                fail : (function() {
                    suda.modal({error:'加载失败，请重试'});
                    return;
                })
            
            });
            
        });
    };
    
    $('body').on('click','.inline-edit',function(e){
        inlineEdit(e,this);
    });
    
    
    // $('.switch-language').on('hidden.bs.dropdown', function (e) {
    //     console.log(e);
    // });

    

    function formatCategoryChild (item) {
        if (!item.id) {
            return item.text;
        }
        var data_child = $(item.element).attr('data-child');
        var symbolstr = '&#x2500;';
        if(data_child>0)
        {
            var $item = $(
                '<span><span style="color:#999;">&#x251C;'+symbolstr.repeat(data_child)+'</span><span>'+item.text+'</span></span>'
            );
        }else{
            var $item = $(
                '<span><span style="font-weight:bold;">'+item.text+'</span></span>'
            );
        }
        return $item;
    };

    $.fn.selectCategory = function(){

        var el = this;
        var containerCssClass = '';
        var dropdownCssClass = '';
        if($(el).hasClass('form-control-sm'))
        {
            containerCssClass = 'select2--small';
            dropdownCssClass = 'select2--small';
        }
        if($(el).hasClass('form-control-lg'))
        {
            containerCssClass = 'select2--large';
            dropdownCssClass = 'select2--large';
        }

        var dropdownParent = $('body');
        var selectType = 'single';
        if($(el).attr('multiple'))
        {
            selectType = 'multiple';
        }
        var placeholder = $(el).attr('placeholder')?$(el).attr('placeholder'):'- 选择分类 -';

        if(selectType=='single')
        {
            $(el).select2({
                theme: 'bootstrap-5',
                templateResult: formatCategoryChild,
                placeholder: placeholder,
                selectionCssClass: containerCssClass,//select2-selection-lg
                dropdownCssClass: dropdownCssClass,//select2-selection-lg
                dropdownParent: dropdownParent
            });
        }

        if(selectType=='multiple')
        {
            $(el).select2({
                theme: 'bootstrap-5',
                templateResult: formatCategoryChild,
                placeholder: placeholder,
                selectionCssClass: containerCssClass,//select2-selection-lg
                dropdownCssClass: dropdownCssClass,//select2-selection-lg
                dropdownParent: dropdownParent
            });
        }
        

    };

    
    
    

    $.fn.selectTag = function(){

        var el = this;

        var tag_settings = {
            taxonomy: $(el).attr('data-taxonomy'),
            maximumSelectionLength: $(el).attr('data-max'),
            url: $(el).attr('data-href'),
            method: 'GET',
            tags: true,
            placeholder: $(el).attr('placeholder'),
            dropdownParent: $('body')
        };

        // var tag_settings = $.extend({}, default_options,options);
        
        var containerCssClass = '';
        var dropdownCssClass = '';
        var dropdownParent = tag_settings.dropdownParent;
        if($(el).hasClass('form-control-sm'))
        {
            containerCssClass = 'select2--small';
            dropdownCssClass = 'select2--small';
        }
        if($(el).hasClass('form-control-lg'))
        {
            containerCssClass = 'select2--large';
            dropdownCssClass = 'select2--large';
        }

        if(!dropdownParent)
        {
            dropdownParent = $(el).parent();
        }

        var select2_options = {
            maximumSelectionLength: tag_settings.maximumSelectionLength,
            tags: tag_settings.tags,
            placeholder: tag_settings.placeholder,
            theme: 'bootstrap-5',
            containerCssClass: containerCssClass,
            selectionCssClass: containerCssClass,
            dropdownCssClass: dropdownCssClass,
            dropdownParent: dropdownParent,
            language: "zh-CN",
            ajax: {
                url: tag_settings.url,
                dataType: 'json',
                method: tag_settings.method,
                delay: 250,
                data: function (params) {
                    var query = {
                        taxonomy: tag_settings.taxonomy,
                        name: params.term,
                    }
                    return query;
                },
                processResults: function (data) {
                    return {
                      results: data.tags
                    };
                }
            }
        };

        $(el).select2(select2_options);

    };

    $.fn.suda_distpicker = function(options)
    {
        var default_options = {
            url: suda.link(suda.data('adminPath')+'/areadata/json')
        };
        var doptions = $.extend({},default_options,options);
        var el = this;
        $.ajax({
            type: 'GET',
            url: doptions.url,
            cache: false,
            dataType: 'json',
            success(rsdata) {
                if (rsdata) {
                    doptions.districtdata = rsdata.districts;
                    $(el).distpicker(doptions);
                }
            },
            error() {
                if (xhr.status == 422) {
                  const errors = xhr.responseJSON;
                  alert(errors);
                }
            },
            fail() {
                alert('地区数据加载失败');
            },
        });
        
    };


    $.extend({
        mediabox: function(options){
            var default_options = {
                box_url: '',
                modal_url: '',
                upload_url: '',
                remove_url: '',
            };
    
            var settiings = $.extend({}, default_options,options);
            
            suda.addMeta('.mediabox_box_url',settiings.box_url+'/');
            suda.addMeta('.mediabox_modal_url',settiings.modal_url+'/');
            suda.addMeta('.mediabox_upload_url',settiings.upload_url+'/');
            suda.addMeta('.mediabox_remove_url',settiings.remove_url+'/');
        }
    });

});
