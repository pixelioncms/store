
/**
 * @param el
 * @return {Boolean}
 * @constructor
 */
function AddRelatedProduct(el) {
    var img = $(el).parent().find('.image img').attr("src");


    var product = $(el).parent().find("a.product-name");
    var product_id = product.attr('data-id');
    var product_name = product.text();
  //  var str = product.attr("href");

  //      console.log(product.attr('data-id'));
  // var parts = str.split("/");
    var trclass = "relatedProductLine" + product_id;
   //     console.log(parts);
    if ($("." + trclass).length == 0)
    {
        $("#relatedProductsTable").append("<tr class=" + trclass + "><td class=\"image text-center\"><img class=\"img-thumbnail\" src=\"" + img + "\" /></td><td>" + product_name + "</td><td class=\"text-center\">" +
                "<a href='javascript:void(0)' class='btn btn-danger' onclick='return $(this).parent().parent().remove();'>" + common.message.delete + "</a>" +
                "<input type='hidden' value='" + product_id + "' name='RelatedProductId[]'>" +
                "</td></tr>");
    }

    return false;
}
