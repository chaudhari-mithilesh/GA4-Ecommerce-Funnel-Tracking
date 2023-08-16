jQuery(document).ready(function () {
  console.log("cart page loaded");
  if (typeof dataLayer === 'undefined') {
    window.dataLayer = [];
  }
  var data = {};
  data[currency] = cart_data["currency"];
  data[items] = cart_data["items"];
  gtag('event', 'view_cart', data);
});
