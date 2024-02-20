(self["webpackChunk"] = self["webpackChunk"] || []).push([["app"],{

/***/ "./assets/app.js":
/*!***********************!*\
  !*** ./assets/app.js ***!
  \***********************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _styles_app_scss__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./styles/app.scss */ "./assets/styles/app.scss");
/* harmony import */ var _js_navbar_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./js/navbar.js */ "./assets/js/navbar.js");
/* harmony import */ var _js_navbar_js__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_js_navbar_js__WEBPACK_IMPORTED_MODULE_1__);
/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)



/***/ }),

/***/ "./assets/js/navbar.js":
/*!*****************************!*\
  !*** ./assets/js/navbar.js ***!
  \*****************************/
/***/ (() => {

var dropdownButton = document.getElementById("user-menu-button");
var dropdownMenu = document.querySelector('[role="menu"]');
dropdownButton.addEventListener("click", function () {
  dropdownMenu.classList.toggle("hidden");
});
var menuButton = document.querySelector('[aria-controls="mobile-menu"]');
var menu = document.getElementById("mobile-menu");
menuButton.addEventListener("click", function () {
  menu.classList.toggle("hidden");
});

/***/ }),

/***/ "./assets/styles/app.scss":
/*!********************************!*\
  !*** ./assets/styles/app.scss ***!
  \********************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ })

},
/******/ __webpack_require__ => { // webpackRuntimeModules
/******/ var __webpack_exec__ = (moduleId) => (__webpack_require__(__webpack_require__.s = moduleId))
/******/ var __webpack_exports__ = (__webpack_exec__("./assets/app.js"));
/******/ }
]);
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoiYXBwLmpzIiwibWFwcGluZ3MiOiI7Ozs7Ozs7Ozs7Ozs7QUFBQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDMkI7Ozs7Ozs7Ozs7O0FDUjNCLElBQU1BLGNBQWMsR0FBR0MsUUFBUSxDQUFDQyxjQUFjLENBQUMsa0JBQWtCLENBQUM7QUFDbEUsSUFBTUMsWUFBWSxHQUFHRixRQUFRLENBQUNHLGFBQWEsQ0FBQyxlQUFlLENBQUM7QUFFNURKLGNBQWMsQ0FBQ0ssZ0JBQWdCLENBQUMsT0FBTyxFQUFFLFlBQU07RUFDN0NGLFlBQVksQ0FBQ0csU0FBUyxDQUFDQyxNQUFNLENBQUMsUUFBUSxDQUFDO0FBQ3pDLENBQUMsQ0FBQztBQUVGLElBQU1DLFVBQVUsR0FBR1AsUUFBUSxDQUFDRyxhQUFhLENBQUMsK0JBQStCLENBQUM7QUFDMUUsSUFBTUssSUFBSSxHQUFHUixRQUFRLENBQUNDLGNBQWMsQ0FBQyxhQUFhLENBQUM7QUFFbkRNLFVBQVUsQ0FBQ0gsZ0JBQWdCLENBQUMsT0FBTyxFQUFFLFlBQU07RUFDekNJLElBQUksQ0FBQ0gsU0FBUyxDQUFDQyxNQUFNLENBQUMsUUFBUSxDQUFDO0FBQ2pDLENBQUMsQ0FBQzs7Ozs7Ozs7Ozs7O0FDWkYiLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vLi9hc3NldHMvYXBwLmpzIiwid2VicGFjazovLy8uL2Fzc2V0cy9qcy9uYXZiYXIuanMiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL3N0eWxlcy9hcHAuc2Nzcz8zZThhIl0sInNvdXJjZXNDb250ZW50IjpbIi8qXG4gKiBXZWxjb21lIHRvIHlvdXIgYXBwJ3MgbWFpbiBKYXZhU2NyaXB0IGZpbGUhXG4gKlxuICogV2UgcmVjb21tZW5kIGluY2x1ZGluZyB0aGUgYnVpbHQgdmVyc2lvbiBvZiB0aGlzIEphdmFTY3JpcHQgZmlsZVxuICogKGFuZCBpdHMgQ1NTIGZpbGUpIGluIHlvdXIgYmFzZSBsYXlvdXQgKGJhc2UuaHRtbC50d2lnKS5cbiAqL1xuXG4vLyBhbnkgQ1NTIHlvdSBpbXBvcnQgd2lsbCBvdXRwdXQgaW50byBhIHNpbmdsZSBjc3MgZmlsZSAoYXBwLmNzcyBpbiB0aGlzIGNhc2UpXG5pbXBvcnQgXCIuL3N0eWxlcy9hcHAuc2Nzc1wiO1xuaW1wb3J0IFwiLi9qcy9uYXZiYXIuanNcIjtcbiIsImNvbnN0IGRyb3Bkb3duQnV0dG9uID0gZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoXCJ1c2VyLW1lbnUtYnV0dG9uXCIpO1xuY29uc3QgZHJvcGRvd25NZW51ID0gZG9jdW1lbnQucXVlcnlTZWxlY3RvcignW3JvbGU9XCJtZW51XCJdJyk7XG5cbmRyb3Bkb3duQnV0dG9uLmFkZEV2ZW50TGlzdGVuZXIoXCJjbGlja1wiLCAoKSA9PiB7XG4gIGRyb3Bkb3duTWVudS5jbGFzc0xpc3QudG9nZ2xlKFwiaGlkZGVuXCIpO1xufSk7XG5cbmNvbnN0IG1lbnVCdXR0b24gPSBkb2N1bWVudC5xdWVyeVNlbGVjdG9yKCdbYXJpYS1jb250cm9scz1cIm1vYmlsZS1tZW51XCJdJyk7XG5jb25zdCBtZW51ID0gZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoXCJtb2JpbGUtbWVudVwiKTtcblxubWVudUJ1dHRvbi5hZGRFdmVudExpc3RlbmVyKFwiY2xpY2tcIiwgKCkgPT4ge1xuICBtZW51LmNsYXNzTGlzdC50b2dnbGUoXCJoaWRkZW5cIik7XG59KTsiLCIvLyBleHRyYWN0ZWQgYnkgbWluaS1jc3MtZXh0cmFjdC1wbHVnaW5cbmV4cG9ydCB7fTsiXSwibmFtZXMiOlsiZHJvcGRvd25CdXR0b24iLCJkb2N1bWVudCIsImdldEVsZW1lbnRCeUlkIiwiZHJvcGRvd25NZW51IiwicXVlcnlTZWxlY3RvciIsImFkZEV2ZW50TGlzdGVuZXIiLCJjbGFzc0xpc3QiLCJ0b2dnbGUiLCJtZW51QnV0dG9uIiwibWVudSJdLCJzb3VyY2VSb290IjoiIn0=