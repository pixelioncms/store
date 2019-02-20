
(function ($) {
    "use strict";

    $.fn.fileinputThemes.corner = {
        fileActionSettings: {
            removeIcon: '<i class="icon-trashcan text-danger"></i>',
            uploadIcon: '<i class="fa fa-upload text-info"></i>',
            zoomIcon: '<i class="fa fa-search-plus"></i>',
            dragIcon: '<i class="icon-drag"></i>',
            indicatorNew: '<i class="fa fa-hand-o-down text-warning"></i>',
            indicatorSuccess: '<i class="icon-check text-success"></i>',
            indicatorError: '<i class="icon-info text-danger"></i>',
            indicatorLoading: '<i class="fa fa-hand-o-up text-muted"></i>'
        },
        layoutTemplates: {
            fileIcon: '<i class="fa fa-file kv-caption-icon"></i> '
        },
        previewZoomButtonIcons: {
            prev: '<i class="fa fa-caret-left fa-lg"></i>',
            next: '<i class="fa fa-caret-right fa-lg"></i>',
            toggleheader: '<i class="icon-resize"></i>',
            fullscreen: '<i class="fa fa-arrows-alt"></i>',
            borderless: '<i class="icon-external-link"></i>',
            close: '<i class="icon-remove"></i>'
        },
        previewFileIcon: '<i class="fa fa-file"></i>',
        browseIcon: '<i class="icon-folder-open"></i>',
        removeIcon: '<i class="icon-trashcan"></i>',
        cancelIcon: '<i class="fa fa-ban"></i>',
        uploadIcon: '<i class="icon-upload"></i>',
        msgValidationErrorIcon: '<i class="fa fa-exclamation-circle"></i> '
    };
})(window.jQuery);
