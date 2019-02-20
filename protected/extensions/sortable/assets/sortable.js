function grid_sortable(id) {
    $('#' + id + ' tbody').sortable({
        connectWith: '.sortable-clipboard-area',
        axis: 'y',
        handle: '.sortable-column-handler',
        helper: function (event, ui) {
            ui.children().each(function () {
                $(this).width($(this).width());
            });
            return ui;
        },
        update: function (event, ui) {
            var ids = [];
            $('#' + id + ' .sortable-column').each(function (i) {
                ids[i] = $(this).data('id');
            });
            var clipboard = [];
            $('.sortable-clipboard-area .sortable-column').each(function (i) {
                clipboard[i] = $(this).data('id');
            });
            $.ajax({
                url: grid_sortable_url,
                type: 'POST',
                data: ({'ids': ids, 'clipboard': clipboard}),
                success: function () {
                    common.notify('Success!', 'success');
                }
            });
        }
    });
}