
@php
    $height = !isset($height)?300:intval($height);
    $content = !isset($content)?'':$content;
    $name = !isset($name)?'content':$name;
    $default_place = __('suda_lang::press.pages.content_tips');
    $placeholder = !isset($placeholder)?$default_place:$placeholder;
    $lang = config('app.locale')=='zh_CN'?'zh-CN':'en-US';
@endphp

<input id="x" type="hidden" name="{{ $name }}" value="{{ $editor_content }}">
<trix-editor input="x" placeholder="{{ $placeholder }}" class="trix-content"></trix-editor>

@push('styles-head')
<link rel="stylesheet" type="text/css" href="{{ suda_asset('trix/trix.css') }}">
@endpush


@push('scripts')

@once
<script type="text/javascript" src="{{ suda_asset('trix/trix.umd.min.js') }}"></script>
@endonce

<script type="text/javascript">

$(function(){
    $.mediabox({
        box_url: "{{ admin_url('medias/load-modal/') }}",
        modal_url: "{{ admin_url('medias/modal/') }}",
        upload_url: "{{ admin_url('medias/upload/image/') }}",
        remove_url: "{{ admin_url('medias/remove/image/') }}"
    });
    
    var element = document.querySelector("trix-editor");
    document.addEventListener("trix-media-accept", (e) => {
        e.preventDefault();
        // Change Trix.config if you need
        
        var href = suda.link(suda.meta['.mediabox_box_url'] + 'editor');

        $.ajax({
            type    : 'GET', 
            url     : href,
            cache   : false,
            data: { media_name: 'editor',media_max:10, _token:suda.data('csrfToken') },
            success : function(data){
                if(data){
                    $.fn.popMediaModel(data,'made-by-suda',suda.data('csrfToken'));
                    
                    
                    var modalLayout = $('.modal-layout');
                    //保存内容
                    modalLayout.find('#btn-save').on('click',function(e){
                        
                        e.preventDefault();
                        
                        var modalBody = modalLayout.find('.modal-body:visible');

                        var files = modalLayout.find('.media-lists li:has("div.checked")');

                        if(files.length<1){
                            suda.modal('请至少选择一张图片');
                            return false;
                        }

                        files.each(function(index,e){
                            var tt = $(e).find('img').parent().clone();
                            $(tt).find('div.checked').remove();
                            $(tt).find('input').remove();
                            
                            var img = new Image();
                            img.src = $(tt).find('img').attr('data-src');
                            if($(tt).find('img').attr('file')=='file'){
                                img.src = $(tt).find('img').attr('src');
                                var newDiv = document.createElement("a");
                                $(newDiv).attr('href',$(tt).find('img').attr('data-src'));
                                $(newDiv).attr('target','_blank');
                                img.alt = $(tt).find('img').attr('title');
                                img.title = $(tt).find('img').attr('title');
                                img.style = "max-width:150px";
                                $(newDiv).append(img);
                                let tmp = document.createElement('div');
                                tmp.appendChild(newDiv);
                                // element.editor.insertHTML(tmp.innerHTML);
                                var attachment = new Trix.Attachment({
                                    content: tmp.innerHTML,
                                })
                                attachment.setAttributes({
                                    contentType:$(tt).find('img').attr('data-type').toLowerCase(),
                                    filename: $(tt).find('img').attr('title'),
                                    previewable: true,
                                    caption: {
                                        name: $(tt).find('img').attr('title'),
                                        size:0
                                    }
                                })
                                element.editor.insertAttachment(attachment)

                            }else{
                                img.alt = $(tt).find('img').attr('title');
                                img.title = $(tt).find('img').attr('title');
                                let tmp = document.createElement('div');
                                tmp.appendChild(img);
                                // element.editor.insertHTML(tmp.innerHTML);

                                var attachment = new Trix.Attachment({
                                    file: img.src,
                                    url: img.src,
                                })
                                
                                attachment.setAttributes({
                                    contentType:'image/'+$(tt).find('img').attr('data-type').toLowerCase(),
                                    filename: $(tt).find('img').attr('title'),
                                    previewable: true,
                                    mutable: true,
                                    preview: {
                                        presentation: "gallery",
                                        caption: {
                                        name: true,
                                        size: true,
                                        },
                                    },
                                    caption: {
                                        name: $(tt).find('img').attr('title'),
                                        size:0
                                    }
                                })
                                element.editor.insertAttachment(attachment)
                                
                            }    
                        });
                        
                        modalLayout.modal('hide');

                    });
                }
            },
            error : (function(xhr){
                if(xhr.status==422){
                    var errors = xhr.responseJSON;
                    suda.modal(errors);
                }
            }),
            fail : (function() {
                suda.modal({error:'Loading...'});
            })
        });
    })
});
    
</script>


@endpush