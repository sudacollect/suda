$(document).ready(function($) {
    
    window.suda = window.suda || {};
    
    suda.data = function(definition, defaultVal, set) {
       if (defaultVal === null){
           defaultVal = definition;
       }
       
       if (!(definition in suda.meta) && defaultVal!=undefined) {
           return defaultVal;
       }
       
       if (set && defaultVal!=undefined) {
           suda.meta[definition] = defaultVal;
       }
       return suda.meta[definition];
    };

    suda.addMeta = function(key,value){
        suda.meta[key]  = value;
    }

    suda.link = function(path) {
       if (path.indexOf("//") >= 0){
           return path;
       }
       var urlFormat = suda.data("UrlFormat", "/{Path}");
       
       if (path.substr(0, 1) == "/"){
           path = path.substr(1);
       }
       if (urlFormat.indexOf("?") >= 0){
           path = path.replace("?", "&");
       }
       
       return urlFormat.replace("{Path}", path);
    };

    suda.disable = function(e, progressClass) {
       var href = $(e).attr('href');
       if (href) {
          $.data(e, 'hrefBak', href);
       }
       $(e).addClass(progressClass ? progressClass : 'InProgress').removeAttr('href').attr('disabled', true);
    };
    
    suda.enable = function(e) {
       $(e).attr('disabled', false).removeClass('InProgress');
       var href = $.data(e, 'hrefBak');
       if (href) {
          $(e).attr('href', href);
          $.removeData(e, 'hrefBak');
       }
    };
    
    suda.infobox = function(info){
        var errors = '';
        if($.isArray(info)){
            $.each(info,function(index,e){
                errors += index+":"+e+'<br>';
            });
        }else{
            errors = info;
        }
        window.confirm(errors);
    };
    
    suda.alert = function(info,infoType){
        
        var errors = '';
        if ($.isArray(info)) {
            $.each(info, function (index, e) {
                errors += index + ":" + e + '<br>';
            });
        } else {
            errors = info;
        }
        
        if(infoType==undefined){
            infoType = 'dark';
        }
        
        // var alerthtml = '<div class="modal-alert"><div class="alert alert-'+infoType+'">'+errors+'</div></div>';
        // $('body').find('.modal-alert').remove();
        // $('body').append(alerthtml);
        // $('body').find('.suda-toast').find('.toast-body').text(errors);
        var suda_flat_width = $('body').find('.suda-flat').width();
        var boxa_right = (suda_flat_width-180)/2;

        var boxa_top = $(window).scrollTop()+15;
        
        var alerthtml = '<div class="suda-toast toast" data-autohide="true" data-delay="2500" style="position: absolute; z-index:9999;top:'+boxa_top+'px; right:'+ boxa_right +'px;width:180px;">';
            alerthtml += '<div class="toast-header text-'+infoType+'" style="background:#efefef;">';
            alerthtml += '<i class="ion-alert-circle"></i>&nbsp;';
            alerthtml += '<strong class="mr-auto">提示</strong>';
            alerthtml += '<button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
            alerthtml += '</div>';
            alerthtml += '<div class="toast-body">'+errors+'</div>';
            alerthtml += '</div>';
        $('body').find('.suda-toast').remove();
        $('body').append(alerthtml);
        $('body').find('.suda-toast').toast('show');
    }
    
    suda.modal = function(info,infoType,autoClose,rurl,btn){
        $.ajax({
            type    : 'GET', 
            url     : '/sdone/error/ajax',
            cache   : false,
            success : function(data){
               if(data){
                   $('body').append($(data));
                   var errors = '';
                   if ($.isArray(info)){
                       $.each(info,function(index,e){
                           errors += index+":"+e+'<br>';
                       });
                   } else if (typeof(info)=='object'){
                       errors = info.error;
                   } else {
                       errors = info;
                   }
                   
                   if(infoType != undefined){
                       $('.errormodal').addClass('errormodal-'+infoType);
                   }
                   
                   $('.errormodal').find('.modal-body').append(errors);
                   
                   //if btn
                   if(btn != undefined){
                       $('.errormodal').find('.modal-footer').prepend(btn);
                   }
                   
                   $('.errormodal').modal('show');
                   $('.errormodal').on('hidden.bs.modal',function(e){
                       $('.errormodal').remove();
                   });
                   
                   if(autoClose && autoClose>0){
                       var timesRun = 0;
                       var interval = setInterval(function(){
                           timesRun += 1;
                           if(timesRun === autoClose){
                               clearInterval(interval);
                           }
                           $('.errormodal').modal('hide');
                       }, 3000);
                   }
                   
                   if(rurl != undefined && rurl!=''){
                       window.location.href = rurl;
                   }
                   
               }
            }
            
        });
    };
    
    $.fn.zpupload = function(options){
        
        var configs = {
            action:'',
            maxSize:52428800,
            label:'拖拽 或 点击上传',
            leave:'有图片正在上传，确定退出么',
            maxQueue:1,
            maxFiles:false,
            multiple:false,
            maxConcurrent:1,
            postData:{},
            delete:true,
            onComplete:zpup.onFileComplete,
            onStart:zpup.onStart,
            onFileStart:zpup.onFileStart,
            onFileProgress:zpup.onFileProgress,
            onFileError:zpup.onFileError,
            onQueued:zpup.onQueued,
            postKey:'file'
        };
        
        var settings = $.extend({}, configs,options);
        
        
        var elem = this;
        var $elem = $(elem);
        zpup.options = settings;
        $elem.upload({
            action:settings.action,
            maxSize:settings.maxSize,
            beforeSend:zpup.onBeforeSend,
            label:settings.label,
            leave:settings.leave,
            maxQueue:settings.maxQueue,
            maxFiles:settings.maxFiles,
            multiple:settings.multiple,
            maxConcurrent:settings.maxConcurrent,
            postData:settings.postData,
            postKey:settings.postKey,
        }).on("start.upload", settings.onStart)
    	.on("filestart.upload", settings.onFileStart)
    	.on("fileprogress.upload", settings.onFileProgress)
    	.on("filecomplete.upload", settings.onComplete)
    	.on("fileerror.upload", settings.onFileError)
    	.on("queued.upload", settings.onQueued);
        
        
        if($elem.find(".filelist.complete li").length == $elem.attr('media_max')){
            $elem.find('.fs-upload-target').hide();
        }
        
        if(settings.delete==true){
            $elem.find(".filelist.complete li.data").find('.delete').on('click',function(e){
                
                var $media = $(this);
                var href= settings.removeUrl;
                var media_id = $media.attr('media_id');
                var data_id = $media.attr('data_id');
                
                suda.disable(e);
                e.stopPropagation();
                
                $.ajax({
                   type: "POST",
                   url: href,
                   data: { UploadID:data_id,MediaID:media_id },
                   dataType: 'json',
                   error: function(xhr) {
                      suda.modal(xhr);
                   },
                   success: function(json) {
                      if (json == null) json = {};
                      var informed = suda.modal(json);
                      suda.enable($media);
                      $media.parent('li').fadeOut(500);
                   }
                });
            
            });
        }
    };
    
    //更有效率的图片上传初始化
    $.fn.zpuploadInit = function(options){
        
        var configs = {
            label:'上传图片',
            leave:'有图片正在上传，确定退出么?',
            maxQueue:1,
            delete:false,
            postKey:'img',
            onComplete:zpup.zupload_complete
        };
        
        var settings = $.extend({}, configs,options);
        
        var elem = this;
        var upload_url = options.action;
        var remove_url = options.removeUrl;
        var post_data = {_token:suda.data('csrfToken')};
        var media_type = $(elem).attr('media_type');
        
        if(media_type!='' && media_type!=undefined){
            post_data.media_type = media_type;
            upload_url += "/"+media_type;
        }
        
        settings.action = upload_url;
        settings.removeUrl = remove_url;
        settings.postData = post_data;
        
        $(elem).zpupload(settings);
        
    };
    
    var zpup = {};
    
    zpup.options = {};
    
    zpup.zupload_complete = function (e, file, response) {
        
        var media_max = $(this).attr('media_max')||1;
        var media_box = this;
        
        response = $.parseJSON(response);
    	if (response.error) {
    		suda.modal(response.error);
    	} else {
    		var $target = $(this).find(".filelist.queue").find("li[data-index=" + file.index + "]");
        
            //insert page
            $image_html = response.image;
            $image_html += "<input type='hidden' name='images["+response.media_id+"]' value='"+response.media_id+"'>";
            
        
    		$target.find(".file").html($image_html);
    		$target.find(".progress").remove();
    		$target.find(".cancel").remove();
    		$target.appendTo($(this).find(".filelist.complete"));
            
            $('.s-upload .filelist.complete li .file').on('mouseenter',function(e){
        
                var ele = this;
                if($(ele).length>0){
                    if($(ele).find('.delete_image_show').length<1){
                        $(ele).append("<div class='delete_image_show'><i class='far fa-times-circle'></i>&nbsp;删除</div>");
                        $(ele).find('.delete_image_show').on('click',function(){
                        
                            var href = zpup.options.removeUrl;
                            $.ajax({
                               type: "POST",
                               url: href,
                               data: { _token:suda.data('csrfToken'),media_id:$(ele).find('input[name^="images["]').val() },
                               dataType: 'json',
                               error: function(xhr) {
                                   var res = xhr.responseJSON;
                                   if(res.response_msg){
                                       suda.modal(res.response_msg);
                                   }else{
                                       suda.modal('请求出错，请重试');
                                   }
                               
                               },
                               success: function(json) {
                               
                                  if (json == null){
                                      json = {};
                                      suda.modal('请求出错，请重试');
                                  }
                              
                                  var msg = json.response_msg;
                                  if(msg){
                                      suda.modal(msg);
                                  
                                      $(ele).parent('li').animate({
                                          opacity: 0,
                                          top:-100,
                                      }, 500, function() {
                                          $(ele).parent('li').remove();
                                          
                                          if(($(ele).length-1)<media_max){
                                              $(media_box).find('.fs-upload-target').show();
                                          }
                                      });
                                      
                                      
                                  
                                  }else{
                                      suda.modal('请求出错，请重试');
                                  }
                              
                               }
                            });
                        
                        
                        });
                    }
                }
        
            });
            
            
            var media_max = $(this).attr('media_max')||1;
            var media_length = $(this).find(".filelist.complete").find('li').length;
            
            if(media_length==media_max || media_length>media_max){
                $(this).find('.fs-upload-target').hide();
            }else{
                $(this).find('.fs-upload-target').show();
            }
    
            $('.s-upload .filelist.complete li .file').on('mouseleave',function(e){
                if($(this).find('.delete_image_show')){
                    $(this).find('.delete_image_show').remove();
                }
            });
            
    	}
    };
    
    zpup.onCancel = function(e) {
    	var index = $(this).parents("li").data("index");
    	$(this).find(".upload").upload("abort", parseInt(index, 10));
    }

    zpup.onBeforeSend = function(formData, file) {
        formData.append("media_file", "media_value");
        return formData;
        
        // var media_max = $(this).attr('media_max')||1;
//         var media_length = $(this).find(".filelist.complete").find('li').length;
//
//         if(media_length < media_max){
//             formData.append("media_file", "media_value");
//             return formData;
//         }else{
//             suda.modal({error:'上传数量超过允许的上传总量'});
//             return false;
//
//         }
    }

    zpup.onQueued = function (e, files) {
    	var html = '';
    	for (var i = 0; i < files.length; i++) {
    		html += '<li data-index="' + files[i].index + '"><div class="file">' + files[i].name + '</div><span class="progress">Queued</span><span class="cancel">取消</span></li>';
    	}
    	$(this).find(".filelist.queue").append(html);
    }

    zpup.onStart = function (e, files) {
        $(this).find(".filelist.queue").find("li").find(".progress").text("Waiting");
    }

    zpup.onFileStart = function (e, file) {
    	$(this).find(".filelist.queue").find("li[data-index=" + file.index + "]").find(".progress").text("0%");
    }

    zpup.onFileProgress = function (e, file, percent) {
    	$(this).find(".filelist.queue").find("li[data-index=" + file.index + "]").find(".progress").text(percent + "%");
    }

    zpup.onFileComplete = function (e, file, response) {
        response = $.parseJSON(response);
    	if (response.error) {
    		$(this).find(".filelist.queue").find("li[data-index=" + file.index + "]").addClass("error").find(".progress").text(response.error);
    	} else {
    		var $target = $(this).find(".filelist.queue").find("li[data-index=" + file.index + "]");
    		$target.find(".file").html(response.image);
    		$target.find(".progress").remove();
    		$target.find(".cancel").remove();
    		$target.appendTo($(this).find(".filelist.complete"));
    	}
    }

    zpup.onFileError = function (e, file, response) {
    	$(this).find(".filelist.queue").find("li[data-index=" + file.index + "]").addClass("error").find(".progress").text("Error: " + response);
    }
    
    
});
