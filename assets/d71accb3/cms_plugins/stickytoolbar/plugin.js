(function (modules) { // webpackBootstrap
    var installedModules = {};

    function __webpack_require__(moduleId) {
        if (installedModules[moduleId]) {
            return installedModules[moduleId].exports;
        }
        var module = installedModules[moduleId] = {
            i: moduleId,
            l: false,
            exports: {}

        };

        modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
        module.l = true;
        return module.exports;
    }

    __webpack_require__.m = modules;

    __webpack_require__.c = installedModules;

    __webpack_require__.i = function (value) {
        return value;
    };

    __webpack_require__.d = function (exports, name, getter) {

        if (!__webpack_require__.o(exports, name)) {
            Object.defineProperty(exports, name, {
                configurable: false,
                enumerable: true,
                get: getter

            });
        }
    };

    __webpack_require__.n = function (module) {

        var getter = module && module.__esModule ?
            function getDefault() {
                return module['default'];
            } :
            function getModuleExports() {
                return module;
            };
        __webpack_require__.d(getter, 'a', getter);
        return getter;
    };

    __webpack_require__.o = function (object, property) {
        return Object.prototype.hasOwnProperty.call(object, property);
    };

    __webpack_require__.p = "";

    return __webpack_require__(__webpack_require__.s = 1);

})
([(function (module, exports, __webpack_require__) {
        "use strict";
        Object.defineProperty(exports, "__esModule", {
            value: true
        });
        var plugin = function plugin(editor) {
            var offset = editor.settings.sticky_offset ? editor.settings.sticky_offset : 0;

            editor.on('init', function () {
                setSticky();
            });

            window.addEventListener('scroll', function () {
                setSticky();
            });

            function setSticky() {
                var container = editor.getContainer();

                if (!editor.inline && container && container.offsetParent) {

                    var statusbar = '';

                    if (editor.settings.statusbar !== false) {
                        statusbar = container.querySelector('.mce-statusbar');
                    }

                    var topPart = container.querySelector('.mce-top-part');

                    if (isSticky()) {
                        container.style.paddingTop = topPart.offsetHeight + 'px';

                        if (isAtBottom()) {
                            topPart.style.top = null;
                            topPart.style.width = '100%';
                            topPart.style.position = 'absolute';
                            topPart.style.bottom = statusbar ? statusbar.offsetHeight + 'px' : 0;
                        } else {
                            topPart.style.bottom = null;
                            topPart.style.top = offset + 'px';
                            topPart.style.position = 'fixed';
                            topPart.style.width = container.clientWidth + 'px';
                            var body = topPart.querySelector('.mce-container-body');
                            body.style.backgroundColor = '#fff';
                        }
                    } else {
                        container.style.paddingTop = 0;
                        if (topPart) {
                            topPart.style.position = 'relative';
                            topPart.style.top = null;
                            topPart.style.width = null;
                            topPart.style.borderBottom = null;
                        }
                    }
                }
            }

            function isSticky() {
                var editorPosition = editor.getContainer().getBoundingClientRect().top;
                if (editorPosition < offset) {
                    return true;
                }
                return false;
            }

            function isAtBottom() {
                var container = editor.getContainer();

                var editorPosition = container.getBoundingClientRect().top,
                    statusbar = container.querySelector('.mce-statusbar'),
                    topPart = container.querySelector('.mce-top-part');

                var statusbarHeight = statusbar ? statusbar.offsetHeight : 0,
                    topPartHeight = topPart ? topPart.offsetHeight : 0;

                var stickyHeight = -(container.offsetHeight - topPartHeight - statusbarHeight);
                if (editorPosition < stickyHeight + offset) {
                    return true;
                }
                return false;
            }
        };

        exports.default = plugin;


    }),
    (function (module, exports, __webpack_require__) {
        "use strict";
        var _plugin = __webpack_require__(0);
        var _plugin2 = _interopRequireDefault(_plugin);
        function _interopRequireDefault(obj) {
            return obj && obj.__esModule ? obj : {default: obj};
        }
        tinymce.PluginManager.add('stickytoolbar', _plugin2.default);

    })
]);