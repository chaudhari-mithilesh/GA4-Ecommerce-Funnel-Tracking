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

	public function gtm_head_script()
	{
		wp_enqueue_script('gtm-head-script', plugin_dir_url(__FILE__) . '/js/gtm_head_script.js', array(), $this->version, false);
	}

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
		wp_enqueue_script('wp_form_tracking', plugin_dir_url(__FILE__) . 'js/wp_form_tracking.js', array(), $this->version, false);
		if (defined('DOING_AJAX') && DOING_AJAX) {
			wp_enqueue_script('wp_form_tracking', plugin_dir_url(__FILE__) . 'js/wp_form_tracking.js', array(), $this->version, true);
		}
		wp_localize_script('wp_form_tracking', 'form_data', $data);
	}

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
				'item_brand' => "Earth Store",
				'item_category' => !empty($category_ids) ? get_term($category_ids[0], 'product_cat')->name : "Uncategorized",
				'price' => (float) $product_data->get_price(),
				'quantity' => $request_quantity
			)
		);

		wp_enqueue_script('add_to_cart', plugin_dir_url(__FILE__) . 'js/add_to_cart.js', array('jquery'), $this->version, false);
		wp_localize_script('add_to_cart', 'item_data', $data);
	}

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
				'item_id' => $product->get_id(),
				'item_name' => $product_data['name'],
				'index' => $index,
				'discount' => 0,
				'item_brand' => 'Earth Store',
				'item_category' => 'Uncategorized',
				'item_list_id' => '12345',
				'item_list_name' => 'All Products',
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
}
