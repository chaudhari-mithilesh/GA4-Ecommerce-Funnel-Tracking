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

		wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/ga4-ecommerce-funnel-tracking-public.css', array(), $this->version, 'all');
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

		wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/ga4-ecommerce-funnel-tracking-public.js', array('jquery'), $this->version, false);
		wp_enqueue_script('wp-form-tracking-js', plugin_dir_url(__FILE__) . 'js/wp-form-tracking.js', array('jquery'), $this->version, false);
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
		wp_enqueue_script('gtm-head-script', plugin_dir_url(__FILE__) . '/js/gtm_head_script.js', array(), $this->version, false);
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
?>
		<!-- Google Tag Manager (noscript) -->
		<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-5T4FGTL" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
		<!-- End Google Tag Manager (noscript) -->
	<?php
	}

	public function wp_form_tracking($confirmation, $form_data, $fields)
	{
		// die('Function called');
		$data = array();
		$data['name'] = $fields[0]['value'];
		$data['email'] = $fields[1]['value'];
		$data['comment'] = $fields[2]['value'];

		// wp_enqueue_script('ga4_form_event', plugin_dir_url(__FILE__) . 'js/ga4_form_event.js', array(), $this->version, false);
		// wp_localize_script('ga4_form_event', 'form_data', $data);

	?>
		<script>
			console.log("form-tracking-file");
			var form_data = <?php echo json_encode($data); ?>;
			dataLayer.push({
				event: "wp_form_tracking",
				ecommerce: {
					name: form_data["name"],
					email: form_data["email"],
					comment: form_data["comment"],
				},
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
		if (!is_shop()) {
			return;
		}

		$data = array(
			'currency' => get_woocommerce_currency(),
			'item_list_id' => '12345',
			'item_list_name' => 'All Products',
			'items' => array(),
		);

		$args = array(
			'post_type' => 'product',
			'posts_per_page' => -1,
		);
		$products = wc_get_products($args);

		foreach ($products as $index => $product) {
			$product_data = $product->get_data();

			$item_data = array(
				'item_list_id' => '12345',
				'item_list_name' => 'All Products',
				'item_id' => $product->get_id(),
				'item_name' => $product_data['name'],
				'index' => $index,
				'discount' => 0,
				'item_brand' => bloginfo('name'),
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
			'item_brand' => bloginfo('name'),
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

	// public function checkout_event()
	// {
	// 	if (!is_checkout()) {
	// 		return;
	// 	}

	// 	$cart = WC()->cart;
	// 	$cart_items = $cart->get_cart();

	// 	$data = array();
	// 	foreach($cart_items as $cart_item_key => $cart_item) {
	// 		$product_id = $cart_item['product_id'];
	// 		$quantity = $cart_item['quantity'];
	// 		$product = $cart_item['data'];
	// 		$data[] = array(
	// 			'currency' => get_woocommerce_currency(),

	// 		)
	// 	}
	// }


	function remove_from_cart_event($cart_item_key)
	{
		die("Item Removed From Cart.");
		$cart = WC()->cart;
		$removed_item = $cart->get_removed_cart_item($cart_item_key);
		$product_id = $removed_item['product_id'];
		$request_quantity = $removed_item['quantity'];

		$product_data = wc_get_product($product_id);
		$category_ids = $product_data->get_category_ids();

		$data = array(
			'currency' => get_woocommerce_currency(),
			'value' => (float) $product_data->get_price(),
			'item' => array(
				'item_id' => (string) $product_id,
				'item_name' => $product_data->get_name(),
				'item_brand' => "Earth Store",
				'item_category' => !empty($category_ids) ? get_term($category_ids[0], 'product_cat')->name : "Uncategorized",
				'price' => (float) $product_data->get_price(),
				'quantity' => $request_quantity
			)
		);

		wp_enqueue_script('remove_from_cart', plugin_dir_url(__FILE__) . 'js/remove_from_cart.js', array('jquery'), $this->version, false);
		wp_localize_script('remove_from_cart', 'item_data', $data);
	}
}
