jQuery(document).ready(function () {
  console.log("Removed from cart");
  if (typeof dataLayer === 'undefined') {
    window.dataLayer = [];
  }
  var data = {};
  data['currency'] = item_data["currency"];
  data['value'] = parseFloat(item_data["value"]);
  data['items'] = item_data["item"];
  gtag('event', 'remove_from_cart', data);
});
