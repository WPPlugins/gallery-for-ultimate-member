<?php
/*
Plugin Name: User Gallery Lite for Ultimate Member
Plugin URI: https://suiteplugins.com/
Description: Allow your user to upload photos from their Ultimate Member profile
Version: 1.0.2
Author: SuitePlugins
Author URI: https://suiteplugins.com/
*/

define( 'UM_GALLERY_LITE_STORE_URL', 'https://suiteplugins.com' );
define( 'UM_GALLERY_LITE_ITEM_NAME', 'User Gallery Lite for UltimateMembers' );
require_once(ABSPATH.'wp-admin/includes/plugin.php');
/**
 * Check if Class exists
 */
if ( ! class_exists( 'UM_Gallery_Pro' ) ):
	/**
	 *	Setup Class
	 */
	class UM_Gallery{

		protected static $_instance = null;
		/**
		 * Main UM_Gallery Instance
		 *
		 * Ensures only one instance of UM_Gallery is loaded or can be loaded.
		 *
		 * @static
		 * @return UM_Gallery - Main instance
		 */
		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}
		/**
		 * Initiate construct
		 */
		 public function __construct(){
			/** Paths *************************************************************/

			// Setup some base path and URL information
			$this->file       		= __FILE__;
			$this->basename   		= apply_filters( 'um_gallery_plugin_basenname', plugin_basename( $this->file ) );
			$this->plugin_dir 		= apply_filters( 'um_gallery_plugin_dir_path',  plugin_dir_path( $this->file ) );
			$this->plugin_url 		= apply_filters( 'um_gallery_plugin_dir_url',   plugin_dir_url ( $this->file ) );
			$this->plugin_slug 		= '';
			$upload_dir 			= wp_upload_dir();
			$this->gallery_path 	= $upload_dir['basedir'].'/um-gallery';
			$this->gallery_url_path = $upload_dir['baseurl'].'/um-gallery';
			add_action( 'plugins_loaded', array($this, 'load_plugin_textdomain') );
			$this->includes();
			$this->setup_hooks();
		}
		/**
		*	Contains hooks
		*
		**/
		public function setup_hooks(){
			add_action( 'wp_enqueue_scripts', array($this, 'add_scripts') );
			//create an action and try to place content below the profile fields
			add_action( 'init', array($this, 'plugin_classes') );
		}
		/**
		 * Initiated plugin classes
		 *
		 */
		public function plugin_classes(){
			//$this->activity 	= new UM_Gallery_Activity();
			$this->admin 		= new UM_Gallery_Pro_Admin();
			$this->ajax 		= new UM_Gallery_Pro_AJAX();
			$this->template		= new UM_Gallery_Pro_Template();
			$this->shortcode	= new UM_Gallery_Shortcodes();
		}

		/**
		 * Load language file
		 *
		 */
		public function load_plugin_textdomain() {
			//taken from um-gallery-pro
			$locale = apply_filters( 'plugin_locale', get_locale(), 'um-gallery-pro' );

			// Allow upgrade safe, site specific language files in /wp-content/languages/um-gallery-pro/
			load_textdomain( 'um-gallery-pro', WP_LANG_DIR.'/um-gallery-pro-'.$locale.'.mo' );

			$plugin_rel_path = apply_filters( 'um_gallery_pro_tranlsation_path', dirname( plugin_basename( __FILE__ ) ) . '/languages' );

			load_plugin_textdomain( 'um-gallery-pro', false, $plugin_rel_path );
		}

		/**
		 * Include necessary files
		 */
		public function includes(){
			require_once( $this->plugin_dir . 'includes/um-gallery-admin.php');
			require_once( $this->plugin_dir . 'includes/class-um-gallery-template.php');
			require_once( $this->plugin_dir . 'includes/um-gallery-ajax.php');
			require_once( $this->plugin_dir . 'includes/um-gallery-shortcodes.php');
			require_once( $this->plugin_dir . 'includes/um-gallery-functions.php');
		}
		/**
		 * Check if user has access
		 *
		 * @return boolean [description]
		 */
		public function is_owner(){
			global $album, $photo;
			//logged in ID
			$my_ID = get_current_user_id();
			//get profile ID
			$profile_id = um_get_requested_user();
			//if not logged in then return false
			if( ! $my_ID ){
				return false;
			}
			//if album not empty then we are in album loop
			if( ! empty( $album ) ){
				if( $my_ID == $album->user_id ){
					return true;
				}else{
					return false;
				}
			}
			//check if we are in photos loop
			if( ! empty( $photo ) ){
				if( $my_ID == $photo->user_id ){
					return true;
				}else{
					return false;
				}
			}
			if( $profile_id == $my_ID ):
				return true;
			else:
				return false;
			endif;
		}

		/**
		 * Add JS and Styles
		 */
		public function add_scripts(){
			wp_enqueue_style(
				'um-gallery-style',
				plugins_url( '/assets/css/um-gallery.css' , __FILE__ )
			);
			wp_enqueue_script(
				'um-gallery-dropzone',
				plugins_url( '/assets/js/dropzone.js' , __FILE__ ),
				array( 'jquery' )
			);

			wp_enqueue_style(
				'um-gallery-style-carousel',
				plugins_url( '/templates/um-gallery/carousel/owl-carousel/owl.carousel.css' , __FILE__ )
			);

			wp_enqueue_style(
				'um-gallery-style-theme',
				plugins_url( '/templates/um-gallery/carousel/owl-carousel/owl.theme.css' , __FILE__ )
			);

			wp_enqueue_script(
				'um-gallery-carousel',
				plugins_url( '/templates/um-gallery/carousel/owl-carousel/owl.carousel.min.js' , __FILE__ ),
				array( 'jquery' )
			);

			wp_register_script( 'um_gallery', um_gallery()->plugin_url . 'assets/js/um-gallery.js', array( 'jquery' ) );
			// Localize the script with new data
			$localization = array(
				'site_url' => site_url(),
				'nonce' => wp_create_nonce( "um-event-nonce" ),
				'ajax_url' => admin_url('admin-ajax.php'),
				'is_owner' => $this->is_owner(),
				'save_text' => __('Save', 'um-gallery-pro'),
				'edit_text' => __('<i class="um-faicon-pencil"></i> Edit Caption', 'um-gallery-pro'),
				'cancel_text' => __('Cancel', 'um-gallery-pro'),
				'album_id' => um_galllery_get_album_id(),
				'dictDefaultMessage' => '<span class="icon"><i class="um-faicon-picture-o"></i></span>
	        <span class="str">'.__('Upload your photos', 'um-gallery-pro').'</span>',
				'upload_complete' => __('Upload Complete', 'um-gallery-pro'),
				'no_events_txt' => __('No photos found.', 'um-gallery-pro')
			);
			wp_localize_script( 'um_gallery', 'um_gallery_config', $localization );
			wp_enqueue_script('um_gallery');
		}
		/**
		 * [get_user_image_src description]
		 * @param  integer $user_id [description]
		 * @param  string  $name    [description]
		 * @param  string  $size    [description]
		 * @return [type]           [description]
		 */
		public function get_user_image_src( $user_id=0, $name='', $size='thumbnail' ){
			if(empty($user_id) || empty($name)){
				return um_gallery_default_thumb();
			}

			$image = $this->gallery_url_path .'/'. $user_id .'/'. $name;
			if( $size == 'thumbnail' ){
				$filetype = wp_check_filetype($image);
				$basename = basename($image, '.' . $filetype['ext']);
				$image_path_url = $this->gallery_url_path .'/'. $user_id .'/'. $basename .'-thumbnail.'. $filetype['ext'];
				$image_path = $this->gallery_path .'/'. $user_id .'/'. $basename .'-thumbnail.'. $filetype['ext'];

				if( ! file_exists( $image_path ) ){
					$image_path_url = um_gallery_default_thumb();
				}
				return $image_path_url;
			}

			return $image;
		}
		/**
		 * [get_user_image_path description]
		 * @param  integer $user_id [description]
		 * @param  string  $name    [description]
		 * @param  string  $size    [description]
		 * @return [type]           [description]
		 */
		public function get_user_image_path($user_id=0, $name='', $size='thumbnail'){
			if(empty($user_id) || empty($name)){
				return;
			}

			$image = $this->gallery_path . '/'. $user_id .'/'. $name;
			if( $size == 'thumbnail' ){
				$filetype = wp_check_filetype( $image );
				$basename = basename( $image, '.'.$filetype['ext'] );
				return $this->gallery_path.'/'.$user_id.'/'.$basename.'-thumbnail.'.$filetype['ext'];
			}

			return $image;
		}
		/**
		 * Get images by by user ID
		 *
		 * @param  integer $user_id
		 * @return array
		 */
		public function get_images_by_user_id( $user_id = 0 ){
			global $wpdb;
			$results = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->prefix}um_gallery WHERE user_id=%d", $user_id));
			return $results;
		}
	}

	if ( ! is_plugin_active( 'um-gallery-pro/um-gallery-pro.php' ) && ! class_exists('UM_Gallery_Pro') && ! function_exists( 'um_gallery' ) ) {
		
		function um_gallery() {
			return UM_Gallery::instance();
		}

		um_gallery();
	}



