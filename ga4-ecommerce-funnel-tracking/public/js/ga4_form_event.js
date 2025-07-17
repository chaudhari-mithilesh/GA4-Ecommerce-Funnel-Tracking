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

jQuery(document).on('nfFormSubmitResponse', function (e, response) {
  if (response) {
      var form_id = response.id;

if (typeof window.dataLayer === 'undefined') {
  window.dataLayer = [];
}
var formData = {};
        jQuery.each(response.data, function (key, value) {
            formData[key] = value.value;
        });
        window.dataLayer.push({
            event: 'form_tracking',
            form_id: form_id,
            form_data: formData
        });
console.log('Ninja Form Data Pushed');
  }
});