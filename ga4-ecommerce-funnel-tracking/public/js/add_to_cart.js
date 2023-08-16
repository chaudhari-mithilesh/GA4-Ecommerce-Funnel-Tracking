jQuery(document).ready(function () {
  console.log("Connected to cart");
  if (typeof dataLayer === 'undefined') {
    window.dataLayer = [];
  }
  var data = {};
  data['currency'] = item_data["currency"];
  data['value'] = parseFloat(item_data["value"]);
  data['items'] = item_data["item"];
  gtag('event', 'add_to_cart', data);
});
