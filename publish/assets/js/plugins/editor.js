!function(e){var t={};function a(o){if(t[o])return t[o].exports;var n=t[o]={i:o,l:!1,exports:{}};return e[o].call(n.exports,n,n.exports,a),n.l=!0,n.exports}a.m=e,a.c=t,a.d=function(e,t,o){a.o(e,t)||Object.defineProperty(e,t,{enumerable:!0,get:o})},a.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},a.t=function(e,t){if(1&t&&(e=a(e)),8&t)return e;if(4&t&&"object"==typeof e&&e&&e.__esModule)return e;var o=Object.create(null);if(a.r(o),Object.defineProperty(o,"default",{enumerable:!0,value:e}),2&t&&"string"!=typeof e)for(var n in e)a.d(o,n,function(t){return e[t]}.bind(null,n));return o},a.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return a.d(t,"a",t),t},a.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},a.p="/",a(a.s=188)}({188:function(e,t,a){e.exports=a(189)},189:function(e,t){jQuery(document).ready((function(){$.fn.zeditor=function(e,t,a,o){$(e)&&($(e).summernote({lang:"zh-CN",height:suda.editor_height,load_url:t,minHeight:null,maxHeight:null,focus:!0,shortcuts:!1,disableDragAndDrop:!1,dialogsFade:!0,placeholder:"请输入内容...",toolbar:[["style",["style"]],["font",["bold","italic","underline","strikethrough","clear"]],["color",["color"]],["para",["ul","ol","paragraph","hr"]],["insert",["table","link","mediaPlugin","video"]],["misc",["codeview","fullscreen"]]],cleaner:{action:"both",newline:"<br>",notStyle:"position:absolute;top:0;left:0;right:0",icon:'<i class="note-icon-eraser"></i>',keepHtml:!1,keepOnlyTags:["<p>","<br>","<ul>","<li>","<b>","<strong>","<i>","<a>"],keepClasses:!1,badTags:["style","script","applet","embed","noframes","noscript","html"],badAttributes:["style","start"],limitChars:!1,limitDisplay:"both",limitStop:!1},callbacks:{onImageUpload:function(t){var a,o;a=t[0],o=$(e),data=new FormData,data.append("img",a),data.append("_token",suda.data("csrfToken")),data.append("media_type","upload"),$.ajax({data:data,type:"POST",url:suda.link(suda.data("adminPath")+"/medias/upload/image/editor"),cache:!1,contentType:!1,processData:!1,success:function(e){o.summernote("insertImage",e.url,e.name)}})},onKeydown:function(e){}},popover:{image:[["image",["resizeFull","resizeHalf","resizeQuarter","resizeNone"]],["float",["floatLeft","floatRight","floatNone"]],["remove",["removeMedia"]]]}}),$.fn.mediabox({modal_url:a,upload_url:o}))}}))}});