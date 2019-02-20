
$('#ShopCategoryTree').bind('move_node.jstree', function (node, parent) {
    $.ajax({
        type: 'GET',
        url: '/admin/shop/category/moveNode',
        data: {
            'id': parent.node.id.replace('node_', ''),
            'ref': parent.parent.replace('node_', ''),
            'position': parent.position
        }
    });
});

$('#ShopCategoryTree').bind('rename_node.jstree', function (node, text) {
    if (text.old !== text.text) {
        $.ajax({
            type: 'GET',
            url: "/admin/shop/category/renameNode",
            dataType: 'json',
            data: {
                "id": text.node.id.replace('node_', ''),
                text: text.text
            },
            success: function (data) {
                common.notify(data.message,'success');
            }
        });
    }
});
//Need dev.
$('#ShopCategoryTree').bind('create_node.jstree', function (node, parent, position) {


    $.ajax({
        type: 'GET',
        url: "/admin/shop/category/createNode",
        dataType: 'json',
        data: {
            text: parent.node.text,
            parent_id: parent.parent.replace('node_', '')
        },
        success: function (data) {
            common.notify(data.message,'success');
        }
    });
});

$('#ShopCategoryTree').bind("delete_node.jstree", function (node, parent) {
    $.ajax({
        type: 'GET',
        url: "/admin/shop/category/delete",
        data: {
            "id": parent.node.id.replace('node_', '')
        }
    });
});

function categorySwitch(node) {
    $.ajax({
        type: 'GET',
        url: "/admin/shop/category/switchNode",
        dataType: 'json',
        data: {
            id: node.id.replace('node_', ''),
        },
        success: function (data) {
            var icon = (data.switch) ? 'icon-eye' : 'icon-eye-close';
            common.notify(data.message,'success');
            $('#ShopCategoryTree').jstree(true).set_icon(node, icon);
        },
        beforeSend:function(){
            common.notify(common.message.loading);
        }
    });
}




