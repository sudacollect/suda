!function(e){var t={};function n(o){if(t[o])return t[o].exports;var r=t[o]={i:o,l:!1,exports:{}};return e[o].call(r.exports,r,r.exports,n),r.l=!0,r.exports}n.m=e,n.c=t,n.d=function(e,t,o){n.o(e,t)||Object.defineProperty(e,t,{enumerable:!0,get:o})},n.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},n.t=function(e,t){if(1&t&&(e=n(e)),8&t)return e;if(4&t&&"object"==typeof e&&e&&e.__esModule)return e;var o=Object.create(null);if(n.r(o),Object.defineProperty(o,"default",{enumerable:!0,value:e}),2&t&&"string"!=typeof e)for(var r in e)n.d(o,r,function(t){return e[t]}.bind(null,r));return o},n.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return n.d(t,"a",t),t},n.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},n.p="/",n(n.s=191)}({191:function(e,t,n){n(192),n(243),e.exports=n(249)},192:function(e,t){jQuery((function(){$.fn.sudaEditor=function(e){$(e)&&$(e).summernote({lang:"en-US",height:suda.editor_height,minHeight:null,maxHeight:null,focus:!0,shortcuts:!1,disableDragAndDrop:!1,dialogsFade:!0,placeholder:$(e).attr("placeholder"),tooltip:!1,toolbar:[["style",["style"]],["font",["bold","italic","underline","strikethrough","clear"]],["color",["color"]],["para",["ul","ol","paragraph","hr"]],["insert",["table","link","mediaPlugin","video"]],["misc",["codeview","fullscreen"]]],cleaner:{action:"both",newline:"<br>",notStyle:"position:absolute;top:0;left:0;right:0",icon:'<i class="note-icon-eraser"></i>',keepHtml:!1,keepOnlyTags:["<p>","<br>","<ul>","<li>","<b>","<strong>","<i>","<a>"],keepClasses:!1,badTags:["style","script","applet","embed","noframes","noscript","html"],badAttributes:["style","start"],limitChars:!1,limitDisplay:"both",limitStop:!1},callbacks:{onImageUpload:function(t){var n,o,r,a=suda.link(window.suda.meta[".mediabox_upload_url"]+"editor");n=a,o=t[0],r=$(e),data=new FormData,data.append("img",o),data.append("_token",suda.data("csrfToken")),data.append("media_type","upload"),$.ajax({data:data,type:"POST",url:n,cache:!1,contentType:!1,processData:!1,success:function(e){r.summernote("insertImage",e.url,e.name)}})},onKeydown:function(e){}},popover:{image:[["image",["resizeFull","resizeHalf","resizeQuarter","resizeNone"]],["float",["floatLeft","floatRight","floatNone"]],["remove",["removeMedia"]]]}})}}))},243:function(e,t){},249:function(e,t){}});