jQuery(document).ready(function(){
    'use strict';
    // Change this to the location of your server-side upload handler:
    var url = suda.link('/'+suda.data('adminPath')+'/profile/upload/avatar');
    $('#fileupload').fileupload({
        url: url,
        dataType: 'json',
        formData:{_token:suda.data('csrfToken'),'media_type':$('#fileupload').attr('media_type')},
        done: function (e, data) {
            var xhr = data.jqXHR;
            var media = xhr.responseJSON;
            var avatar = $('#fileupload').parent('.fileinput-avatar');
            
            if(media.image){
                if(avatar.find('.zpress-image').length>0){
                    avatar.find('.zpress-image').remove();
                }
                
                avatar.append(media.image);
            }else{
                suda.modal('上传失败,请重试');
            }
            
        },
        progressall: function (e, data) {
            var progress = parseInt(data.loaded / data.total * 100, 10);
            $('#progress .progress-bar').css(
                'width',
                progress + '%'
            );
        },
        fail:function(e,data){
            var xhr = data.jqXHR;
            if(xhr.status == 413){
                suda.modal('上传文件过大，请重新上传');
            }
            if(xhr.status == 422 || xhr.status == 405){
                if(xhr.responseJSON){
                    var errors = xhr.responseJSON;
                    errors = errors.response_msg?errors.response_msg:'请求异常,请稍后重试';
                }else{
                    var errors = '请求异常,请稍后重试';
                }
                
                suda.modal(errors);
            }else{
                suda.modal('请求异常,请稍后重试');
            }
        }
    }).prop('disabled', !$.support.fileInput)
        .parent().addClass($.support.fileInput ? undefined : 'disabled');
    
});