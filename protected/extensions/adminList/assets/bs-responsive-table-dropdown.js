/**
 * Bootstrap Responsive Table Dropdown Plugin
 *
 * Currently if you attempt to use the bootstrap dropdown
 * plugin within a table wrapped in the .table-responsive
 * class the dropdown menu will be 'cut-off' (a portion of
 * the menu will be hidden).
 *
 * This plugin fixes this problem using fixed positioning
 * rather than absolute positioning for the elements that
 * need rescuing.
 *
 * @author Jake Wilkinson <jake.wilkinson@backslash.co.nz>
 * @version 1.0.0
 * @license MIT
 */
;(function ($, window, document) {
    $(document).on('ready', function() {
        var $window = $(window);

        $('.table-responsive [data-toggle=dropdown]').each(function() {
            var $this = $(this),
                $group = $this.parent(),
                $menu = $group.find('.dropdown-menu');

            $this.parent().on('show.bs.dropdown.bs-responsive-table-dropdown', function() {
                var offset = $group.position();

                // Apply the css we want, this will change every time we scroll/resize the screen
                // so we need to calculate this value dynamically.
                //console.log(offset.top);
                //console.log(offset.left);
                $menu.css({
                    'top': offset.top - $window.scrollTop() + $group.height(),
                    'left': offset.left - $window.scrollLeft() - $menu.width() + $group.width(),
                    'right': 'inherit',
                    'position': 'fixed'
                });
            });
        });

        // We want to hide all of the dropdown menu's when we resize the screen or when we scroll.
        // we apply this behaviour to all of the dropdown menu's on the page, not just the ones
        // we are fixing - for consistency.
        $window.on('scroll.bs-responsive-table-dropdown resize.bs-responsive-table-dropdown', function() {
            var $elem = $('.open > [data-toggle=dropdown]'),
                $group = $elem.parent(),
                $menu = $group.find('.dropdown-menu');

            $group.removeClass('open');
            $menu.prop('aria-expanded', false);
            $elem.blur();
        });
    });
}(jQuery, window, document));