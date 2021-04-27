jQuery(document).ready(function(){
    
    var upload_url = suda.link(suda.data('adminPath')+'/medias/upload/image');
    var remove_url = suda.link(suda.data('adminPath')+'/medias/remove/image');
    
    $('.s-upload').zpuploadInit({
        action:upload_url,
        removeUrl:remove_url,
        label:'<span><i class="fa fa-upload"></i>&nbsp;上传文件</span>',
        leave:'有文件正在上传，确定退出么?',
        maxQueue:1,
        maxFiles:false,
        multiple:false,
        delete:false,
        postKey:'img'
    });
    
    $('.s-upload .filelist.complete li .file').on('mouseenter',function(e){
        
        var ele = this;
        if($(ele).length>0){
            $(ele).append("<div class='delete_image_show'><i class='far fa-times-circle'></i>&nbsp;删除文件</div>");
            $(ele).find('.delete_image_show').on('click',function(){
                // $(ele).parent('li').remove();
                $(ele).parent('li').animate({
                    opacity: 0,
                    top:-100,
                }, 500, function() {
                    $(ele).parent('li').remove();
                });
            });
        }
        
    });
    
    $('.s-upload .filelist.complete li .file').on('mouseleave',function(e){
        if($(this).find('.delete_image_show')){
            $(this).find('.delete_image_show').remove();
        }
    });
});