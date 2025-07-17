jQuery(document).ready(function () {
  console.log("Checkout Page loaded");
  if (typeof dataLayer === 'undefined') {
    window.dataLayer = [];
  }
  var data = {};
  data['currency'] = cart_data["currency"];
  data['items'] = cart_data["items"];
  data['coupon_code'] = cart_data["coupon_code"];
  gtag('event', 'checkout_event', data);
});
