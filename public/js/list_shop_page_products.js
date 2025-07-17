jQuery(document).ready(function () {
  console.log("shop page loaded");
  if (typeof dataLayer === 'undefined') {
    window.dataLayer = [];
}
var data = {};
data["currency"] = item_list_data["currency"];
data["items"] = item_list_data["items"];
gtag('event', 'list_shop_page_products', data);
});
