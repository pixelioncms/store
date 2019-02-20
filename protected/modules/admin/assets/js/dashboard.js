function footerCssPadding(selector) {
    $('.footer').css({'padding-left': ($(selector).hasClass('active')) ? 250 : 0});
}


$(document).ready(function () {
    /*$('.dropdown-toggle').dropdown();

    $('#notification-dropdown').on('show.bs.dropdown', function () {
        console.log('s');
        alert('ss');
    });*/


});
$(function () {
    $('[data-toggle="popover"]').popover({
        //trigger: 'hover'
        container: 'body'
    });


    $('[data-toggle="tooltip"]').tooltip();


    $('#notifactionLink').on("click", function (e) {
        var itemsBlock = $(this).next('.dropdown-menu');
        var isShow = itemsBlock.hasClass('show');
        var data = [];
        var objectList = [];
        if (isShow) {
            $.each(itemsBlock.find('.notification'), function (i, obj) {
                //  $( "#" + i ).append( document.createTextNode( " - " + val ) );
                //console.log(i);
                console.log($(obj).attr('data-notification-id'));
                data.push($(obj).attr('data-notification-id'));
                objectList.push(obj);
            });
            console.log(data);
            if (data !== undefined) {
                $.ajax({
                    'type': 'POST',
                    'url': '/admin/ajax/notifications',
                    'dataType': 'json',
                    'data': {ids: data},
                    'success': function (data) {
                        console.log(data);
                        if (data.status == 'success') {
                            /*$.each(data.items, function (i, id) {
                             $('#notifaction-' + id).remove();
                             console.log(id);
                             });*/
                            var countItems = data.items.length;
                            var currentCount = parseInt($('#notification-count').text());
                            var resultCount = currentCount - countItems;
                            if (currentCount >= 3) {
                                $('#notification-count').text(resultCount);
                            }
                            if(resultCount < 3){
                                $('#notification-count').remove();
                            }
                        }
                    }
                });
            }
        }


        //e.stopPropagation();
       // e.preventDefault();


    });


    //Slidebar
    var toggle_selector = '#wrapper';
    var cook_name = toggle_selector.replace(/#/g, "");

    footerCssPadding(toggle_selector);


    var dashboard = {
        saveMenuCookie: function (data) {
            $.cookie(cook_name, data, {
                expires: 300,
                path: '/' //window.location.href
            });
        }
    };
    if (!$('#sidebar-wrapper').length) {
        $(toggle_selector).removeClass('active');
        dashboard.saveMenuCookie(false);
    } else {
        dashboard.saveMenuCookie($(toggle_selector).hasClass('active'));
    }

    $("#menu-toggle").click(function (e) {
        var w = $(window).width();
        e.preventDefault();
        if(w <= 768){
            $('#sidebar-wrapper').toggleClass('active');
        }else{
            $(toggle_selector).toggleClass("active");
            $('#menu ul').hide();
            footerCssPadding(toggle_selector);
            dashboard.saveMenuCookie($(toggle_selector).hasClass('active'));
        }
    });

    //fix for mobile for dropdown
    /*$('.table-responsive').on('shown.bs.dropdown', function (e) {
     var t = $(this),
     m = $(e.target).find('.dropdown-menu'),
     tb = t.offset().top + t.height(),
     mb = m.offset().top + m.outerHeight(true),
     d = 20; // Space for shadow + scrollbar.
     if (t[0].scrollWidth > t.innerWidth()) {
     if (mb + d > tb) {
     t.css('padding-bottom', ((mb + d) - tb));
     }
     } else {
     t.css('overflow', 'visible');
     }
     }).on('hidden.bs.dropdown', function () {
     $(this).css({'padding-bottom': '', 'overflow': ''});
     });*/
});