endif;

/* Licensing */
/*
if( ! class_exists( 'EDD_SL_Plugin_Updater' ) ) {
	include( um_gallery()->plugin_dir . '/classes/EDD_SL_Plugin_Updater.php' );
}
*/

/**
 * Create Gallery Upload path and create necessary tables
 */
function _um_gallery_activate() {
	$upload_dir = wp_upload_dir();
	$path = $upload_dir['basedir'] . '/um-gallery/';
	if( ! file_exists( $path ) ){
		wp_mkdir_p( $path );
	}

	global $wpdb;
	$version = get_option( 'um_gallery_lite_version', '1.0.0' );
	$charset_collate = !empty( $wpdb->charset ) ? "DEFAULT CHARACTER SET $wpdb->charset" : '';
	$table_prefix = $wpdb->prefix;

	//check version and make edits to database
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	$sql = "CREATE TABLE IF NOT EXISTS {$table_prefix}um_gallery (
			`id` bigint(20) NOT NULL AUTO_INCREMENT PRIMARY KEY,
			`album_id` bigint(20) NOT NULL,
			  `user_id` bigint(20) NOT NULL,
			  `file_name` varchar(255) NOT NULL,
			  `caption` text NOT NULL,
			  `description` text NOT NULL,
			  `status` tinyint(2) NOT NULL,
			  `upload_date` DATETIME NULL DEFAULT NULL
		) {$charset_collate};";

	dbDelta( $sql );
	echo $wpdb->last_error;
	$sql2 = "CREATE TABLE IF NOT EXISTS {$table_prefix}um_gallery_album (
			`id` bigint(20) NOT NULL AUTO_INCREMENT PRIMARY KEY,
			 `user_id` bigint(20) NOT NULL,
			  `album_name` varchar(255) NOT NULL,
			  `album_caption` text NOT NULL,
			  `album_description` text NOT NULL,
			  `album_status` tinyint(2) NOT NULL,
			  `album_privacy` tinyint(2) NOT NULL,
			  `order` int(11) NOT NULL,
			  `creation_date` DATETIME NULL DEFAULT NULL
		) {$charset_collate};";

	dbDelta( $sql2 );
	echo $wpdb->last_error;
	update_option( 'um_gallery_lite_version', '1.0.0' );
}
register_activation_hook( __FILE__, '_um_gallery_activate' );
