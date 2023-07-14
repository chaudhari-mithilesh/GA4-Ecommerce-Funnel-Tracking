console.log("gform-tracking-file");
alert("form tracking js enqueued.");
// var form_data = <?php echo json_encode($data); ?>;
if (form_data["name"] == undefined) {
  delete form_data.name;
  var check = 1;
}
if (check) {
  dataLayer.push({
    event: "wp_form_tracking",
    ecommerce: {
      email: form_data["email"],
    },
  });
} else {
  dataLayer.push({
    event: "wp_form_tracking",
    ecommerce: {
      name: form_data["name"],
      email: form_data["email"],
    },
  });
}
