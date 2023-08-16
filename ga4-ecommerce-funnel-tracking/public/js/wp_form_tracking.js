// form_tracking.js

jQuery(document).ready(function ($) {
  // Function to check if the confirmation message is visible
  function checkConfirmation() {
    if ($(".gform_confirmation_message").is(":visible")) {
      console.log("form-tracking-file");
      var form_data = form_data_object;
      console.log(form_data.form_fields);
      if (typeof dataLayer === 'undefined') {
        window.dataLayer = [];
      }
      var data = {};
      data['form_title'] = form_data.form_title;
      data['form_fields'] = form_data.form_fields;
      gtag('event', 'form_tracking', data);
    } else {
      // Confirmation message not visible yet, check again after a short delay
      setTimeout(checkConfirmation, 100); // Check again after 100 milliseconds
    }
  }

  // Start checking for the confirmation message visibility
  checkConfirmation();
});
