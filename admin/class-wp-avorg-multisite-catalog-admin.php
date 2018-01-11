<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.audioverse.org
 * @since      1.0.0
 *
 * @package    Wp_Avorg_Multisite_Catalog
 * @subpackage Wp_Avorg_Multisite_Catalog/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wp_Avorg_Multisite_Catalog
 * @subpackage Wp_Avorg_Multisite_Catalog/admin
 * @author     Ki Song <ki@audioverse.org>
 */
class Wp_Avorg_Multisite_Catalog_Admin {

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
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wp_Avorg_Multisite_Catalog_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wp_Avorg_Multisite_Catalog_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		// CSS stylesheet for Color Picker
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wp-avorg-multisite-catalog-admin.css', array( 'wp-color-picker' ), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wp_Avorg_Multisite_Catalog_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wp_Avorg_Multisite_Catalog_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_media();
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wp-avorg-multisite-catalog-admin.js', array( 'jquery', 'wp-color-picker' ), $this->version, false );

	}

	/**
	 * Register the administration menu for this plugin into the WordPress Dashboard menu.
	 *
	 * @since    1.0.0
	 */

	public function add_plugin_admin_menu() {

	    /*
	     * Add a settings page for this plugin to the Settings menu.
	     *
	     * NOTE:  Alternative menu locations are available via WordPress administration menu functions.
	     *
	     *        Administration Menus: http://codex.wordpress.org/Administration_Menus
	     *
	     */
	    add_menu_page( 'AudioVerse Catalog', 'AudioVerse', 'manage_options', $this->plugin_name, array($this, 'display_plugin_setup_page'), 'dashicons-admin-generic', 4);
	}

	 /**
	 * Add settings action link to the plugins page.
	 *
	 * @since    1.0.0
	 */

	public function add_action_links( $links ) {
	    /*
	    *  Documentation : https://codex.wordpress.org/Plugin_API/Filter_Reference/plugin_action_links_(plugin_file_name)
	    */
	   $settings_link = array(
	    '<a href="' . admin_url( 'options-general.php?page=' . $this->plugin_name ) . '">' . __('Settings', $this->plugin_name) . '</a>',
	   );
	   return array_merge(  $settings_link, $links );

	}

	/**
	 * Render the settings page for this plugin.
	 *
	 * @since    1.0.0
	 */

	public function display_plugin_setup_page() {
	    include_once( 'partials/' . $this->plugin_name . '-admin-display.php' );
	}

	/**
	 * Update options
	 *
	 * @since    1.0.0
	 */
	public function options_update() {
	    register_setting($this->plugin_name, $this->plugin_name, array($this, 'validate'));
	}

	/**
	 * Validate form
	 *
	 * @since    1.0.0
	 */
	public function validate($input) {
	    // All checkboxes inputs        
		$valid = array();
		
		$valid['baseURLFormerAPI'] = (isset($input['baseURLFormerAPI']) && !empty($input['baseURLFormerAPI'])) ? sanitize_text_field($input['baseURLFormerAPI']) : '';
		$valid['user'] = (isset($input['user']) && !empty($input['user'])) ? sanitize_text_field($input['user']) : '';
		$valid['password'] = (isset($input['password']) && !empty($input['password'])) ? sanitize_text_field($input['password']) : '';
		
		$valid['baseURL'] = (isset($input['baseURL']) && !empty($input['baseURL'])) ? sanitize_text_field($input['baseURL']) : '';
		$valid['token'] = (isset($input['token']) && !empty($input['token'])) ? sanitize_text_field($input['token']) : '';

		$valid['detailPageID'] = (isset($input['detailPageID']) && !empty($input['detailPageID'])) ? sanitize_text_field($input['detailPageID']) : '';
		$valid['site'] = (isset($input['site']) && !empty($input['site'])) ? sanitize_text_field($input['site']) : '';
		$valid['itemsPerPage'] = (isset($input['itemsPerPage']) && !empty($input['itemsPerPage'])) ? sanitize_text_field($input['itemsPerPage']) : '';

		$valid['playerLibrary'] = (isset($input['playerLibrary']) && !empty($input['playerLibrary'])) ? sanitize_text_field($input['playerLibrary']) : '';
		$valid['playerLicense'] = (isset($input['playerLicense']) && !empty($input['playerLicense'])) ? sanitize_text_field($input['playerLicense']) : '';

		$valid['overlayBackgroundColor'] = (isset($input['overlayBackgroundColor']) && !empty($input['overlayBackgroundColor'])) ? sanitize_text_field($input['overlayBackgroundColor']) : '';
		$valid['overlayHeight'] = (isset($input['overlayHeight']) && !empty($input['overlayHeight'])) ? sanitize_text_field($input['overlayHeight']) : '';
		$valid['descriptionColor'] = (isset($input['descriptionColor']) && !empty($input['descriptionColor'])) ? sanitize_text_field($input['descriptionColor']) : '';
		$valid['descriptionLines'] = (isset($input['descriptionLines']) && !empty($input['descriptionLines'])) ? sanitize_text_field($input['descriptionLines']) : '';

	    return $valid;
	}

}
