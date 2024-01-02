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
dataLayer.push({
event: 'form_tracking',
form_id: form_id,
});
console.log('Ninja Form Data Pushed');
  }
});