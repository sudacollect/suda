(function (factory) {
  /* Global define */
  if (typeof define === 'function' && define.amd) {
    // AMD. Register as an anonymous module.
    define(['jquery'], factory);
  } else if (typeof module === 'object' && module.exports) {
    // Node/CommonJS
    module.exports = factory(require('jquery'));
  } else {
    // Browser globals
    factory(window.jQuery);
  }
}(function ($) {
    $.extend(true,$.summernote.lang, {
        'zh-CN': {
              mediaPlugin: {
                mediaText: '图片',
                dialogTitle: '选取图片文件',
                okButton: '确认',
                tooltip : '选取图片',
              }
        },
        'en-US': {
          mediaPlugin: {
            mediaText: 'Image',
            dialogTitle: 'Select Image',
            okButton: 'Confirm',
            tooltip : 'Select Image',
          }
        }
    });
    

    
    
    $.extend($.summernote.options, {
      mediaPlugin: {
        icon: '<i class="note-icon-picture"/>',
        tooltip: 'Image',
      },

    });
        
        
    $.extend($.summernote.plugins, {
        /**
         *  @param {Object} context - context object has status of editor.
         */
        'mediaPlugin':function (context) {
            
            var self      = this,

           // ui has renders to build ui elements
           // for e.g. you can create a button with 'ui.button'
            ui        = $.summernote.ui,
            $note     = context.layoutInfo.note,

            // contentEditable element
            $editor   = context.layoutInfo.editor,
            $editable = context.layoutInfo.editable,
            $toolbar  = context.layoutInfo.toolbar,
          
            // options holds the Options Information from Summernote and what we extended above.
            options   = context.options,
          
            // lang holds the Language Information from Summernote and what we extended above.
            lang      = options.langInfo;
            
            
            context.memo('button.mediaPlugin', function () {

                    // Here we create a button
                    var button = ui.button({

                      // icon for button
                      contents: options.mediaPlugin.icon,

                      // tooltip for button
                      tooltip: lang.mediaPlugin.tooltip,
                      click:function (e) {
                        context.invoke('mediaPlugin.show');
                      }
                    });
                    return button.render();
            });
            
            
            this.initialize = function() {

                    // This is how we can add a Modal Dialog to allow users to interact with the Plugin.

                    // get the correct container for the plugin how it's attached to the document DOM.
                    var $container = options.dialogsInBody ? $(document.body) : $editor;
                    
                    this.$dialog = '';
                    
                    
                  
                  //移除
                  this.destroy = function () {
                      ui.hideDialog(this.$dialog);
                      this.$dialog.remove();
                  };
                  
                  
                  this.bindEnterKey = function ($input, $btn) {
                    $input.on('keypress', function (event) {
                      if (event.keyCode === 13) $btn.trigger('click');
                    });
                  };
                  this.bindLabels = function () {
                    self.$dialog.find('.form-control:first').focus().select();
                    self.$dialog.find('label').on('click', function () {
                      $(this).parent().find('.form-control:first').focus();
                    });
                  };
                  
                  this.show = function () {
                        var $img = $($editable.data('target'));
                        var editorInfo = {

                        };
                        
                        this.showmediaPluginDialog(editorInfo).then(function (editorInfo) {
                            $note.val(context.invoke('code'));
                            $note.change();
                        });
                        
                      };
                  
                  this.showmediaPluginDialog = function(editorInfo) {
                        return $.Deferred(function (deferred) {
                            
                          
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
                                              if($.summernote.options.lang == 'zh-CN')
                                              {
                                                suda.modal('请至少选择一张图片');
                                              }else{
                                                suda.modal('0 selected');
                                              }
                                               
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
                                                   img.alt = $(tt).find('img').attr('title');
                                                   img.title = $(tt).find('img').attr('title');
                                                   img.style = "width:25%";
                                                   $(newDiv).append(img);
                                                   context.invoke('editor.insertNode', newDiv);
                                               }else{
                                                    img.alt = $(tt).find('img').attr('title');
                                                    img.title = $(tt).find('img').attr('title');
                                                    img.style = "width:50%";
                                                    context.invoke('editor.insertNode', img);
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
                        
                            self.$dialog = $(document).find('.modal-image-upload');
                            
                            
                            ui.onDialogHidden(self.$dialog, function () {
                                if (deferred.state() === 'pending') deferred.reject();
                            });
                                    
                        });
                      };
                  
                };
            
            
        }
       
        
        
        
    });
        
      
}))