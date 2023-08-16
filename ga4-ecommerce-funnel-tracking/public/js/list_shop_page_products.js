jQuery(document).ready(function () {
  console.log("shop page loaded");
  if (typeof dataLayer === 'undefined') {
    window.dataLayer = [];
}
var data = {};
data[currency] = cart_data["currency"];
data[items] = cart_data["items"];
gtag('event', 'list_shop_page_products', data);
});
