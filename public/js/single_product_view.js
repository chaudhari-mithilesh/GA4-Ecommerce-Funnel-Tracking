jQuery(document).ready(function () {
  console.log("Product page loaded");
  if (typeof dataLayer === 'undefined') {
    window.dataLayer = [];
  }
  var product_data = {};
  product_data['item_id'] = data["item_id"];
  product_data['item_name'] = data["item_name"];
  product_data['discount'] = data["discount"];
  product_data['item_brand'] = data["item_brand"];
  product_data['item_category'] = data["item_category"];
  product_data['price'] = data["price"];
  gtag('event', 'single_product_view', product_data);
});
