jQuery(document).ready(function($) {
  console.log('ga4_form_event.js loaded.');
  $('.elementor-form .elementor-button[type="submit"]').click(function (event) {
    var formData = $(this).closest('.elementor-form').serializeArray();
    var formDataObject = {};
    $.each(formData, function (index, field) {
      formDataObject[field.name] = field.value;
    });
    gtag('event', 'form_tracking', formDataObject);
  });
});