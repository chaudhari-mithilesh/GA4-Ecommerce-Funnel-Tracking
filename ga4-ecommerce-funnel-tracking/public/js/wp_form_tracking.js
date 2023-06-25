console.log("form-tracking-file");
dataLayer.push({
  name: form_data["name"],
  email: form_data["email"],
  comment: form_data["commment"],
  event: "wp_form_tracking",
});
