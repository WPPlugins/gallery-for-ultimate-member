<?php
if( ! class_exists( 'UM_Gallery_Pro_AJAX' ) ) :
	/**
	 * AJAX Class
	 */
	class UM_Gallery_Pro_AJAX{
		/**
		 * [__construct description]
		 */
		public function __construct(){
            $this->hooks();
        }
		/**
		 * [hooks description]
		 * @return [type] [description]
		 */
		public function hooks(){
			add_action(	'wp_ajax_um_gallery_album_update', array($this, 'um_gallery_album_update')	);
			add_action(	'wp_ajax_um_gallery_delete_album', array($this, 'um_gallery_ajax_delete_album') );
			add_action(	'wp_ajax_um_gallery_get_album_form', array($this, 'um_gallery_get_album_form') );
			add_action(	'wp_ajax_um_gallery_photo_update', array($this, 'um_gallery_photo_update') );
			add_action(	'wp_ajax_um_gallery_get_album_item', array($this, 'um_gallery_get_album_item') );
			add_action( 'wp_ajax_um_gallery_photo_upload', array($this, 'um_gallery_photo_upload') );

			add_action(	'wp_ajax_um_photo_info', array($this, 'um_gallery_photo_info') );
			add_action(	'wp_ajax_nopriv_um_photo_info', array($this, 'um_gallery_photo_info') );

			add_action('wp_ajax_sp_gallery_um_delete', array($this, 'delete_item'));
		}

		public function um_gallery_get_album_item() {
			$album_id = (int)$_GET['album_id'];
			global $album;
			$album = um_gallery_album_by_id( $album_id );
			?>
			<div class="um-gallery-grid-item" id="um-album-<?php echo $album->id; ?>">
			  <div class="um-gallery-inner">
				<div class="um-gallery-img"><a href="<?php  echo um_gallery_album_url(); ?>"><img src="<?php echo um_gallery()->get_user_image_src($album->user_id, $album->file_name); ?>"></a>
				<?php if(um_gallery()->is_owner()): ?>
				  <div class="um-gallery-action">
					<a href="#" class="um-gallery-form" data-id="<?php echo $item->id; ?>"><i class="um-faicon-pencil"></i></a>
					<a href="#" class="um-delete-album" data-id="<?php echo $item->id; ?>"><i class="um-faicon-trash"></i></a>
				  </div>
				  <?php endif; ?>
				</div>
				<div class="um-gallery-info">
				  <div class="um-gallery-title"><a href="<?php  echo um_gallery_album_url(); ?>"><?php echo $album->album_name; ?></a></div>
				  <div class="um-gallery-meta"><span class="um-gallery-count"><?php echo um_gallery_photos_count_text(); ?></span></div>

				</div>
			  </div>
			</div>
			<?php
			exit;
		}
		/**
		 * Save Album with Photos
		 *
		 * @return [type] [description]
		 */
		function um_gallery_album_update(){
			$results = array();
			$album_id = 0;
			global $wpdb;
			$user_id = get_current_user_id();
			$album_name = (!empty($_POST['album_name']) ? wp_kses($_POST['album_name']) : __('Untitled Album', 'um-gallery-pro'));
			$album_description = (!empty($_POST['album_description']) ? wp_kses($_POST['album_description']) : '');
			if( empty($_POST['id']) ){
				$wpdb->insert(
					$wpdb->prefix.'um_gallery_album',
					array(
						'album_name' => $album_name,
						'album_description' => $album_description,
						'creation_date'	=> date('Y-m-d H:i:s'),
						'user_id' => $user_id,
						'album_status' => 1
					),
					array(
						'%s',
						'%s',
						'%s',
						'%d',
						'%d'
					)
				);
				$album_id = $wpdb->insert_id;
				$results['new'] = true;
			}else{
				$id = (int)$_POST['id'];
				$wpdb->update(
					$wpdb->prefix.'um_gallery_album',
					array(
						'album_name' => $album_name,
						'album_description' => $album_description,
					),
					array( 'id' => $id ),
					array(
						'%s',
						'%s',
					),
					array( '%d' )
				);
				$album_id = $id;
				$results['new'] = false;
			}
			$results['id'] = $album_id;
			$results['user_id'] = $user_id;
			do_action('um_gallery_album_updated', $results );
			wp_send_json($results);
		}

		/**
		 * [um_gallery_photo_upload description]
		 * @return [type] [description]
		 */
		function um_gallery_photo_upload(){
			$results = array();
			if ( ! function_exists( 'wp_handle_upload' ) ) {
				require_once( ABSPATH . 'wp-admin/includes/file.php' );
			}
			if (!empty($_FILES)) :
				$user_id = get_current_user_id();
				if ( is_user_logged_in() ) {
					$path = um_gallery()->gallery_path .'/'. $user_id.'/';
					if(!file_exists($path)){
						wp_mkdir_p($path);
					}

				$file = um_gallery_fix_image_orientation( $_FILES['file'] );
				$album_id = (int)$_POST['album_id'];
				$tmp_file = $file['tmp_name'];
				$name = sanitize_text_field($file['name']);
				$filename = wp_unique_filename( $path, $name, false );
				$targetFile =  $path. $filename;  //5

				if( move_uploaded_file( $tmp_file,$targetFile ) ){
					$image = wp_get_image_editor( $targetFile );
					$filetype = wp_check_filetype($targetFile);
					$basename = basename($targetFile, '.'.$filetype['ext']);
					if ( ! is_wp_error( $image ) ) {
						$image->resize( 150, 150, true );
						$image->save( $path . $basename.'-thumbnail.'.$filetype['ext'] );
					}
					global $wpdb;
					$wpdb->insert(
						$wpdb->prefix.'um_gallery',
						array(
							'album_id' => $album_id,
							'file_name' => $filename,
							'upload_date'	=> date('Y-m-d H:i:s'),
							'user_id' => $user_id,
							'status' => 1
						),
						array(
							'%d',
							'%s',
							'%s',
							'%d',
							'%d'
						)
					);
					$last_id = $wpdb->insert_id;
				}

				}
			endif;
			$images_var = array();
			$images = um_gallery_photos_by_album($album_id);
			if( !empty($images) ){
				foreach ($images as $item) {
					global $photo;
					$image = um_gallery_setup_photo($item);
					$images_var[$image->id] = array(
						'id' => $image->id,
						'user_id' => $image->user_id,
						'caption' => $image->caption,
						'description' => esc_html($image->description)
					);
				}
			}
			$image_src = um_gallery()->get_user_image_src($user_id, $filename, 'none');
			$thumb = um_gallery()->get_user_image_src($user_id, $filename);
			$results = array(
				'id'			=> $last_id,
				'user_id'		=> $user_id,
				'album_id'		=> $album_id,
				'image_src'		=> $image_src,
				'thumb'			=> $thumb,
				'gallery_images'=> $images_var
			);
			do_action('um_gallery_photo_updated', $results );
			wp_send_json($results);
		}

		/**
		 * [um_gallery_photo_update description]
		 * @return [type] [description]
		 */
		function um_gallery_photo_update(){
			$results = array();
			global $wpdb;
			$id = (int)$_POST['id'];

			$wpdb->update(
				$wpdb->prefix.'um_gallery',
				array(
					'caption' => (!empty($_POST['caption']) ? wp_kses($_POST['caption']) : wp_kses($_POST['default_caption'])),
					'description' => (!empty($_POST['description']) ? wp_kses($_POST['description']) : ''),
				),
				array( 'id' => $id ),
				array(
					'%s',
					'%s',
				),
				array( '%d' )
			);
			$album_id = $wpdb->get_var( $wpdb->prepare("SELECT album_id FROM {$wpdb->prefix}um_gallery WHERE id='%d'", $id) );
			$images = um_gallery_photos_by_album($album_id);
			if( !empty($images) ){
				foreach ($images as $item) {
					global $photo;
					$image = um_gallery_setup_photo($item);
					$results[$image->id] = array(
						'id' => $image->id,
						'user_id' => $image->user_id,
						'caption' => $image->caption,
						'description' => esc_html($image->description)
					);
				}
			}
			do_action('um_gallery_photo_updated', $results );
			wp_send_json($results);
		}

		/**
		 * [um_gallery_get_album_form description]
		 * @return [type] [description]
		 */
		function um_gallery_get_album_form(){
			global $album_id;
			if( isset($_GET['album_id']) ){
				$album_id = (int)$_GET['album_id'];
			}
			global $album;
			//get album data
			$album = um_gallery_album_by_id( $album_id );
			um_gallery()->template->load_template('um-gallery/manage/album-form');
			exit;
		}
		/**
		 * [um_gallery_ajax_delete_album description]
		 * @return [type] [description]
		 */
		function um_gallery_ajax_delete_album(){
			$results = array();
			$album_id = (int)$_POST['id'];
			um_gallery_delete_album( $album_id );
			wp_send_json($results);
		}
		/**
		 * [um_gallery_photo_info description]
		 * @return [type] [description]
		 */
		function um_gallery_photo_info(){

		}
		/**
		 * Delete gallery item
		 *
		 * @return JSON
		 */
		public function delete_item() {
			$results = array();
			$id = (int)$_POST['id'];
			global $wpdb;
			um_gallery_delete_photo( $id );
			$album_id = (int)$_POST['album_id'];
			$images = um_gallery_photos_by_album($album_id);
			if( !empty($images) ){
				foreach ($images as $item) {
					global $photo;
					$image = um_gallery_setup_photo($item);
					$results[$image->id] = array(
						'user_id' => $image->user_id,
						'caption' => $image->caption,
						'description' => esc_html($image->description)
					);
				}
			}
			wp_send_json($results);
		}

	}

endif;
