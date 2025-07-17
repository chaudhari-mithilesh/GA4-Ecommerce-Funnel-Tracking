<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://wisdmalbs.com
 * @since      1.0.0
 *
 * @package    Ga4_Ecommerce_Funnel_Tracking
 * @subpackage Ga4_Ecommerce_Funnel_Tracking/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Ga4_Ecommerce_Funnel_Tracking
 * @subpackage Ga4_Ecommerce_Funnel_Tracking/public
 * @author     WisdmLabs <software@wisdmlabs.com>
 */
class Ga4_Ecommerce_Funnel_Tracking_Public
{

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct($plugin_name, $version)
	{

		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles()
	{

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Ga4_Ecommerce_Funnel_Tracking_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Ga4_Ecommerce_Funnel_Tracking_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts()
	{

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Ga4_Ecommerce_Funnel_Tracking_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Ga4_Ecommerce_Funnel_Tracking_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_enqueue_script('ga4_functions', plugin_dir_url(__FILE__) . 'js/functions.js', array('jquery'), $this->version, false);
		wp_enqueue_script('ga4_form_event', plugin_dir_url(__FILE__) . 'js/ga4_form_event.js', array('jquery'), $this->version, false);
		$nonce = wp_create_nonce('delete_option_nonce');
		$log_nonce = wp_create_nonce('log_event_nonce');
		wp_localize_script('ga4_form_event', 'delete_option_ajax_object', array(
			'ajaxurl' => admin_url('admin-ajax.php'),
			'security' => $nonce,
		));

		wp_localize_script('ga4_form_event', 'log_event_ajax_object', array(
			'ajaxurl' => admin_url('admin-ajax.php'),
			'log_nonce_security' => $log_nonce,
		));
		$prospect_form_data = get_option('prospect_service_form_data_ga4');
        if(!empty($prospect_form_data) && $prospect_form_data != ''){
            wp_localize_script('ga4_form_event', 'form_data_array', $prospect_form_data);
        }
	}

	/**
	 * Enqueues the GTM (Google Tag Manager) head script.
	 *
	 * This function is responsible for enqueueing the GTM head script file.
	 * The script file is added to the head section of the website.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */

	public function gtm_head_script()
	{
		$gtm_head_script = get_option('gtm_head_script');
		$blocked_ips_raw = get_option('gtm_blocked_ips');

		if (empty($gtm_head_script)) {
			return; // Nothing to output
		}

		// Get visitor IP
		$visitor_ip = $_SERVER['REMOTE_ADDR'] ?? '';

		// Prepare blocked IPs list
		$blocked_ips_array = array_filter(array_map('trim', explode("\n", $blocked_ips_raw)));

		// Check if IP is blocked
		if (!in_array($visitor_ip, $blocked_ips_array)) {
			echo $gtm_head_script;
		} else {
			// Optionally log or debug
			// error_log("GTM blocked for IP: $visitor_ip");
		}
	}

	/**
	 * Enqueues the GTM (Google Tag Manager) body script.
	 *
	 * This function is responsible for enqueuing the GTM body script code snippet.
	 * The script is added to the body section of the website within a noscript tag.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */

	public function gtm_body_script()
	{
		$gtm_body_script = get_option('gtm_body_script');
		$blocked_ips_raw = get_option('gtm_blocked_ips');

		if (empty($gtm_body_script)) {
			return; // No script to print
		}

		// Get visitor IP
		$visitor_ip = $_SERVER['REMOTE_ADDR'];

		// Prepare blocked IPs list
		$blocked_ips_array = array_filter(array_map('trim', explode("\n", $blocked_ips_raw)));

		// Output only if IP is NOT blocked
		if (!in_array($visitor_ip, $blocked_ips_array)) {
			echo $gtm_body_script;
		} else {
			// Optionally log or debug
			// error_log("GTM body script blocked for IP: $visitor_ip");
		}
	}

	public function gtm_debug_mode()
	{
		$gtm_debug_mode = get_option('debug_checkbox');
		if (!empty($gtm_debug_mode)) {
			?>
			<script>
				<!-- GA4 Ecommerce Funnel Tracking Settings -->
				 var gtm_debug_mode = "_test";
			</script>
			<?php
		}
	}

	/**
	 * Tracks form submission data to the data layer for GTM.
	 *
	 * This function processes form submission data and sends it to the Google Tag Manager
	 * data layer for tracking. It extracts form title and field values from the provided
	 * data and attaches them to the event that is pushed to the data layer.
	 *
	 * @since 1.0.0
	 *
	 * @param string $confirmation The confirmation message after form submission.
	 * @param array  $form_data    An array containing form data, including title.
	 * @param array  $fields       An array of form fields with their values.
	 * @return void
	 */
	public function wp_form_tracking($confirmation, $form_data, $fields)
	{
		$form_title = $form_data['title'];
		$field_values = array();

		foreach ($fields as $field) {
			$value = isset($field['value']) ? $field['value'] : '';
			$field_values[$field['name']] = $value;
		}

		$data = array(
			'form_title'  => $form_title,
			'form_fields' => $field_values,
		);

		?>
		<script>
			console.log("form-tracking-file");
			var form_data = <?php echo json_encode($data); ?>;
			dataLayer.push({
				event: "form_tracking",
				form_title: form_data.form_title,
				form_fields: form_data.form_fields,
			});
		</script>
		<?php
	}

	/**
	 * Tracks Gravity Forms submission data to the data layer for GTM.
	 *
	 * This function processes Gravity Forms submission data and enqueues a JavaScript script
	 * responsible for pushing the submission data to the Google Tag Manager data layer for tracking.
	 * It extracts form title and field values from the provided data and localizes them for the script.
	 *
	 * @since 1.0.0
	 *
	 * @param array $entry The entry data of the submitted form.
	 * @param array $form  The form data containing form title and fields.
	 * @return void
	 */
	function gform_tracking($entry, $form)
	{
		$form_title = $form['title'];
		$field_values = array();

		foreach ($form["fields"] as $field) {
			$value = rgar($entry, $field->id);
			$field_values[$field->label] = $value;
		}

		$data = array(
			'form_title'  => $form_title,
			'form_fields' => $field_values,
		);

		?>
		<script>
		jQuery(document).ready(function ($) {
			function checkConfirmation() {
				if ($(".gform_confirmation_message").is(":visible")) {
					console.log("form-tracking-file");
					if (typeof dataLayer === 'undefined') {
						window.dataLayer = [];
					}
					dataLayer.push({
						event: "form_tracking",
						form_title: form_data.form_title,
						form_fields: form_data.form_fields,
					});
				} else {
					setTimeout(checkConfirmation, 100); // Check again after 100 milliseconds
				}
			}
			checkConfirmation();
		});
		</script>
		<?php
	}


	/**
	 * Lists and enqueues products on the shop page for tracking purposes.
	 *
	 * This function retrieves the products displayed on the shop page,
	 * prepares the necessary data for tracking, and enqueues the script
	 * responsible for handling the tracking.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */

	public function list_shop_page_products()
	{
		$data = array(
			'currency' => get_woocommerce_currency(),
			'items' => array(),
		);

		if (have_posts()) {
			while (have_posts()) {
				the_post();
				$product = wc_get_product(get_the_ID());
				$product_data = $product->get_data();

				$item_data = array(
					'item_id' => $product->get_id(),
					'item_name' => $product_data['name'],
					'discount' => 0,
					'item_brand' => get_bloginfo('name'),
					'item_category' => 'Uncategorized',
					'price' => floatval($product_data['price']),
					'quantity' => 1,
				);

				if ($product->get_sale_price() !== '') {
					$discount_amount = $product->get_regular_price() - $product->get_sale_price();
					$item_data['discount'] = $discount_amount;
				}

				$category_ids = $product->get_category_ids();
				if (!empty($category_ids)) {
					$category = get_term($category_ids[0], 'product_cat');
					$item_data['item_category'] = $category->name;
				}

				$data['items'][] = $item_data;
			}
		}

		wp_enqueue_script('list_shop_page_products', plugin_dir_url(__FILE__) . 'js/list_shop_page_products.js', array('jquery'), $this->version, false);
		wp_localize_script('list_shop_page_products', 'item_list_data', $data);
	}

	/**
	 * Handles the event when a product is added to the cart.
	 *
	 * This function is triggered when a product is added to the cart.
	 * It retrieves the necessary data for tracking the added item, such as
	 * product information, price, and quantity. It then enqueues the script
	 * responsible for handling the tracking.
	 *
	 * @since 1.0.0
	 *
	 * @param int    $cart_id          The cart ID.
	 * @param int    $product_id       The ID of the product being added.
	 * @param int    $request_quantity The quantity of the product being added.
	 * @return void
	 */

	public function add_to_cart_event($cart_id, $product_id, $request_quantity)
	{
		$product_data = wc_get_product($product_id);
		$category_ids = $product_data->get_category_ids();

		$data = array(
			'currency' => get_woocommerce_currency(),
			'value' => (float) $product_data->get_price(),
			'item' => array(
				'item_id' => (string) $product_id,
				'item_name' => $product_data->get_name(),
				'item_brand' => get_bloginfo('name'),
				'item_category' => !empty($category_ids) ? get_term($category_ids[0], 'product_cat')->name : "Uncategorized",
				'price' => (float) $product_data->get_price(),
				'quantity' => $request_quantity
			)
		);

		wp_enqueue_script('add_to_cart', plugin_dir_url(__FILE__) . 'js/add_to_cart.js', array('jquery'), $this->version, false);
		wp_localize_script('add_to_cart', 'item_data', $data);
	}

	/**
	 * Handles the event when a single product is viewed.
	 *
	 * This function is triggered when a single product page is viewed.
	 * It retrieves the necessary data for tracking the viewed product, such as
	 * product information, price, and category. It then enqueues the script
	 * responsible for handling the tracking.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */

	public function view_single_product_event()
	{
		if (!is_product()) {
			return;
		}
		global $product;
		$product_data = $product->get_data();
		$data = array(
			'currency' => get_woocommerce_currency(),
			'items' => array(),
		);

		$item_data = array(
			'item_id' => $product->get_id(),
			'item_name' => $product_data['name'],
			'discount' => 0,
			'item_brand' => get_bloginfo('name'),
			'item_category' => 'Uncategorized',
			'price' => floatval($product_data['price']),
		);

		if ($product->get_sale_price() !== '') {
			$discount_amount = $product->get_regular_price() - $product->get_sale_price();
			$item_data['discount'] = $discount_amount;
		}

		$category_ids = $product->get_category_ids();
		if (!empty($category_ids)) {
			$category = get_term($category_ids[0], 'product_cat');
			$item_data['item_category'] = $category->name;
		}

		wp_enqueue_script('single_product_view', plugin_dir_url(__FILE__) . 'js/single_product_view.js', array('jquery'), $this->version, false);
		wp_localize_script('single_product_view', 'data', $item_data);
	}

	/**
	 * Handles the event when the cart page is viewed.
	 *
	 * This function is triggered when the cart page is viewed. It retrieves
	 * the necessary data for tracking the products in the cart, such as
	 * product information, price, and quantity. It then enqueues the script
	 * responsible for handling the tracking.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */

	public function view_cart_event()
	{
		if (!is_cart()) {
			return;
		}

		$woocommerce = WC();
		$data = array(
			'currency' => get_woocommerce_currency(),
			'items' => array(),
		);

		foreach ($woocommerce->cart->get_cart() as $cart_item_key => $cart_item) {
			$product = $cart_item['data'];
			$product_data = $product->get_data();

			$item_data = array(
				'item_id' => $product->get_id(),
				'item_name' => $product_data['name'],
				'discount' => 0,
				'item_brand' => get_bloginfo('name'),
				'item_category' => 'Uncategorized',
				'price' => floatval($product_data['price']),
				'quantity' => $cart_item['quantity'],
			);

			if ($product->get_sale_price() !== '') {
				$discount_amount = $product->get_regular_price() - $product->get_sale_price();
				$item_data['discount'] = $discount_amount;
			}

			$category_ids = $product->get_category_ids();
			if (!empty($category_ids)) {
				$category = get_term($category_ids[0], 'product_cat');
				$item_data['item_category'] = $category->name;
			}

			$data['items'][] = $item_data;
		}
		wp_enqueue_script('view_cart', plugin_dir_url(__FILE__) . 'js/view_cart.js', array('jquery'), $this->version, false);
		wp_localize_script('view_cart', 'cart_data', $data);
	}

	/**
	 * Handles the event when the checkout page is viewed.
	 *
	 * This function is triggered when the checkout page is viewed. It retrieves
	 * the necessary data for tracking the products in the cart, such as
	 * product information, price, and quantity. It then enqueues the script
	 * responsible for handling the tracking.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */

	public function checkout_event()
	{
		if (!is_checkout()) {
			return;
		}

		$woocommerce = WC();
		$data = array(
			'currency' => get_woocommerce_currency(),
			'items' => array(),
			'coupon_code' => 'Not Applied',
		);

		foreach ($woocommerce->cart->get_cart() as $cart_item_key => $cart_item) {
			$product = $cart_item['data'];
			$product_data = $product->get_data();

			$item_data = array(
				'item_id' => $product->get_id(),
				'item_name' => $product_data['name'],
				'discount' => 0,
				'item_brand' => get_bloginfo('name'),
				'item_category' => 'Uncategorized',
				'price' => floatval($product_data['price']),
				'quantity' => $cart_item['quantity'],
			);

			if ($product->get_sale_price() !== '') {
				$discount_amount = $product->get_regular_price() - $product->get_sale_price();
				$item_data['discount'] = $discount_amount;
			}

			$category_ids = $product->get_category_ids();
			if (!empty($category_ids)) {
				$category = get_term($category_ids[0], 'product_cat');
				$item_data['item_category'] = $category->name;
			}

			$data['items'][] = $item_data;
		}

		$applied_coupons = $woocommerce->cart->get_applied_coupons();
		if (!empty($applied_coupons)) {
			$data['coupon_codes'] = $applied_coupons;
		}

		if ($woocommerce->cart->applied_coupons) {
			$coupon_codes = $woocommerce->cart->get_coupons();
			$data['coupon_code'] = reset($coupon_codes)->get_code();
		}


		wp_enqueue_script('checkout_event', plugin_dir_url(__FILE__) . 'js/checkout.js', array('jquery'), $this->version, false);
		wp_localize_script('checkout_event', 'cart_data', $data);
	}

	/**
	 * Handles the event when the purchase is completed.
	 *
	 * This function is triggered when an order is placed and the purchase is completed.
	 * It retrieves the necessary order details such as order number, total, billing email,
	 * billing phone, payment method, country, and customer ID. It then enqueues the script
	 * responsible for handling the completion tracking.
	 *
	 * @since 1.0.0
	 *
	 * @param int $order_id The ID of the completed order.
	 * @return void
	 */

	public function purchase_event($order_id)
	{
		$order = wc_get_order($order_id);

		$order_details = array(
			'order_number' => $order->get_order_number(),
			'order_total' => $order->get_total(),
			'billing_email' => $order->get_billing_email(),
			'billing_phone' => $order->get_billing_phone(),
			'payment_method' => $order->get_payment_method_title(),
			'country' => $order->get_billing_country(),
			'customer_id' => $order->get_customer_id(),
		);

		wp_enqueue_script('purchase_complete', plugin_dir_url(__FILE__) . 'js/purchase_complete.js', array('jquery'), $this->version, false);
		wp_localize_script('purchase_complete', 'order_data', $order_details);
	}

	public function prospect_service_event($contact_form){


		//  Error Logging for Debugging Puposes

		error_log("\n\n" . '----------------------------------- Date - ' . date('Y-m-d H:i:s') .'------------------------------------------' . "\n", 3 , './wp-content/plugins/ga4-ecommerce-funnel-tracking/public/Logs/ga4.log' );

		error_log('prospect_service_event ' . "\n" . ' Lead ID - ' . get_option('wdm_cf_count'), 3 , './wp-content/plugins/ga4-ecommerce-funnel-tracking/public/Logs/ga4.log' );

		error_log("\n" . '-----------------------------------------------------------------------------' . "\n", 3 , './wp-content/plugins/ga4-ecommerce-funnel-tracking/public/Logs/ga4.log' );

		//  Error Logging for Debugging Puposes



		$submission = WPCF7_Submission::get_instance();
		if ($submission) {
        // Get form data
        $posted_data = $submission->get_posted_data();
		$form_id = isset($posted_data['form_id']) ? $posted_data['form_id'] : '';
		$form_data_array = array();

        // Loop through each field and store in the array
			foreach ($posted_data as $field_name => $field_value) {
				// Store each field in the array
				$form_data_array[$field_name] = $field_value;
			}
			$lead_number = get_option('wdm_cf_count');
			$form_data_array['lead_number'] = $lead_number;
			update_option('prospect_service_form_data_ga4', $form_data_array);
			$a = print_r($form_data_array,1);


			//  Error Logging for Debugging Puposes

			error_log('Form Data - ' . $a . "\n" . ' Lead ID - ' . get_option('wdm_cf_count'), 3, './wp-content/plugins/ga4-ecommerce-funnel-tracking/public/Logs/ga4.log'  );
			
			error_log("\n" . '-----------------------------------Date - ' . date('Y-m-d H:i:s') .'------------------------------------------' . "\n\n", 3 , './wp-content/plugins/ga4-ecommerce-funnel-tracking/public/Logs/ga4.log' );

			//  Error Logging for Debugging Puposes
			
			
		}
}


public function delete_ga4_option(){
	if (!isset($_POST['security']) || !wp_verify_nonce($_POST['security'], 'delete_option_nonce')) {
        wp_send_json_error('Invalid nonce');
    }

    // Check if the option name is provided
    if (!isset($_POST['option_name']) || empty($_POST['option_name'])) {
        wp_send_json_error('Option name is required');
    }

    $option_name = sanitize_text_field($_POST['option_name']);

    // Delete the option
    delete_option($option_name);

    wp_send_json_success('Option deleted successfully');
}

public function log_event(){
	if (!isset($_POST['security']) || !wp_verify_nonce($_POST['security'], 'log_event_nonce')) {
        wp_send_json_error('Invalid nonce');
    }

    // Check if the option name is provided
    if (!isset($_POST['data']) || empty($_POST['data'])) {
        wp_send_json_error('Log data is required.');
    }

    $log = sanitize_text_field($_POST['data']);

	$log = "\n" . $log . "\n"; 

    // Error logging
    // $res = var_dump(error_log($log, 3, './wp-content/plugins/ga4-ecommerce-funnel-tracking/public/Logs/ga4.log'));
    wp_send_json_success(error_log(print_r( $log, true ),3, plugin_dir_path( __FILE__ ) . '/Logs/ga4.log'));

    // wp_send_json_success();
}

    public function cf7_form_tracking()
    {
?>
    <script type="text/javascript">
        var wpcf7Elm = document.querySelector('.wpcf7');

        wpcf7Elm.addEventListener('wpcf7mailsent', function(event) {
			event.preventDefault();
            // jQuery('input[name="_wpcf7cf_options"]').on('change', function() {
				var form = event.target;
				// Create a new FormData object and pass the form element
				var formData = new FormData(form);

				// Serialize the form data
				var serializedData = Array.from(formData).reduce(function(result, pair) {
					result[pair[0]] = pair[1];
					return result;
				}, {});

				// Download E-book Form on URL /woocommerce-migration-checklist/ START
					// get form Fields
					var form_data = {};
					var first_name = serializedData['first_name'];
					var last_name = serializedData['last_name'];
					var your_email = serializedData['email'];
					var formID = serializedData['_wpcf7'];

					form_data = {
						'first_name': first_name,
						'last_name': last_name,
						'email': your_email,
					};
					

					// Push Data to dataLayer
					if(!first_name.toLowerCase().includes("test") && !last_name.toLowerCase().includes("test")){
						if(formID == '1017881' || formID == '1017867' || formID == '1026181'){
							dataLayer.push({
								'event': 'download',
								'form_id':	formID,
								'form_data': form_data,
								'download_looker_studio': true,
							});
						}
					}

				// Download E-book Form on URL /woocommerce-migration-checklist/ END
            // })
        }, false);
    </script>
    <?php
    }

    public function ir_view_demo()
    {
        if (is_page('instructor-role-for-learndash')) {
    ?>
        <script type="text/javascript">
            jQuery(document).ready(function($) {

                $('a[href*="/instructor-role-demo/"].ir-demo-btn-top').on('click', function(event) {
                    console.log('Ir Demo Button Top.');
                    dataLayer.push({
                        'event': 'ir_view_demo_tracking_header',
                        'Click Status': true,
                    });
                    console.log('IR Demo Button Event Data Pushed.');
                });

                $('a[href*="/instructor-role-demo/"].ir-demo-btn-highlight').on('click', function(event) {
                    console.log('Ir Demo Button Highlight.');
                    dataLayer.push({
                        'event': 'ir_view_demo_tracking_highlight',
                        'Click Status': true,
                    });
                    console.log('IR Demo Button Event Data Pushed.');
                });

                $('a[href*="/instructor-role-demo/"].ir-demo-btn-popup').on('click', function(event) {
                    console.log('Ir Demo Button Popup.');
                    dataLayer.push({
                        'event': 'ir_view_demo_tracking_popup',
                        'Click Status': true,
                    });
                    console.log('IR Demo Button Event Data Pushed.');
                });
            });
        </script>
    <?php
        }
    }


	public function elementor_popup()
    {
    ?>
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof window.elementorFrontend !== 'undefined') {
                window.addEventListener('elementor/popup/show', function(event) {
                    
					// Popup Displayed
					console.log('Popup Displayed.');

                    var popupId = event.detail.id;
                    console.log('Popup ID:', popupId);

					// Push data to dataLayer
					window.dataLayer.push({
                            'event': 'popup_viewed',
                            'popupId': popupId,
							'popup_viewed_looker_studio': true,
                        });

                    var popupType;
                    var popupEventView;
                    var popupEventClick;

                    if (popupId === 1024085) {
                        popupType = 'Product-popup';
                        popupEventView = 'Product-popup-view';
                        popupEventClick = 'Product-popup-click';
                    } else if (popupId === 1024082) {
                        popupType = 'CRO-popup';
                        popupEventView = 'CRO-popup-view';
                        popupEventClick = 'CRO-popup-click';
                    } else if (popupId === 1024079) {
                        popupType = 'Growth-popup';
                        popupEventView = 'Growth-popup-view';
                        popupEventClick = 'Growth-popup-click';
                    }

                    if (popupType) {
						// Push data to dataLayer
                        window.dataLayer.push({
                            'event': popupEventView,
                            'popupId': popupId,
                            'popup': popupType,
                        });
						
                    }

                        var popupElement = document.querySelector('#elementor-popup-modal-' + popupId);
                        var buttonElement = popupElement ? popupElement.querySelector('.elementor-button-text') : null;

                        if (buttonElement) {
                            console.log('Popup has a child with the class "elementor-button-text".');
                            buttonElement.addEventListener('click', function() {
								if (popupType) {
									// popupType Prsent
									// Push data to dataLayer
									window.dataLayer.push({
										'event': popupEventClick,
										'popupId': popupId,
										'popup': popupType,
									});
								}
								// Push data to dataLayer
								window.dataLayer.push({
										'event': 'popup_cta_click',
										'popupId': popupId,
										'popup_cta_click_looker_studio': true,
									});
                            });
                        } else {
                            console.log('Popup does not have a child with the class "elementor-button-text".');
                        }
                });

				window.addEventListener('elementor/popup/hide', function(event) {
					// Popup ID
					var popupId = event.detail.id;
					console.log('Popup Closed');
					console.log('PopupId - ', popupId);
					window.dataLayer.push({
						'event': 'popup_closed',
						'popupId': popupId,
						'popup_closed_looker_studio': true,
					});
				});
            }
        });
    </script>
<?php
    }
}
