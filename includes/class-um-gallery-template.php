<?php
if( ! class_exists( 'UM_Gallery_Pro_Template' ) ) :
    class UM_Gallery_Pro_Template{
        /**
    	 * [$gallery description]
    	 * @var [type]
    	 */
    	public $gallery;
    	/**
    	 * [$manage_gallery description]
    	 * @var string
    	 */
    	public $manage_gallery = 'manage_gallery';
    	/**
    	 * [$add_photos description]
    	 * @var [type]
    	 */
    	public $add_photos;
    	/**
    	 * @var UM_Gallery_Pro The single instance of the class
    	 */
        /**
         * [__construct description]
         */
        public function __construct(){
            $this->gallery = $this->get_gallery_slug();
    		$this->add_photos = $this->get_add_photos_slug();
            $this->album_allowed = um_gallery_allow_albums();
            $this->quick_upload = um_gallery_allow_quick_upload();
            $this->hooks();
        }
        /**
    	 * [get_gallery_slug description]
    	 * @return [type] [description]
    	 */
    	public function get_gallery_slug(){
    		return apply_filters('sp_gallery_gallery_slug', 'gallery');
    	}
    	/**
    	 * [get_add_photos_slug description]
    	 */
    	public function get_add_photos_slug(){
    		return apply_filters('sp_gallery_add_photos_slug', 'add_photos');
    	}
        public function hooks() {
            add_filter( 'um_profile_tabs', array($this, 'setup_gallery_tabs'), 12, 1);
            add_action( 'um_profile_content_'.$this->gallery, array($this, 'gallery_content_page') );
            add_action( 'um_profile_content_main', array($this, 'gallery_profile_content_page'), 20);
        }

        /**
    	 * Set profile tab
    	 *
    	 * @param  array
    	 * @return array
    	 */
    	public function setup_gallery_tabs( $tabs = array() ){
            if( um_gallery_can_moderate() ):
    		$tabs[$this->gallery] = array(
    				'name'              => __('Gallery', 'um-gallery-pro'),
    				'icon'              => 'um-faicon-camera',
                    'custom'            => true,
    				'subnav_default'    => 0
    			);
            endif;
    		return $tabs;
    	}

        /**
    	 * [gallery_profile_content_page description]
    	 * @return [type] [description]
    	 */
    	public function gallery_profile_content_page(){
    		if( um_get_option('um_gallery_profile') && (isset($_GET['profiletab']) && 'main' == $_GET['profiletab'] ) || empty($_GET['profiletab']) ){
    			global $ultimatemember, $images;
    			$amount = um_get_option('um_gallery_profile_count');
    			if( !$amount ){
    				$amount = 10;
    			}
    			$images = um_gallery_recent_photos( array('user_id'=>um_get_requested_user(), 'amount'=> $amount) );
    			um_gallery()->template->load_template('um-gallery/content-grid');
    		}
    	}

        /**
    	 * [gallery_content_page description]
    	 * @return [type] [description]
    	 */
    	public function gallery_content_page() {
    		$user_id = um_profile_id();
    		$this->get_profile_photos_view();
    	}

        public function get_profile_photos_view() {
            global $ultimatemember, $images;
            $user_id = um_profile_id();
            $images = get_images_by_user_id( $user_id );
            ?>
            <h3>
    			<?php if( um_gallery()->is_owner() ) {
                    $album_id = um_gallery_get_default_album( $user_id );
                ?>
    			<a href="#" class="um-gallery-form um-gallery-btn" data-id="<?php echo (int)$album_id; ?>"><i class="um-faicon-plus"></i> <?php _e('Add Photo', 'um-gallery-pro'); ?></a>
    			<?php } ?>
    		</h3>
            <?php
            um_gallery()->template->load_template('um-gallery/content-grid');
        }
        /**
         * [get_profile_single_album_view description]
         * @return [type] [description]
         */
        public function get_profile_single_album_view(){
            global $images;
            $album_id = (int)$_GET['album_id'];
            $images = um_gallery_photos_by_album($album_id);
            $album = um_gallery_album_by_id( $album_id );
            ?>
            <div class="um-gallery-album-back">
            <a href="<?php echo um_gallery_profile_url(); ?>" class="um-gallery-btn"><i class="um-faicon-chevron-left"></i> <?php _e('Back to Albums', 'um-gallery-pro'); ?>
            </a>
            </div>
            <div class="um-gallery-album-head">
                <h3 class="um-gallery-album-title"><?php echo $album->album_name; ?></h3>
                <?php if( ! empty( $album->album_description ) ): ?>
                <div class="um-gallery-album-description"><?php echo esc_html( $album->album_description ); ?></div>
                <?php endif; ?>
            </div>
            <?php
            $layout = um_get_option('um_main_gallery_type');
            switch($layout){
                case 'carousel':
                um_gallery()->template->load_template('um-gallery/content-carousel');
                break;
                case 'grid':
                um_gallery()->template->load_template('um-gallery/content-grid');
                break;
                case 'slideshow':
                um_gallery()->template->load_template('um-gallery/content-slideshow');
                break;
                default:
                um_gallery()->template->load_template('um-gallery/content-grid');
                break;
            }
        }
        public function get_profile_albums_view() {
            ?>
            <h3>
    			<?php _e('Albums', 'um-gallery-pro'); ?>
    			<?php if( um_gallery()->is_owner() ) { ?>
    			<a href="#" class="um-gallery-form um-gallery-btn"><i class="um-faicon-folder"></i> <?php _e('Add Album', 'um-gallery-pro'); ?></a>
    			<?php } ?>
    		</h3>
    		<?php
    		um_gallery()->template->load_template('um-gallery/albums');
        }

        /**
    	 * [load_template description]
    	 * @param  [type] $tpl [description]
    	 * @return [type]      [description]
    	 */
    	public function load_template($tpl){
    		global $ultimatemember;

    		$file = um_gallery()->plugin_dir . 'templates/' . $tpl . '.php';
    		$theme_file = get_stylesheet_directory() . '/ultimate-member/templates/' . $tpl . '.php';

    		if ( file_exists( $theme_file ) ){
    			$file = $theme_file;
    		}

    		if ( file_exists( $file ) ){
    			include $file;
    		}
    	}
    }
endif;
