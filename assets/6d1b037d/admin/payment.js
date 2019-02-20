
$(document).ready(function(){
    $('#ShopPaymentMethod_payment_system').on('change',function(){
        $('#payment_configuration').load('/admin/cart/paymentMethod/renderConfigurationForm/system/'+$(this).val()+'/payment_method_id/'+$(this).attr('rel'));
    });
    $('#ShopPaymentMethod_payment_system').change();
});