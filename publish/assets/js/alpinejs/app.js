/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "/";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 2);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/assets/js/alpinejs/app.js":
/*!*********************************************!*\
  !*** ./resources/assets/js/alpinejs/app.js ***!
  \*********************************************/
/*! no static exports found */
/***/ (function(module, exports) {

document.addEventListener('alpine:init', function () {
  Alpine.store('menuStyle', {
    name: ''
  });
  Alpine.data('userDropdown', function () {
    return {
      open: false,
      toggleUserDropdown: function toggleUserDropdown() {
        this.open = !this.open;
      }
    };
  });
  Alpine.data('sudaBody', function () {
    return {
      proSidebar: false,
      sidebarStyle: '',
      toggleSidebar: function toggleSidebar(e) {
        var _this = this;
        e.preventDefault();
        this.proSidebar = !this.proSidebar;
        if (e.currentTarget.parentElement.classList.contains('navbar-suda-icon')) {
          e.currentTarget.parentElement.classList.remove('navbar-suda-icon');
          this.$refs.sidebar.classList.remove('press-sidebar-icon');
          this.$refs.sidebar.classList.add('in');
          this.$refs.suda_app_content.classList.remove('suda-flat-lg');
          this.sidebarStyle = 'flat';
        } else if (!e.currentTarget.parentElement.classList.contains('navbar-suda-icon')) {
          e.currentTarget.parentElement.classList.add('navbar-suda-icon');
          this.$refs.sidebar.classList.add('press-sidebar-icon');
          this.$refs.sidebar.classList.remove('in');
          this.$refs.suda_app_content.classList.add('suda-flat-lg');
          this.sidebarStyle = 'icon';
        }
        fetch(document.head.querySelector('meta[name=root-path]').content + '/style/sidemenu/' + this.sidebarStyle, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.head.querySelector('meta[name=csrf-token]').content
          },
          body: JSON.stringify({})
        }).then(function () {
          // this.message = 'Form sucessfully submitted!'
          _this.$store.menuStyle.name = _this.sidebarStyle;
        })["catch"](function () {
          // this.message = 'Ooops! Something went wrong!'
        });
      }
    };
  });
});

/***/ }),

/***/ 2:
/*!***************************************************!*\
  !*** multi ./resources/assets/js/alpinejs/app.js ***!
  \***************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! /Users/mengdoo/codes/www/zhilayun/blog10/suda/resources/assets/js/alpinejs/app.js */"./resources/assets/js/alpinejs/app.js");


/***/ })

/******/ });