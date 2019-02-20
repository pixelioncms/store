
$(document).ready(function(){
    $('#ShopDeliveryMethod_delivery_system').on('change',function(){
        $('#delivery_configuration').load('/admin/cart/delivery/renderConfigurationForm/system/'+$(this).val()+'/delivery_method_id/'+$(this).attr('rel'));
    });
    $('#ShopDeliveryMethod_delivery_system').change();

});
