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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wp-avorg-multisite-catalog-public.css.php', array(), $this->version, 'all' );

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
		if(isset($_GET['recording_id'])){
		$recordingData = $this->get_recording($_GET['recording_id']);
		$recording = $recordingData['recording'];
		$imageUrl = isset( $recording['coverImage'] ) ? $recording['coverImage']['url'] : $recording['imageWithFallback']['url'];
		$audioUrl = sizeof($recording['audioFiles']) ? $recording['audioFiles'][sizeof($recording['audioFiles']) - 1]['url'] : '';
		}

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wp-avorg-multisite-catalog-public.js', array( 'jquery', 'jw-player' ), $this->version, true );
		$options = get_option($this->plugin_name);
		$playerLibrary = isset($options['playerLibrary']) ? $options['playerLibrary'] : '';
		$jwplayerLicense = isset($options['playerLicense']) ? $options['playerLicense'] : '';


		wp_register_script( 'jw-player', $playerLibrary, array(), $this->version, 'all' );
		wp_localize_script( $this->plugin_name, 'jwPlayerOop',
        array( 
            'image_url' => $imageUrl,
            'audio_url' => $audioUrl,
			'lisence' => $jwplayerLicense
        )
    );

	}

	/**
	 * Fetch data from the AV API
	 * 
	 * @param	string	$url	The url to be fetched
	 * @param	array	$headers	array with the headers
	 */
	// STEP - 1.3
	function fetch_from_api( $url, $headers, $data ) {

		$args = array(
		  'headers' => $headers,
		  'body' => $data
		);
		$response = wp_remote_post( esc_url_raw( $url ), $args );
		return json_decode( wp_remote_retrieve_body( $response ), true );
	}

	/**
	 * Get the list of recordings from the API
	 * 
	 * @param	array	$params	request params
	 */
	// STEP 1.2
	function get_recordings( $params ) {
		$options = get_option($this->plugin_name);
		$itemsPerPage = isset($options['itemsPerPage']) ? $options['itemsPerPage'] : '';
		// for single tag
		$tag = $params;
		$tagName = empty($tag)  ? "null" : 'tagName:"'.$tag.'"';
		$site = isset($options['site']) ? $options['site'] : '';
		$websiteId = empty($site)  ? "" : 'websiteIds:'.$site.'';
		$body = <<< GQL
			query {
				recordings(language:ENGLISH, first:$itemsPerPage, $tagName, $websiteId){
				nodes {
				id
				title
				coverImage {
					name
					url(size:800)
				  }
			      imageWithFallback {
					url(size:800)
					name
				  }
				description
				persons{
					givenName
				  }
				duration
				audioFiles{
					filename
					url
				}
				videoFiles{
					filename
					url
				}
				}
				pageInfo{
					hasNextPage
					hasPreviousPage
					endCursor
					startCursor
				  }
			}
			}
		GQL;
		$baseURLGraphQl = isset($options['baseURLGraphQl']) ? $options['baseURLGraphQl'] : '';
		$token = isset($options['token']) ? $options['token'] : '';
		$site = isset($options['site']) ? $options['site'] : '';

		// add per page param
		// Returns a string if the URL has parameters or NULL if not
		$query = parse_url($params, PHP_URL_QUERY);
		// for multiple tags
		// $params .= ( $query ? '&' : '?' ) . 'per_page=' . $itemsPerPage;
		$data = array ('query' => $body);
		$url = $baseURLGraphQl;
		$headers = array('Authorization' => 'Bearer ' . $token);
		return $this->format_recordings($this->fetch_from_api($url, $headers, $data));
	}

	/**
	 * Get the list of recordings from the API
	 * 
	 * @param	array	$data	Contains all the request params
	 */
	function rest_route_recordings_handler( $data ) {
		$next_page = ($data['url']);
		$tagName = empty($data['tag'])  ? "null" : 'tagName:"'.$data['tag'].'"';
		$options = get_option($this->plugin_name);
		$itemsPerPage = isset($options['itemsPerPage']) ? $options['itemsPerPage'] : '';
		$site = isset($options['site']) ? $options['site'] : '';
		$websiteId = empty($site)  ? "" : 'websiteIds:'.$site.'';
		$body = <<< GQL
			query {
				recordings(language:ENGLISH, first:$itemsPerPage, after:"$next_page", $tagName, $websiteId){
				nodes {
				id
				title
				coverImage {
					name
					url(size:800)
				  }
			      imageWithFallback {
					url(size:800)
					name
				  }
				description
				persons{
					givenName
				  }
				duration
				audioFiles{
					filename
					url
				}
				videoFiles{
					filename
					url
				}
				}
				pageInfo{
					hasNextPage
					hasPreviousPage
					endCursor
					startCursor
				  }
			}
			}
			GQL;
		$dataa = array ('query' => $body);
		$token = isset($options['token']) ? $options['token'] : '';
		$headers = array('Authorization' => 'Bearer ' . $token);
		$baseURLGraphQl = isset($options['baseURLGraphQl']) ? $options['baseURLGraphQl'] : '';
		$url = $baseURLGraphQl;
		return $this->format_recordings($this->fetch_from_api($url, $headers, $dataa));
	}

	/**
	 * Since we are using ajax to get more recordings, we are using this function as a proxy to our real API and so our API credentials are not exposed on the client side
	 * Registers a new route using the WP REST API in order to get all the recordings by tag
	 */
	function rest_route_recordings() {
		register_rest_route( $this->plugin_name . '/v1', '/tags', array(
			'methods' => 'GET',
			'callback' => array( $this, 'rest_route_recordings_handler'),
			) );
	}

	/**
	 * format recordings
	 * 
	 * @param	object	$data	recordings list
	 */

	//  STEP 1.4
	function format_recordings( $recordings ) {
		if (is_array($recordings['data']) || is_object($recordings['data'])) {
			
			foreach( $recordings['data']['recordings']['nodes']  as $key=>$recording ) {
				$duration_info = getdate($recording['duration']);
				$format = $duration_info['hours'] > 0 ? 'H:i:s' : 'i:s';
				$recordings['data']['recordings']['nodes'][$key]['duration_formatted'] = gmdate($format, $recording['duration']);
				$recordings['data']['recordings']['nodes'][$key]['speaker_name'] = $this->get_speaker_name( $recording );
				$recordings['data']['recordings']['nodes'][$key]['sanitized_title'] = sanitize_title($recording['title']);
			}			
			return $recordings;
		}
	}

	/**
	 * Wraps the API request and caching, if the $key is already in cache it returns it, otherwise makes an HTTP request
	 * Useful for shortcodes in the same page that access to the same data
	 * 
	 * @param	string	$key	recording id
	 */
	function get_recording( $key ) {
		if (!wp_cache_get($key)) {
			$options = get_option($this->plugin_name);
			$baseURLFormerAPI = isset($options['baseURLGraphQl']) ? $options['baseURLGraphQl'] : '';
			$user = isset($options['user']) ? $options['user'] : '';
			$password = isset($options['password']) ? $options['password'] : '';
			$headers = array( 'Authorization' => 'Basic ' . base64_encode( $user . ':' . $password ) );
			$body =sprintf("
			query {
				recording(id:%s){
				title,
				description,
				coverImage {
					name
					url(size:800)
				  },
			      imageWithFallback {
					url(size:800)
					name
				  },
				  audioFiles{
					url
				  }
				  persons{
					givenName
					surname
				  }
			}
		}
		", $key);

			$data = array ('query' => $body);
			$response = $this->fetch_from_api($baseURLFormerAPI, $headers, $data);
			//////////////////////////////////////////////////////////////////////////////////
			// wp_cache_add($key, $response['result'][0]['recordings']);
			// return $response['result'][0]['recordings'];
			/////////////////////////////////////////////////////////////////////////////////
			wp_cache_add($key, $response['data']);
			return $response['data'];
		} else {
			return wp_cache_get($key);
		}
	}

	/**
	 * Shortcode function that returns a list or recordings
	 * 
	 * @param	array	$atts	shortcode attributes
	 */

	//  STEP 1 
	function get_list( $atts, $content = null, $tag = '' ) {

		// normalize attribute keys, lowercase
		$atts = array_change_key_case((array)$atts, CASE_LOWER);

		$params = '';
		if ( isset($atts['tags']) ) {
			$tags = explode(",", $atts['tags']);
			// for multiple tags
			// $params = '?tags[0]=' . implode('&tags[0]=', $tags);
			// for single tag
			$params = $tags[0];
		}
		
		// STEP 1.1
		$recordings = $this->get_recordings($params);
		
		$options = get_option($this->plugin_name);
		$detailPageID = isset($options['detailPageID']) ? $options['detailPageID'] : '';

		$html = '<div class="grid" id="avgrid">';
		if (is_array($recordings['data']) || is_object($recordings['data'])) {
		foreach( $recordings['data']['recordings']['nodes'] as $key=>$recording ) {
				// // // // // // // // // // // // // // // // // // // // // // ///////////////////////////////////////////////////////////////////
				$imageUrl = isset( $recording['coverImage'] ) ? $recording['coverImage']['url'] : $recording['imageWithFallback']['url'];
				$detailPage = get_permalink($detailPageID) . '?' . $recording['sanitized_title'] . '&recording_id=' . $recording['id'];
				$description = empty( $recording['description'] ) ? 'No Description' : $recording['description'];
				$html .= '
				<div class="cell">
					<a href="' . $detailPage . '">
						<img src="' . $imageUrl . '" class="responsive-image">
						<div class="backdrop">
							<div class="duration"><span class="play-icon"></span>' . $recording['duration_formatted'] . '</div>
							<div class="inner-content">
								<div class="title">' . $recording['title'] . '</div>
								<div class="subtitle">' . $recording['speaker_name'] . '</div>
							</div>
						</div>
						<div class="overlay">
							<div class="text">' . $description . '</div>
						</div>
					</a>
				</div>';
			}
		}	
		$html .= '</div>';
		
		$options = get_option($this->plugin_name);
		$html .= '
		<div class="show-more">
		<a href="javascript:void(0)" id="more" onclick="getRecordings(this)" data-next="' . $recordings['data']['recordings']['pageInfo']['endCursor'] . '" data-detail-permalink="' . get_permalink($detailPageID) . '" data-tags="' . $params .'">Show more</a>
		</div>';
		
		return $html;
	}


	/**
	 * Shortcode function that returns the recording's media player
	 * 
	 * @param	array	$atts	shortcode attributes
	 */
	function get_recording_media( $atts ) {

		if ( !empty( $_GET['recording_id'] ) ) {

			$options = get_option($this->plugin_name);
			wp_enqueue_script( 'jw-player' );
			$recordingData = $this->get_recording($_GET['recording_id']);
			$recording = $recordingData['recording'];
			return '
			<div class="video-container">
				<div class="video-wrapper">
					<div class="embed-responsive embed-responsive-16by9">
						<div id="mediaplayer">Loading the player...</div>
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
		if ( !empty( $_GET['recording_id'] ) ) {
			$recording = $this->get_recording($_GET['recording_id']);
			return '<h1>' . $recording['recording']['title'] . '</h1>';
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
		if ( !empty( $_GET['recording_id'] ) ) {
			$recording = $this->get_recording($_GET['recording_id']);
			return $recording['recording']['description'];
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
		if ( !empty( $_GET['recording_id'] ) ) {
			$recordingData = $this->get_recording($_GET['recording_id']);
			$recording = $recordingData['recording'];
			$speaker = 'Anonymous Presenter';
			if ( isset( $recording['persons'] ) ) {
				if ( sizeof( $recording['persons'] ) > 1 ) {
					$speaker = 'Various Presenters';
				} else if ( sizeof( $recording['persons'] ) > 0 ) {
					$speaker = $recording['persons'][0]['givenName'] . ' ' . $recording['persons'][0]['surname'];
				}
			}

			return $speaker;
		} else {
			return 'No recording id';
		}
	}
	
	/**
	 * gets the speaker's name
	 * 
	 * @param	array	$recording	recording data
	 */
	function get_speaker_name( $recording ) {
		$speaker = 'Anonymous Presenter';
		
		if ( isset( $recording['persons'] ) ) {
			if ( sizeof( $recording['persons'] ) > 1 ) {
				$speaker = 'Various Presenters';
			} else if ( sizeof( $recording['persons'] ) > 0 ) {
				$speaker = $recording['persons'][0]['givenName'];
			}
		}
		
		return $speaker;
	}

	/**
	 * filter the document title
	 * 
	 * @param	array	$title	title data
	 */
	function filter_document_title_parts($title) {
		$options = get_option($this->plugin_name);
		if ( isset( $_GET['recording_id'] ) && isset( $options['detailPageID'] ) && $options['detailPageID'] == get_the_ID() ) {
			$recording = $this->get_recording($_GET['recording_id']);
			$title['title'] = $recording['recording']['title'];
		}
		return $title;
	}

	/**
	 * filter the document title
	 * 
	 * @param	array	$title	title data
	 */
	function filter_wp_title($title) {
		$options = get_option($this->plugin_name);
		if ( isset( $_GET['recording_id'] ) && isset( $options['detailPageID'] ) && $options['detailPageID'] == get_the_ID() ) {
			$recording = $this->get_recording($_GET['recording_id']);
			$title = $recording['recording']['title'];
		}
		return $title;
	}

	/**
	 * filter the page/post title
	 * 
	 * @param	string	$title	the title
	 * @param	int		$id		post id
	 */
	function filter_the_title($title, $id = null) {
		$options = get_option($this->plugin_name);
		if ( isset( $_GET['recording_id'] ) && isset( $options['detailPageID'] ) && $options['detailPageID'] == $id ) {
			$recording = $this->get_recording($_GET['recording_id']);
			return $recording['recording']['title'];
		}
		return $title;
	}

	/**
	 * Adding the Open Graph in the Language Attributes
	 * 
	 * @param	string	$output	the output
	 */
	function add_opengraph_doctype( $output ) {
		return $output . ' xmlns:og="http://opengraphprotocol.org/schema/" xmlns:fb="http://www.facebook.com/2008/fbml"';
	}

	/**
	 * Add Open Graph Meta Info
	 * 
	 */
	function insert_opengraph_in_head() {
		// echo '<meta property="fb:admins" content="YOUR USER ID"/>';
		echo '<meta property="og:title" content="' . get_the_title() . '"/>';
		echo '<meta property="og:type" content="article"/>';
		echo '<meta property="og:url" content="' . 'http//' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . '"/>';
		echo '<meta property="og:site_name" content="' . get_bloginfo( 'name' ) . '"/>';

		$options = get_option($this->plugin_name);
		if ( isset( $_GET['recording_id'] ) && isset( $options['detailPageID'] ) && $options['detailPageID'] == get_the_ID() ) {
			$recording = $this->get_recording($_GET['recording_id']);
			echo '<meta property="og:description" content="' . $recording['description'] . '"/>';

			$image = isset( $recording['coverImage'] ) ? $recording['coverImage']['url'] : $recording['imageWithFallback']['url'];
			echo '<meta property="og:image" content="' . esc_attr( $image ) . '"/>';
		} else {
			echo '<meta property="og:description" content="' . get_bloginfo( 'description' ) . '"/>';

			$custom_logo_id = get_theme_mod( 'custom_logo' );
			$image = wp_get_attachment_image_src( $custom_logo_id , 'full' );
			echo '<meta property="og:image" content="' . $image[0] . '"/>';
		}
	}

	/**
	 * Override canonical url
	 * 
	 */
	function rel_canonical_with_custom_tag_override() {
		echo '<link rel="canonical" href="' . 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . '"/>';
	}

}
