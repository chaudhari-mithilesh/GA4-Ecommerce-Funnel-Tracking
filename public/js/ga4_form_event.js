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

jQuery(document).ready(function ($) {
  console.log('DOCUMENT READY');
  
  var wpcf7Elm = document.querySelector('.wpcf7');
    
  if (wpcf7Elm) {
    // alert('Form Present');
    wpcf7Elm.addEventListener('wpcf7submit', function (event) {
          // alert('Form Submitted');
      var formId = event.detail.contactFormId;
      console.log(formId);
      // alert(formId);
          var eventName = formId == '1037878' ? 'maintenance_plan_page_form_fill' : 'form_submission';
          // alert(eventName);
          // Collect form data from event.detail.inputs
          var formData = {};
          event.detail.inputs.forEach(function(input) {
              formData[input.name] = input.value;
          });
          // alert(formData);
          
          window.dataLayer = window.dataLayer || [];
          window.dataLayer.push({
              event: eventName,
              formData: formData
          });
          
          console.log('Pushed into DataLayer:', window.dataLayer);
        }, false);
    }

  // Contact Form 7 Prospect Service Event Form Data
  if (typeof form_data_array !== 'undefined' && form_data_array !== null && form_data_array !== '' && typeof form_data_array['about-assistance'] !== 'undefined' && form_data_array['about-assistance'] !== null && form_data_array['about-assistance'] !== '') {
    var date = new Date();
    var log ="\n" + "--------------------------------JS Log " + date + "----------------------------------------------------------------" + "\n";
    console.log(log);
    log_event(log);
    log_event("\nIf condition Passed\n");

    

    // log_event(form_data_array);

    // var name = form_data_array['full_name'];
    log_event("Form Data");
    var log = JSON.stringify(form_data_array);

     // Define the values to be blocked
    const blockedName = "WisdmLabs Uptime Test";
    const blockedEmail = "techopstest@wisdmlabs.com";
    const blockedMessage = "This is uptime";

    const fullName = form_data_array['full_name'] || '';
    const email = form_data_array['email'] || '';
    const message = form_data_array['your-message'] || '';

    // Check for blocked submissions
    if (
      fullName.trim() === blockedName ||
      email.trim().toLowerCase() === blockedEmail.toLowerCase() ||
      message.trim() === blockedMessage
    ) {
      log_event("\nBlocked test or uptime submission. Not pushing to DataLayer.\n");
      return; // Stop execution
    }

    log_event(log);
    // if (!name.toLowerCase().includes("test")) {
      log_event("\nNot a Test Submission.\n");
      dataLayer.push({
        'lead_id': form_data_array['lead_number'],
        'project_type': form_data_array['about-assistance'],
        'budget': form_data_array.project_budget[0],
        'event': 'prospect_event',
      });
      delete_option();
      log_event('Pushed into DataLayer');
      var date = new Date();
      var log ="--------------------------------JS Log " + date + "----------------------------------------------------------------";
      log_event(log);
    // }
  }


  // Delete Option AJAX
  delete_option();


  function delete_option() {
  $.ajax({
    url: delete_option_ajax_object.ajaxurl,
    type: 'POST',
    data: {
      action: 'delete_option_action',
      option_name: 'prospect_service_form_data_ga4',
      security: delete_option_ajax_object.security // You need to define ajax_nonce in your PHP code to prevent CSRF attacks
    },
    success: function (response) {
      // Handle success response
      console.log('Option deleted successfully');
    },
    error: function (xhr, status, error) {
      // Handle error response
      console.error('Error deleting option: ' + error);
    }
  });
}

function log_event(log) {
  // console.log(log_event_ajax_object);
   $.ajax({
    url: log_event_ajax_object.ajaxurl,
    type: 'POST',
    data: {
      action: 'log_event_action',
      data: log,
      security: log_event_ajax_object.log_nonce_security, // You need to define ajax_nonce in your PHP code to prevent CSRF attacks
    },
    success: function (response) {
      // Handle success response
      console.log('JS event Logged successfully', response);
    },
    error: function (xhr, status, error) {
      // Handle error response
      console.error('Error Logging JS Event: ' + error);
    }
  });
}
});