jQuery(function(){
    
    var sendEditorFile = function(send_url,file,editor){
        
        data = new FormData();
        data.append("img", file);
        data.append("_token",suda.data('csrfToken'));
        data.append("media_type", 'upload');
        
        $.ajax({
            data: data,
            type: "POST",
            url: send_url,
            cache: false,
            contentType: false,
            processData: false,
            success: function(response) {
                //response = JSON.parse(response);
                //xy_clog
                //console.log(response);
                editor.summernote('insertImage', response.url,response.name);
            }
        });
    }
    
    function CleanPastedHTML(input) {
        // 1. remove line breaks
        //var stringStripper = /(\n|\r| class=(")?Mso[a-zA-Z]+(")?)/g;
        //var output = input.replace(stringStripper, ' ');
        var output = input;
        // 2. strip Word generated HTML comments
        var commentSripper = new RegExp('<!--(.*?)-->','g');
        var output = output.replace(commentSripper, '');
        
        // 3. remove tags leave content if any
        var tagStripper = new RegExp('<(/)*(meta|link|span|\\?xml:|st1:|o:|font)(.*?)>','gi');
        output = output.replace(tagStripper, '');
        
        // 4. Remove everything in between and including tags '<style(.)style(.)>'
        var badTags = ['style', 'script','applet','embed','noframes','noscript'];
        for (var i=0; i< badTags.length; i++) {
            tagStripper = new RegExp('<'+badTags[i]+'.*?'+badTags[i]+'(.*?)>', 'gi');
            output = output.replace(tagStripper, '');
        }
        
        // 5. remove attributes ' style="..."'
        var badAttributes = ['style', 'start'];
        for (var i=0; i< badAttributes.length; i++) {
            var attributeStripper = new RegExp(' ' + badAttributes[i] + '="(.*?)"','gi');
            output = output.replace(attributeStripper, '');
        }
        return output;
    }
    
    $.fn.sudaEditor = function(elem){
        if($(elem)){

            $(elem).summernote({
                lang: 'zh-CN',
                height: suda.editor_height,
                minHeight: null,
                maxHeight: null,
                focus: true,
                shortcuts: false,
                disableDragAndDrop: false,
                dialogsFade: true,
                placeholder:"请输入内容...",
                toolbar: [
                    // ['cleaner',['cleaner']],
                    ['style',['style']],
                    ['font', ['bold', 'italic', 'underline','strikethrough','clear']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph','hr']],
                    ['insert',['table','link','mediaPlugin','video']],
                    // ['custom', ['mediaPlugin']],
                    ['misc',['codeview','fullscreen']]
                ],
                cleaner:{
                    action: 'both', // both|button|paste 'button' only cleans via toolbar button, 'paste' only clean when pasting content, both does both options.
                    newline: '<br>', // Summernote's default is to use '<p><br></p>'
                    notStyle: 'position:absolute;top:0;left:0;right:0', // Position of Notification
                    icon: '<i class="note-icon-eraser"></i>',
                    keepHtml: false, // Remove all Html formats
                    keepOnlyTags: ['<p>', '<br>', '<ul>', '<li>', '<b>', '<strong>','<i>', '<a>'], // If keepHtml is true, remove all tags except these
                    keepClasses: false, // Remove Classes
                    badTags: ['style', 'script', 'applet', 'embed', 'noframes', 'noscript', 'html'], // Remove full tags with contents
                    badAttributes: ['style', 'start'], // Remove attributes from remaining tags
                    limitChars: false, // 0/false|# 0/false disables option
                    limitDisplay: 'both', // text|html|both
                    limitStop: false // true/false
                },
                callbacks: {
                    onImageUpload: function(files) {
                        const upload_url = suda.link(window.suda.meta['.mediabox_upload_url']+'editor');
                        sendEditorFile(upload_url,files[0],$(elem));                
                    },
                    // onPaste: function(e) {
                    //     var thisNote = $(this);
                    //     var updatePastedText = function(){
                    //         var original = $(elem).summernote('code');
                    //         var cleaned = CleanPastedHTML(buffer);
                    //         $(elem).summernote('insertText',cleaned);
                    //     };
                    //     setTimeout(function () {
                    //         updatePastedText(thisNote);
                    //     }, 10);
                    // },
                    onKeydown: function(e)
                    {
                        //$(elem).summernote('saveRange');
                    }
                },
                popover: {
                  image: [
                    // ['custom', ['imageShapes']],
                    // ['custom', ['captionIt']],
                    ['image', ['resizeFull', 'resizeHalf', 'resizeQuarter', 'resizeNone']],
                    ['float', ['floatLeft', 'floatRight', 'floatNone']],
                    ['remove', ['removeMedia']]
                  ],
                //   captionIt:{
                //     figureClass:'{figure-class/es}',
                //     figcaptionClass:'{figcapture-class/es}',
                //     captionText:'{图片描述}'
                //   }
                }
            });
            
        }
    }

    
});
    