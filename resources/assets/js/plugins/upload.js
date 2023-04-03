jQuery(function(){
    
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
    
});