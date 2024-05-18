/******/ (() => { // webpackBootstrap
/******/ 	var __webpack_modules__ = ({

/***/ "./resources/assets/js/plugins/editor.js":
/*!***********************************************!*\
  !*** ./resources/assets/js/plugins/editor.js ***!
  \***********************************************/
/***/ (() => {

jQuery(function () {
  var sendEditorFile = function sendEditorFile(send_url, file, editor) {
    data = new FormData();
    data.append("img", file);
    data.append("_token", suda.data('csrfToken'));
    data.append("media_type", 'upload');
    $.ajax({
      data: data,
      type: "POST",
      url: send_url,
      cache: false,
      contentType: false,
      processData: false,
      success: function success(response) {
        //response = JSON.parse(response);
        //xy_clog
        //console.log(response);
        editor.summernote('insertImage', response.url, response.name);
      }
    });
  };
  function CleanPastedHTML(input) {
    // 1. remove line breaks
    //var stringStripper = /(\n|\r| class=(")?Mso[a-zA-Z]+(")?)/g;
    //var output = input.replace(stringStripper, ' ');
    var output = input;
    // 2. strip Word generated HTML comments
    var commentSripper = new RegExp('<!--(.*?)-->', 'g');
    var output = output.replace(commentSripper, '');

    // 3. remove tags leave content if any
    var tagStripper = new RegExp('<(/)*(meta|link|span|\\?xml:|st1:|o:|font)(.*?)>', 'gi');
    output = output.replace(tagStripper, '');

    // 4. Remove everything in between and including tags '<style(.)style(.)>'
    var badTags = ['style', 'script', 'applet', 'embed', 'noframes', 'noscript'];
    for (var i = 0; i < badTags.length; i++) {
      tagStripper = new RegExp('<' + badTags[i] + '.*?' + badTags[i] + '(.*?)>', 'gi');
      output = output.replace(tagStripper, '');
    }

    // 5. remove attributes ' style="..."'
    var badAttributes = ['style', 'start'];
    for (var i = 0; i < badAttributes.length; i++) {
      var attributeStripper = new RegExp(' ' + badAttributes[i] + '="(.*?)"', 'gi');
      output = output.replace(attributeStripper, '');
    }
    return output;
  }
  $.fn.sudaEditor = function (elem) {
    if ($(elem)) {
      $(elem).summernote({
        lang: 'en-US',
        height: suda.editor_height,
        minHeight: null,
        maxHeight: null,
        focus: true,
        shortcuts: false,
        disableDragAndDrop: false,
        dialogsFade: true,
        placeholder: $(elem).attr('placeholder'),
        tooltip: false,
        toolbar: [
        // ['cleaner',['cleaner']],
        ['style', ['style']], ['font', ['bold', 'italic', 'underline', 'strikethrough', 'clear']], ['color', ['color']], ['para', ['ul', 'ol', 'paragraph', 'hr']], ['insert', ['table', 'link', 'mediaPlugin', 'video']],
        // ['custom', ['mediaPlugin']],
        ['misc', ['codeview', 'fullscreen']]],
        cleaner: {
          action: 'both',
          // both|button|paste 'button' only cleans via toolbar button, 'paste' only clean when pasting content, both does both options.
          newline: '<br>',
          // Summernote's default is to use '<p><br></p>'
          notStyle: 'position:absolute;top:0;left:0;right:0',
          // Position of Notification
          icon: '<i class="note-icon-eraser"></i>',
          keepHtml: false,
          // Remove all Html formats
          keepOnlyTags: ['<p>', '<br>', '<ul>', '<li>', '<b>', '<strong>', '<i>', '<a>'],
          // If keepHtml is true, remove all tags except these
          keepClasses: false,
          // Remove Classes
          badTags: ['style', 'script', 'applet', 'embed', 'noframes', 'noscript', 'html'],
          // Remove full tags with contents
          badAttributes: ['style', 'start'],
          // Remove attributes from remaining tags
          limitChars: false,
          // 0/false|# 0/false disables option
          limitDisplay: 'both',
          // text|html|both
          limitStop: false // true/false
        },
        callbacks: {
          onImageUpload: function onImageUpload(files) {
            var upload_url = suda.link(window.suda.meta['.mediabox_upload_url'] + 'editor');
            sendEditorFile(upload_url, files[0], $(elem));
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
          onKeydown: function onKeydown(e) {
            //$(elem).summernote('saveRange');
          }
        },
        popover: {
          image: [
          // ['custom', ['imageShapes']],
          // ['custom', ['captionIt']],
          ['image', ['resizeFull', 'resizeHalf', 'resizeQuarter', 'resizeNone']], ['float', ['floatLeft', 'floatRight', 'floatNone']], ['remove', ['removeMedia']]]
          //   captionIt:{
          //     figureClass:'{figure-class/es}',
          //     figcaptionClass:'{figcapture-class/es}',
          //     captionText:'{图片描述}'
          //   }
        }
      });
    }
  };
});

/***/ }),

/***/ "./resources/assets/sass/app.scss":
/*!****************************************!*\
  !*** ./resources/assets/sass/app.scss ***!
  \****************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "./resources/assets/sass/app_site.scss":
/*!*********************************************!*\
  !*** ./resources/assets/sass/app_site.scss ***!
  \*********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = __webpack_modules__;
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/chunk loaded */
/******/ 	(() => {
/******/ 		var deferred = [];
/******/ 		__webpack_require__.O = (result, chunkIds, fn, priority) => {
/******/ 			if(chunkIds) {
/******/ 				priority = priority || 0;
/******/ 				for(var i = deferred.length; i > 0 && deferred[i - 1][2] > priority; i--) deferred[i] = deferred[i - 1];
/******/ 				deferred[i] = [chunkIds, fn, priority];
/******/ 				return;
/******/ 			}
/******/ 			var notFulfilled = Infinity;
/******/ 			for (var i = 0; i < deferred.length; i++) {
/******/ 				var [chunkIds, fn, priority] = deferred[i];
/******/ 				var fulfilled = true;
/******/ 				for (var j = 0; j < chunkIds.length; j++) {
/******/ 					if ((priority & 1 === 0 || notFulfilled >= priority) && Object.keys(__webpack_require__.O).every((key) => (__webpack_require__.O[key](chunkIds[j])))) {
/******/ 						chunkIds.splice(j--, 1);
/******/ 					} else {
/******/ 						fulfilled = false;
/******/ 						if(priority < notFulfilled) notFulfilled = priority;
/******/ 					}
/******/ 				}
/******/ 				if(fulfilled) {
/******/ 					deferred.splice(i--, 1)
/******/ 					var r = fn();
/******/ 					if (r !== undefined) result = r;
/******/ 				}
/******/ 			}
/******/ 			return result;
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	(() => {
/******/ 		__webpack_require__.o = (obj, prop) => (Object.prototype.hasOwnProperty.call(obj, prop))
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/jsonp chunk loading */
/******/ 	(() => {
/******/ 		// no baseURI
/******/ 		
/******/ 		// object to store loaded and loading chunks
/******/ 		// undefined = chunk not loaded, null = chunk preloaded/prefetched
/******/ 		// [resolve, reject, Promise] = chunk loading, 0 = chunk loaded
/******/ 		var installedChunks = {
/******/ 			"/publish/assets/js/plugins/editor": 0,
/******/ 			"publish/assets/css/app": 0,
/******/ 			"publish/assets/css/app_site": 0
/******/ 		};
/******/ 		
/******/ 		// no chunk on demand loading
/******/ 		
/******/ 		// no prefetching
/******/ 		
/******/ 		// no preloaded
/******/ 		
/******/ 		// no HMR
/******/ 		
/******/ 		// no HMR manifest
/******/ 		
/******/ 		__webpack_require__.O.j = (chunkId) => (installedChunks[chunkId] === 0);
/******/ 		
/******/ 		// install a JSONP callback for chunk loading
/******/ 		var webpackJsonpCallback = (parentChunkLoadingFunction, data) => {
/******/ 			var [chunkIds, moreModules, runtime] = data;
/******/ 			// add "moreModules" to the modules object,
/******/ 			// then flag all "chunkIds" as loaded and fire callback
/******/ 			var moduleId, chunkId, i = 0;
/******/ 			if(chunkIds.some((id) => (installedChunks[id] !== 0))) {
/******/ 				for(moduleId in moreModules) {
/******/ 					if(__webpack_require__.o(moreModules, moduleId)) {
/******/ 						__webpack_require__.m[moduleId] = moreModules[moduleId];
/******/ 					}
/******/ 				}
/******/ 				if(runtime) var result = runtime(__webpack_require__);
/******/ 			}
/******/ 			if(parentChunkLoadingFunction) parentChunkLoadingFunction(data);
/******/ 			for(;i < chunkIds.length; i++) {
/******/ 				chunkId = chunkIds[i];
/******/ 				if(__webpack_require__.o(installedChunks, chunkId) && installedChunks[chunkId]) {
/******/ 					installedChunks[chunkId][0]();
/******/ 				}
/******/ 				installedChunks[chunkId] = 0;
/******/ 			}
/******/ 			return __webpack_require__.O(result);
/******/ 		}
/******/ 		
/******/ 		var chunkLoadingGlobal = self["webpackChunk"] = self["webpackChunk"] || [];
/******/ 		chunkLoadingGlobal.forEach(webpackJsonpCallback.bind(null, 0));
/******/ 		chunkLoadingGlobal.push = webpackJsonpCallback.bind(null, chunkLoadingGlobal.push.bind(chunkLoadingGlobal));
/******/ 	})();
/******/ 	
/************************************************************************/
/******/ 	
/******/ 	// startup
/******/ 	// Load entry module and return exports
/******/ 	// This entry module depends on other loaded chunks and execution need to be delayed
/******/ 	__webpack_require__.O(undefined, ["publish/assets/css/app","publish/assets/css/app_site"], () => (__webpack_require__("./resources/assets/js/plugins/editor.js")))
/******/ 	__webpack_require__.O(undefined, ["publish/assets/css/app","publish/assets/css/app_site"], () => (__webpack_require__("./resources/assets/sass/app.scss")))
/******/ 	var __webpack_exports__ = __webpack_require__.O(undefined, ["publish/assets/css/app","publish/assets/css/app_site"], () => (__webpack_require__("./resources/assets/sass/app_site.scss")))
/******/ 	__webpack_exports__ = __webpack_require__.O(__webpack_exports__);
/******/ 	
/******/ })()
;