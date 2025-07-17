<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://wisdmalbs.com
 * @since      1.0.0
 *
 * @package    Ga4_Ecommerce_Funnel_Tracking
 * @subpackage Ga4_Ecommerce_Funnel_Tracking/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Ga4_Ecommerce_Funnel_Tracking
 * @subpackage Ga4_Ecommerce_Funnel_Tracking/admin
 * @author     WisdmLabs <software@wisdmlabs.com>
 */
class Ga4_Ecommerce_Funnel_Tracking_Admin
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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct($plugin_name, $version)
	{

		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	 * Register the stylesheets for the admin area.
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
	 * Register the JavaScript for the admin area.
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
	}

	/**
	 * Adds the GTM Scripts menu page.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function gtm_script_menu()
	{
		add_menu_page(
			'GTM Scripts',
			'GA4 Ecommerce Funnel Tracking',
			'manage_options',
			'wdm-ga4-menu',
			array($this, 'gtm_scripts_settings'),
			'dashicons-tag'
		);
	}

	/**
	 * Renders the GTM Scripts settings page.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function gtm_scripts_settings()
	{
		echo '<div class="wrap">';
		echo '<h1>' . get_admin_page_title() . '</h1>';
		echo '<form method="post" action="options.php">';
		settings_fields('gtm_scripts_options');
		do_settings_sections('gtm_scripts');
		submit_button();
		echo '</form>';
		echo '</div>';
	}

	/**
	 * Initializes the GTM Scripts settings.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function gtm_scripts_init()
	{
		register_setting('gtm_scripts_options', 'gtm_head_script');
		register_setting('gtm_scripts_options', 'gtm_body_script');
		register_setting('gtm_scripts_options', 'debug_checkbox');
		register_setting('gtm_scripts_options', 'gtm_blocked_ips');
		add_settings_section('gtm_scripts_section', 'GTM Scripts', '', 'gtm_scripts');
		add_settings_field('gtm_head_script', 'GTM Head Script', array($this, 'gtm_head_script_callback'), 'gtm_scripts', 'gtm_scripts_section');
		add_settings_field('gtm_body_script', 'GTM Body Script', array($this, 'gtm_body_script_callback'), 'gtm_scripts', 'gtm_scripts_section');
		add_settings_field('gtm_blocked_ips', 'Blocked IP Addresses (one per line)', array($this, 'gtm_blocked_ips_callback'), 'gtm_scripts', 'gtm_scripts_section');

		// Edited by Mithilesh on 24-02-24 START
		// Add the callback for the new field
		add_settings_field('debug_checkbox', 'Debug Mode', array($this, 'debug_checkbox_callback'), 'gtm_scripts', 'gtm_scripts_section');
		// Edited by Mithilesh on 24-02-24 END
	}

	/**
	 * Callback for rendering the GTM Head Script textarea field.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function gtm_head_script_callback()
	{
		$gtm_head_script = get_option('gtm_head_script');
		echo '<textarea name="gtm_head_script" rows="5" cols="50">' . esc_textarea($gtm_head_script) . '</textarea>';
	}

	/**
	 * Callback for rendering the GTM Body Script textarea field.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function gtm_body_script_callback()
	{
		$gtm_body_script = get_option('gtm_body_script');
		echo '<textarea name="gtm_body_script" rows="5" cols="50">' . esc_textarea($gtm_body_script) . '</textarea>';
	}

	// Edited by Mithilesh on 24-02-24 START
	/**
	 * Callback for rendering the GTM Body Script textarea field.
	 *
	 * @since 1.0.2
	 * @return void
	 */
	public function debug_checkbox_callback()
	{
		$debug_checkbox = get_option('debug_checkbox');
		echo '<input type="checkbox" name="debug_checkbox" value="1" ' . checked(1, $debug_checkbox, false) . '>';
		echo '<span>Check this box if you are testing the Analytics events. (Under Development might not work yet.)</span>';
	}
	// Edited by Mithilesh on 24-02-24 END

	/**
	 * Callback for rendering the Blocked IPs textarea field.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function gtm_blocked_ips_callback()
	{
		$blocked_ips = get_option('gtm_blocked_ips', '');
		echo '<textarea name="gtm_blocked_ips" rows="5" cols="50" placeholder="e.g. 123.45.67.89&#10;111.222.333.444">' . esc_textarea($blocked_ips) . '</textarea>';
		echo '<p class="description">Enter one IP address per line that should be blocked from GTM tracking.</p>';
	}
}
