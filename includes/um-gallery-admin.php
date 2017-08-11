<?php

if( ! class_exists( 'UM_Gallery_Pro_Admin' ) ):

class UM_Gallery_Pro_Admin{
	public function __construct(){
		//add_action( 'admin_menu', array($this, 'setup_menu'));
		//add_filter( 'um_core_pages', array($this, 'add_settings_page'), 12, 1);
		add_filter("redux/options/um_options/sections", array($this, 'um_add_addons_settings_tab'), 8 );
		//add_filter("um_docs_settings", array($this, "um_docs_settings_fields", 12, 1));
		//add_action( 'admin_init',  array($this, 'edd_sl_sample_plugin_updater'), 0 );
		//add_action ('redux/options/um_options/saved', array($this, 'after_settings_update'));
		add_action( 'admin_enqueue_scripts', array($this, 'admin_assets') );
	}
	/*
	*	Setup Gallery admin pages
	*
	*/
	public function setup_menu(){
		add_menu_page(
			__( 'UM Gallery', 'um-gallery-pro' ),
			__( 'UM Gallery', 'um-gallery-pro' ),
			'manage_options',
			'um-gallery',
			array($this, 'gallery_list' )
		);
		add_submenu_page(
			'um-gallery',
			__('Settings', 'um-docs'),
			__('Settings', 'um-docs'),
			'manage_options',
			'umg-settings',
			array($this, 'um_gallery_settings_page' )
		);
	}
	/*
	*
	*/
	public function load_template($tpl){
		global $ultimatemember;

		$file = um_gallery_path . 'admin/templates/' . $tpl . '.php';

		if ( file_exists( $file ) ){
			include $file;
		}
	}
	/*
	*
	*/
	public function gallery_list(){
		if( !isset($_GET['album_id']) ){
			um_gallery()->admin->load_template('gallery-list');
		}else{
			um_gallery()->admin->load_template('gallery-view');
		}
	}
	/*
	*
	*/
	public function um_gallery_settings_page(){
		um_gallery()->admin->load_template('settings');
	}
	public function um_add_addons_settings_tab( $sections ){
		global $ultimatemember;

		$fields = array();

		$fields[] = array(
			'id'       		=> 'um_gallery_allowed_roles',
		    'type'     		=> 'select',
		    'multi'    		=> true,
			'class'			=> 'select2',
			'placeholder'	=> __( 'Role select','um-gallery-pro'),
			'title'   		=> __( 'Roles Permission','um-gallery-pro'),
		    'subtitle' 		=> __('Which user roles are allowed to upload photos', 'um-gallery-pro'),
		    'desc'     		=> __('Give album creation access to certain roles. Leave blank to allow it to all', 'um-gallery-pro'),
		    'options'  		=> $ultimatemember->query->get_roles(),
		);

		$fields[] = array(
                'id'       		=> 'um_gallery_profile',
                'type'     		=> 'switch',
                'title'   		=> __( 'Show on Main Tab','um-gallery-pro'),
				'default' 		=> 1,
				'desc' 	   		=> __('If enabled, recent photo uploads will be placed on a user\'s profile main tab','um-gallery-pro'),
				'on'			=> __('Yes','um-gallery-pro'),
				'off'			=> __('No','um-gallery-pro'),
        );
		
		$fields[] = array(
                'id'       		=> 'um_gallery_profile_count',
                'type'     		=> 'text',
                'title'   		=> __( 'Photos on profile','um-gallery-pro' ),
				'desc' 	   		=> __( 'Set the number of photos on profile','um-gallery-pro'),
				'default'		=> 10,
        );
		$fields[] = array(
		    'id'       => 'opt-raw',
		    'type'     => 'raw',
		    'title'    => __('Pro Version', 'um-gallery-pro'),
		    'subtitle' => __('Looking for more?', 'um-gallery-pro'),
		    'content'     => sprintf(
				__('Take a look at <a href="%s" target="_blank">UM Gallery Pro</a><div><a href="%s" target="_blank"><img src="%s" border="0" style="width: 212px !important;" /></a></div>', 'um-gallery-pro'),
				esc_url('https://suiteplugins.com/downloads/gallery-for-ultimate-members/'),
				esc_url('https://suiteplugins.com/downloads/gallery-for-ultimate-members/'),
				um_gallery()->plugin_url . 'assets/images/um-gallery-pro-banner.jpg'
		 		)
		);

		/*
        $fields[] = array(
                'id'       		=> 'um_gallery_carousel_item_count',
                'type'     		=> 'text',
                'title'   		=> __( 'Number of items in Carousel','um-gallery-pro' ),
				'desc' 	   		=> __( 'Set the number of photos to display in Carousel','um-gallery-pro'),
				'default'		=> 10,
        );
        $fields[] = array(
                'id'       		=> 'um_gallery_seconds_count',
                'type'     		=> 'text',
                'title'   		=> __( 'Number of seconds for Autoplay','um-gallery-pro' ),
				'desc' 	   		=> __( 'Set the Slideshow/Carousel Autoplay in seconds','um-gallery-pro'),
				'default'		=> 0,
        );
		$fields[] = array(
                'id'       		=> 'um_gallery_autoplay',
                'type'     		=> 'switch',
                'title'   		=> __( 'AutoPlay Slideshow/Carousel','um-gallery-pro'),
				'default' 		=> 'off',
				'desc' 	   		=> __('If enabled, the gallery will auto play on a user\'s profile page','um-gallery-pro'),
				'on'			=> __('Yes','um-gallery-pro'),
				'off'		=> __('No','um-gallery-pro'),
        );
		$fields[] = array(
                'id'       		=> 'um_gallery_tab',
                'type'     		=> 'switch',
                'title'   		=> __( 'Show Gallery Tab','um-gallery-pro'),
				'default' 		=> 'off',
				'desc' 	   		=> __('If enabled, a gallery tab will be placed on a user\'s profile page','um-gallery-pro'),
				'on'			=> __('Yes','um-gallery-pro'),
				'off'		=> __('No','um-gallery-pro'),
        );
        $fields[] = array(
                'id'       		=> 'um_gallery_pagination',
                'type'     		=> 'switch',
                'title'   		=> __( 'Turn Pagination On/Off','um-gallery-pro'),
				'default' 		=> 'off',
				'desc' 	   		=> __('Enable this to display Pagination','um-gallery-pro'),
				'on'			=> __('Yes','um-gallery-pro'),
				'off'		=> __('No','um-gallery-pro'),
        );
        $fields[] = array(
                'id'       		=> 'um_gallery_autoheight',
                'type'     		=> 'switch',
                'title'   		=> __( 'Turn AutoHeight On/Off','um-gallery-pro'),
				'default' 		=> 'off',
				'desc' 	   		=> __('Enable this to turn AutoHeight on','um-gallery-pro'),
				'on'			=> __('Yes','um-gallery-pro'),
				'off'		=> __('No','um-gallery-pro'),
        );
		$fields[] = array(
				'id'       		=> 'um_main_gallery_type',
                'type'     		=> 'select',
				'select2'		=> array( 'allowClear' => 0, 'minimumResultsForSearch' => -1 ),
                'title'    		=> __( 'Profile Layout Type','um-gallery-pro' ),
                'desc' 	   		=> __( 'Select the type of layout for gallery on gallery tab','um-gallery-pro' ),
                'default'  		=> 'grid',
				'options' 		=> array(
									'carousel' 		=> __('Carousel','um-gallery-pro'),
									'grid' 				=> __('Grid','um-gallery-pro'),
									'slideshow' 			=> __('Slideshow','um-gallery-pro'),
				),
				'placeholder' 	=> __('Choose layout...','um-gallery-pro'),
        );
		*/
		/*$fields[] =  array(
                'id'      		=> 'um_docs_license',
                'type'     		=> 'text',
                'title'    		=> __( 'Addon License','um-docs' ),
                'default'  		=> um_get_metadefault('login_secondary_btn_url'),
				'desc' 	   		=> __('You can replace default link for this button by entering custom URL','um-docs'),
				'required'		=> array( 'login_secondary_btn', '=', 1 ),
        );
		$fields[] = array(
                'id'       		=> 'um_docs_activate',
                'type'     		=> 'switch',
                'title'    		=> __( 'Activate License','um-docs' ),
				'default' 		=> 0,
				'desc' 	   		=> __('','um-docs'),
				'on'			=> __('Yes','um-docs'),
				'off'			=> __('No','um-docs'),
        );*/
		if ( $fields ) {

			$sections[] = array(

				'icon'       => 'um-faicon-camera',
				'title'      => __( 'Gallery','um-gallery-pro'),
				'fields'	 => $fields,
				'subsection' => false,
			);

		}

		return $sections;
	}
	/*
	*
	*
	*	Returns album single view
	*/
	public function album_view_url(){
		global $album;
		return admin_url('admin.php?page=um-gallery&album_id='.$album->id);
	}
	/*
	*	Add admin assets
	*/
	public function admin_assets( $hook ){
		 /*if ( 'edit.php' != $hook ) {
			return;
		}*/
		wp_enqueue_style(
			'um-gallery-admin',
			um_gallery()->plugin_url . 'admin/assets/css/um-gallery.css'
		);
	}
}

endif;
