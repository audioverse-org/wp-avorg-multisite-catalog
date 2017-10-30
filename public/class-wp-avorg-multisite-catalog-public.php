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

	function show_list( $atts ) {
		$url = 'https://api2.audioverse.org/recordings';
		$user = 'audioverse';
		$pass = 'j3$u$l0v3sM3';
		$args = array(
		  'headers' => array(
		    'Authorization' => 'Basic ' . base64_encode( $user . ':' . $pass )
		  )
		);

		$response = wp_remote_get( esc_url_raw( $url ), $args );
		$recordings = json_decode( wp_remote_retrieve_body( $response ), true );

		?>

	  <div class='grid' id="avgrid">
	    
		  <?php foreach( $recordings['result'] as $recording ) { ?>
		    <div class="cell">
					<img src="http://placehold.it/800x800" class="responsive-image">
					<div class="inner-content">
						<div class="title"><?php echo $recording['recordings']['title'] ?></div>
						<div class="subtitle">Subtitle</div>
					</div>
				</div>
		  <?php } ?>
	    
	  </div>
	
		<div class="show-more">
			<?php $options = get_option($this->plugin_name); ?>
			<a href="javascript:void(0)" onclick="getRecordings(this)" data-items-per-page="<?php echo $options['itemsPerPage']?>">Show more</a>
		</div>

		<?php
	}
	
	function getRecordings( $data ) {
		$url = 'https://api2.audioverse.org/recordings';
		if ( isset($data['start']) ) {
			$url .= '?start=' . $data['start'];
		}
		
		$user = 'audioverse';
		$pass = 'j3$u$l0v3sM3';
		$args = array(
		  'headers' => array(
		    'Authorization' => 'Basic ' . base64_encode( $user . ':' . $pass )
		  )
		);

		$response = wp_remote_get( esc_url_raw( $url ), $args );
		return json_decode( wp_remote_retrieve_body( $response ), true );
	}
	
	function recordings_api() {
	  register_rest_route( $this->plugin_name . '/v1', '/tags/(?P<site>\w+)/category/(?P<category>\w+)', array(
	    'methods' => 'GET',
	    'callback' => array( $this, 'getRecordings'),
	  ) );
	}

}
