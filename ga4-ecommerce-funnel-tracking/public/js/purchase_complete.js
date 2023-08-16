jQuery(document).ready(function () {
  console.log("Purchase Complete");
  if (typeof dataLayer === 'undefined') {
    window.dataLayer = [];
  }
  var data = {};
  data['order_number'] = order_data["order_number"];
  data['order_total'] = order_data["order_total"];
  data['billing_email'] = order_data["billing_email"];
  data['billing_phone'] = order_data["billing_phone"];
  data['payment_method'] = order_data["payment_method"];
  data['country'] = order_data["country"];
  data['customer_id'] = order_data["customer_id"];
  gtag('event', 'purchase_complete', data);
});
