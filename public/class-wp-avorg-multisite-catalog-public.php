<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.audioverse.org
 * @since      1.0.0
 *
 * @package    Wp_Avorg_Multisite_Catalog
 * @subpackage Wp_Avorg_Multisite_Catalog/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wp_Avorg_Multisite_Catalog
 * @subpackage Wp_Avorg_Multisite_Catalog/public
 * @author     Ki Song <ki@audioverse.org>
 */
class Wp_Avorg_Multisite_Catalog_Public {

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
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wp-avorg-multisite-catalog-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wp-avorg-multisite-catalog-public.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Fetch data from the AV API
	 * 
	 * @param	string	$url	The url to be fetched
	 */
	function fetch_from_api( $url ) {
		
		$options = get_option($this->plugin_name);

		$baseURL = array_key_exists('baseURL', $options) ? $options['baseURL'] : '';
		$user = array_key_exists('user', $options) ? $options['user'] : '';
		$password = array_key_exists('password', $options) ? $options['password'] : '';

		$args = array(
		  'headers' => array(
		    'Authorization' => 'Basic ' . base64_encode( $user . ':' . $password )
		  )
		);

		$url = $baseURL . $url;

		$response = wp_remote_get( esc_url_raw( $url ), $args );
		return json_decode( wp_remote_retrieve_body( $response ), true );
	}

	/**
	 * Get the list of recordings from the API
	 * 
	 * @param	array	$data	Contains all the request params
	 */
	function get_recordings( $data = array() ) {
		$url = 'recordings';
		if ( isset($data['start']) ) {
			$url .= '?start=' . $data['start'];
		}

		return $this->fetch_from_api($url);
	}

	/**
	 * Since we are using ajax to get more recordings, we are using this function as a proxy to our real API and so our API credentials are not exposed on the client side
	 * Registers a new route using the WP REST API in order to get all the recordings by tag
	 */
	function recordings_api() {
		register_rest_route( $this->plugin_name . '/v1', '/tags/(?P<site>\w+)/category/(?P<category>\w+)', array(
			'methods' => 'GET',
			'callback' => array( $this, 'get_recordings'),
		) );
	}

	/**
	 * Wraps the API request and caching, if the $key is already in cache it returns it, otherwise makes an HTTP request
	 * Useful for shortcodes in the same page that access to the same data
	 * 
	 * @param	string	$key	recording id
	 */
	function get_recording( $key ) {
		if (!wp_cache_get($key)) {
			$response = $this->fetch_from_api('recordings/' . $key);
			wp_cache_add($key, $response['result'][0]['recordings']);
			return $response['result'][0]['recordings'];
		} else {
			return wp_cache_get($key);
		}
	}

	/**
	 * Shortcode function that returns a list or recordings
	 * 
	 * @param	array	$atts	shortcode attributes
	 */
	function get_list( $atts ) {

		$options = get_option($this->plugin_name);
		$detailPageURL = array_key_exists('detailPageURL', $options) ? $options['detailPageURL'] : '';
		$query = parse_url($detailPageURL, PHP_URL_QUERY);
		$hasParameters = false;
		
		// Returns a string if the URL has parameters or NULL if not
		if ( $query ) {
			$detailPageURL .= '&recording_id=';
		} else {
			$detailPageURL .= '?recording_id=';
		}
		
		$recordings = $this->get_recordings();

		$html = '<div class="grid" id="avgrid">';
			
		foreach( $recordings['result'] as $key=>$recording ) {
			$html .= '
			<div class="cell">
				<a href="' . $detailPageURL . $recording['recordings']['id'] . '">
					<img src="//unsplash.it/' . (800 + $key) . '/500" class="responsive-image">
					<div class="inner-content">
						<div class="title">' . $recording['recordings']['title'] . '</div>
						<div class="subtitle">' . $this->get_speaker_name( $recording['recordings'] ) . '</div>
					</div>
				</a>
			</div>';
		}
			
		$html .= '</div>';
		
		$options = get_option($this->plugin_name);
		$html .= '
		<div class="show-more">
			<a href="javascript:void(0)" onclick="getRecordings(this)" data-items-per-page="' . $options['itemsPerPage'] . '" data-detail-page-url="' . $detailPageURL . '">Show more</a>
		</div>';
		
		return $html;
	}

	/**
	 * Shortcode function that returns the recording's media player
	 * 
	 * @param	array	$atts	shortcode attributes
	 */
	function get_recording_media( $atts ) {
		if ( isset( $_GET['recording_id'] ) && $_GET['recording_id'] != null ) {
			$response = $this->get_recording($_GET['recording_id']);
			
			return '
			<div class="video-container">
				<div class="video-wrapper">
					<div class="embed-responsive embed-responsive-16by9">
						<iframe class="embed-responsive-item" src="https://www.audioverse.org/english/embed/media/' . $response['id'] . '?image=https://source.unsplash.com/collection/1360835" scrolling="no" frameBorder="0" allowfullscreen></iframe>
					</div>
				</div>
			</div>';
			
		} else {
			return 'No recording id';
		}
	}

	/**
	 * Shortcode function that returns the recording's title
	 * 
	 * @param	array	$atts	shortcode attributes
	 */
	function get_recording_title( $atts ) {
		if ( isset( $_GET['recording_id'] ) && $_GET['recording_id'] != null ) {
			$response = $this->get_recording($_GET['recording_id']);
			return '<h1>' . $response['title'] . '</h1>';
		} else {
			return 'No recording id';
		}
	}

	/**
	 * Shortcode function that returns the recording's description
	 * 
	 * @param	array	$atts	shortcode attributes
	 */
	function get_recording_desc( $atts ) {
		if ( isset( $_GET['recording_id'] ) && $_GET['recording_id'] != null ) {
			$response = $this->get_recording($_GET['recording_id']);
			return '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>';
		} else {
			return 'No recording id';
		}
	}

	/**
	 * Shortcode function that returns the recording's speaker
	 * 
	 * @param	array	$atts	shortcode attributes
	 */
	function get_recording_speaker( $atts ) {
		if ( isset( $_GET['recording_id'] ) && $_GET['recording_id'] != null ) {
			return $this->get_speaker_name( $this->get_recording($_GET['recording_id']) );
		} else {
			return 'No recording id';
		}
	}
	
	/**
	 * gets the speaker's name
	 * 
	 * @param	array	$item	recording data
	 */
	function get_speaker_name( $item ) {
		$speaker = 'Anonymous Presenter';
		
		if ( isset( $item['presenters'] ) ) {
			if ( sizeof( $item['presenters'] ) > 1 ) {
				$speaker = 'Various Presenters';
			} else if ( sizeof( $item['presenters'] ) > 0 ) {
				$speaker = $item['presenters'][0]['givenName'] . ' ' . $item['presenters'][0]['surname'];
			}
		}
		
		return $speaker;
	}

}
