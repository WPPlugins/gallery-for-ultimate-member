<?php
/**
 * Get the recent photos uploaded by user
 *
 * @param  array  $args [description]
 * @return array
 */
function um_gallery_recent_photos( $args = array() ){
	/**
	 * Define the array of defaults
	 */
	$defaults = array(
		'user_id' => '',
		'id' => "",
		'offset' => "0",
		'amount' => "10",
	);

	/**
	 * Parse incoming $args into an array and merge it with $defaults
	 */
	$args = wp_parse_args( $args, $defaults );
	extract($args);
	$sql_where = array();
	$sql_where[] = ' 1=1';
	if( !empty($args['user_id']) ){
		$user_id_lists = explode(',', $args['user_id']);
		if (count($user_id_lists) > 1){
			$sql_where[] = ' a.user_id IN ('.implode(',', $user_id_lists).')';
		}else{
			$sql_where[] = ' a.user_id = "'.$user_id_lists[0].'" ';
		}
	}
	if( !empty($args['id']) ){
		$id_lists = explode(',', $args['id']);
		if (count($id_lists) > 1){
			$sql_where[] = ' a.user_id IN ('.implode(',', $id_lists).')';
		}else{
			$sql_where[] = ' a.user_id = "'.$id_lists[0].'" ';
		}
	}

	global $wpdb;
	$query = "SELECT a.* FROM {$wpdb->prefix}um_gallery AS a WHERE ".implode(' AND ', $sql_where). " LIMIT {$offset}, {$amount}";
	$items = $wpdb->get_results($query);
	return $items;
}

/**
 * Get user albums and 1 photo for a user
 *
 * @param  integer $user_id [description]
 * @return array
 */
function um_gallery_by_userid( $user_id = 0 ){
	global $wpdb;
	$query = "SELECT a.*,d.file_name, COUNT(d.id) AS total_photos FROM {$wpdb->prefix}um_gallery_album AS a LEFT JOIN {$wpdb->prefix}um_gallery AS d ON a.id=d.album_id WHERE a.user_id='{$user_id}' GROUP BY a.id ORDER BY a.id DESC";
	$albums = $wpdb->get_results($query);
	return $albums;
}

/**
 * Get photos by album_id
 *
 * @param  integer $album_id
 *
 * @return array
 */
function um_gallery_photos_by_album( $album_id = 0){
	global $wpdb;
	$query = "SELECT a.* FROM {$wpdb->prefix}um_gallery AS a WHERE a.album_id='{$album_id}' ORDER BY a.id DESC";
	$photos = $wpdb->get_results($query);
	return $photos;
}

/**
 * Get default thumbnail
 *
 * @return [type] [description]
 */
function um_gallery_default_thumb(){
	return apply_filters('um_gallery_default_image', um_gallery()->plugin_url . 'assets/images/default.jpg');
}
/**
*	Setup photo data
*
*	Setup array for photo data to e used in loop
*
* @return array
**/
function um_gallery_setup_photo( $photo = array() ){
	//global $photo;
	$photo->caption = (!empty($photo->caption) ? $photo->caption : um_gallery_safe_name($photo->file_name));
	return $photo;
}

/**
 * Get number of photos in album
 *
 * @return integer
 */
function um_gallery_photos_count(){
	global $album;
	return (int)$album->total_photos;
}

/**
 * Get number of photos text
 *
 * @return string
 */
function um_gallery_photos_count_text(){
	$count = um_gallery_photos_count();
	$text = sprintf( _n( '%s photo', '%s photos', $count, 'um-gallery-pro' ), number_format_i18n( $count ) );
	return $text;
}
/**
 * Make file name ready database
 * @param  string $file_name [description]
 * @return string
 */
function um_gallery_safe_name( $file_name = '' ){
	$filetype = wp_check_filetype($file_name);
	$file_name = basename($file_name, ".".$filetype['ext']);
	return $file_name;
}

/**
 * Gallery URL
 *
 * @return string
 */
function um_gallery_profile_url(){
	$url = um_user_profile_url();
	$url = remove_query_arg('profiletab', $url);
	$url = remove_query_arg('subnav', $url);
	$url = add_query_arg( 'profiletab', um_gallery()->template->gallery, $url );
	return $url;
}

/**
 * Gets an Album URL
 *
 * @return string
 */
function um_gallery_album_url(){
	 global $ultimatemember, $album;
	$url = um_user_profile_url();
	$url = remove_query_arg('profiletab', $url);
	$url = remove_query_arg('subnav', $url);
	$url = add_query_arg( 'profiletab', um_gallery()->template->gallery, $url );
	if($album->id){
    	$url = add_query_arg( 'album_id',  $album->id, $url );
	}
    return $url;
}

/**
 * Get ALbum ID from address bar
 *
 * @return integer
 */
function um_galllery_get_album_id(){
	$album_id = 0;
	if( isset($_GET) && !empty($_GET['album_id']) ){
		$album_id = (int)$_GET['album_id'];
	}
	return $album_id;
}
/**
 * Get album data by ID
 *
 * @param  integer $album_id Album ID to query
 * @return array
 */
function um_gallery_album_by_id( $album_id = 0 ){
	global $wpdb;
	$query = "SELECT a.*,d.file_name, COUNT(d.id) AS total_photos FROM {$wpdb->prefix}um_gallery_album AS a LEFT JOIN {$wpdb->prefix}um_gallery AS d ON a.id=d.album_id WHERE a.id='{$album_id}'";
	$album = $wpdb->get_row($query);
	return $album;
}

/**
 * Perform photo delete from database and removes file
 *
 * @param  integer $photo_id ID to delete
 * @return void
 */
function um_gallery_delete_photo( $photo_id = 0 ){
	global $wpdb;
	$file = $wpdb->get_row($wpdb->prepare("SELECT file_name, user_id FROM {$wpdb->prefix}um_gallery WHERE id ='%d'", $photo_id));
	$wpdb->delete( $wpdb->prefix.'um_gallery', array( 'id' => $photo_id ) );
	$file_url = um_gallery()->get_user_image_path($file->user_id, $file->file_name);
	unlink ( $file_url );
	$file_url = um_gallery()->get_user_image_path($file->user_id, $file->file_name, 'none');
	unlink ( $file_url );
	do_action('um_gallery_photo', $photo_id );
}
/**
*	Delete album
*
*	Remove album from database and all photos under album
*
*	@return void
*/
function um_gallery_delete_album( $album_id = 0 ){
	//make sure logged in user can delete album
	global $wpdb;
	//get album data
	$album = um_gallery_album_by_id( $album_id );
	//find all photos for this album
	$images = um_gallery_photos_by_album( $album_id );
	//loop through each image for deleting
	if( !empty($images) ){
		foreach ($images as $item) {
			//delete photo
			um_gallery_delete_photo( $item->id );
		}
	}
	//delete album :(
	$wpdb->delete( $wpdb->prefix.'um_gallery_album', array( 'id' => $album_id ) );
	//action for developers
	do_action('um_gallery_album_deleted', $album_id);
}

/**
* Get all users with album
*
* @return array User IDs
**/
function um_gallery_get_users(){
	global $wpdb;
	$query = "SELECT a.user_id FROM {$wpdb->prefix}um_gallery_album AS a LEFT JOIN {$wpdb->users} AS d ON a.user_id=d.ID GROUP BY a.user_id ORDER BY d.display_name DESC";
	$users = $wpdb->get_col($query);
	return $users;
}
/**
 * Get images uploaded by user id
 *
 * @param  integer $user_id
 * @return array
 */
function get_images_by_user_id( $user_id = 0 ){
	global $wpdb;
	$results = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->prefix}um_gallery WHERE user_id=%d", $user_id));
	return $results;
}
/**
 * Get link to user gallery on profile
 *
 * @param  string $id
 *
 * @return string
 */
function um_get_gallery_link( $id='' ){
    global $ultimatemember;
	$slug = 'gallery';
    $url = um_user_profile_url();
    $url = remove_query_arg('profiletab', $url);
    $url = remove_query_arg('subnav', $url);
    $url = add_query_arg( 'profiletab', $slug, $url );
    //$url = add_query_arg( 'view',  'edit_doc', $url );
	if($id){
    	$url = add_query_arg( 'view',  $id, $url );
	}
    return $url;
}

/**
 * Enable the skipping of creating an album
 *
 * @return boolean
 *
 * @since 1.0.6
 */
function um_gallery_allow_albums(){
	return false;
}

/**
 * Get the first album created by a user
 *
 * @param  integer $user_id [description]
 * @return integer
 */
function um_gallery_get_default_album( $user_id = 0 ){
	global $wpdb;
	$query = "SELECT a.id FROM {$wpdb->prefix}um_gallery_album AS a WHERE a.user_id = '{$user_id}' ORDER BY a.id ASC LIMIT 0, 1 ";
	$album_id = $wpdb->get_var($query);
	return $album_id;
}


function um_gallery_can_moderate() {
	//get role setting
	$allowed_roles = um_get_option('um_gallery_allowed_roles');
	//if empty then it's
	if( empty($allowed_roles) ) {
		return true;
	}
	//get profile ID
	$profile_id = um_get_requested_user();
	//get user Role
	$role = get_user_meta($profile_id, 'role', true);
	//check if profle is in array
	if( in_array($role, $allowed_roles) ){
		return true;
	}
	//return false
	return false;
}

/**
* Check if the EXIF orientation flag matches one of the values we're looking for
* http://www.impulseadventure.com/photo/exif-orientation.html
*
* If it does, this means we need to rotate the image based on the orientation flag and then remove the flag.
* This will ensure the image has the correct orientation, regardless of where it's displayed.
*
* Whilst most browsers and applications will read this flag to perform the rotation on displaying just the image, it's
* not possible to do this in some situations e.g. displaying an image within a lightbox, or when the image is
* within HTML markup.
*
* Orientation flags we're looking for:
* 8: We need to rotate the image 90 degrees counter-clockwise
* 3: We need to rotate the image 180 degrees
* 6: We need to rotate the image 90 degrees clockwise (270 degrees counter-clockwise)
*/
function um_gallery_fix_image_orientation( $file ) {

	// Check we have a file
	if ( ! file_exists( $file['file'] ) ) {
		return $file;
	}

	// Attempt to read EXIF data from the image
	$exif_data = wp_read_image_metadata( $file['file'] );
	if ( ! $exif_data ) {
		return $file;
	}

	// Check if an orientation flag exists
	if ( ! isset( $exif_data['orientation'] ) ) {
		return $file;
	}

	// Check if the orientation flag matches one we're looking for
	$required_orientations = array( 8, 3, 6 );
	if ( ! in_array( $exif_data['orientation'], $required_orientations ) ) {
		return $file;
	}

	// If here, the orientation flag matches one we're looking for
	// Load the WordPress Image Editor class
	$image = wp_get_image_editor( $file['file'] );
	if ( is_wp_error( $image ) ) {
		// Something went wrong - abort
		return $file;
	}

	// Store the source image EXIF and IPTC data in a variable, which we'll write
	// back to the image once its orientation has changed
	// This is required because when we save an image, it'll lose its metadata.
	$source_size = getimagesize( $file['file'], $image_info );

	// Depending on the orientation flag, rotate the image
	switch ( $exif_data['orientation'] ) {

		/**
		* Rotate 90 degrees counter-clockwise
		*/
		case 8:
			$image->rotate( 90 );
			break;

		/**
		* Rotate 180 degrees
		*/
		case 3:
			$image->rotate( 180 );
			break;

		/**
		* Rotate 270 degrees counter-clockwise ($image->rotate always works counter-clockwise)
		*/
		case 6:
			$image->rotate( 270 );
			break;

	}

	// Save the image, overwriting the existing image
	// This will discard the EXIF and IPTC data
	$image->save( $file['file'] );

	// Drop the EXIF orientation flag, otherwise applications will try to rotate the image
	// before display it, and we don't need that to happen as we've corrected the orientation

	// Write the EXIF and IPTC metadata to the revised image
	$result = um_gallery_transfer_iptc_exif_to_image( $image_info, $file['file'], $exif_data['orientation'] );
	if ( ! $result ) {
		return $file;
	}

	// Finally, return the data that's expected
	return $file;

}

/**
* Transfers IPTC and EXIF data from a source image which contains either/both,
* and saves it into a destination image's headers that might not have this IPTC
* or EXIF data
*
* Useful for when you edit an image through PHP and need to preserve IPTC and EXIF
* data
*
* @since 1.0.0
*
* @source http://php.net/iptcembed - ebashkoff at gmail dot com
*
* @param string $image_info 			EXIF and IPTC image information from the source image, using getimagesize()
* @param string $destination_image 		Path and File of Destination Image, which needs IPTC and EXIF data
* @param int 	$original_orientation 	The image's original orientation, before we changed it.
*										Used when we replace this orientation in the EXIF data
*/
function um_gallery_transfer_iptc_exif_to_image( $image_info, $destination_image, $original_orientation ) {

    // Check destination exists
    if ( ! file_exists( $destination_image ) ) {
    	return false;
    }

    // Get EXIF data from the image info, and create the IPTC segment
    $exif_data = ( ( is_array( $image_info ) && key_exists( 'APP1', $image_info ) ) ? $image_info['APP1'] : null );
    if ( $exif_data ) {
    	// Find the image's original orientation flag, and change it to 1
    	// This prevents applications and browsers re-rotating the image, when we've already performed that function
        // @TODO I'm not sure this is the best way of changing the EXIF orientation flag, and could potentially affect
        // other EXIF data
    	$exif_data = str_replace( chr( dechex( $original_orientation ) ) , chr( 0x1 ), $exif_data );

        $exif_length = strlen( $exif_data ) + 2;
        if ( $exif_length > 0xFFFF ) {
        	return false;
        }

        // Construct EXIF segment
        $exif_data = chr(0xFF) . chr(0xE1) . chr( ( $exif_length >> 8 ) & 0xFF) . chr( $exif_length & 0xFF ) . $exif_data;
    }

    // Get IPTC data from the source image, and create the IPTC segment
    $iptc_data = ( ( is_array( $image_info ) && key_exists( 'APP13', $image_info ) ) ? $image_info['APP13'] : null );
    if ( $iptc_data ) {
        $iptc_length = strlen( $iptc_data ) + 2;
        if ( $iptc_length > 0xFFFF ) {
        	return false;
        }

        // Construct IPTC segment
        $iptc_data = chr(0xFF) . chr(0xED) . chr( ( $iptc_length >> 8) & 0xFF) . chr( $iptc_length & 0xFF ) . $iptc_data;
    }

    // Get the contents of the destination image
    $destination_image_contents = file_get_contents( $destination_image );
    if ( ! $destination_image_contents ) {
    	return false;
    }
    if ( strlen( $destination_image_contents ) == 0 ) {
    	return false;
    }

    // Build the EXIF and IPTC data headers
    $destination_image_contents = substr( $destination_image_contents, 2 );
    $portion_to_add = chr(0xFF) . chr(0xD8); // Variable accumulates new & original IPTC application segments
    $exif_added = ! $exif_data;
    $iptc_added = ! $iptc_data;

    while ( ( substr( $destination_image_contents, 0, 2 ) & 0xFFF0 ) === 0xFFE0 ) {
        $segment_length = ( substr( $destination_image_contents, 2, 2 ) & 0xFFFF );
        $iptc_segment_number = ( substr( $destination_image_contents, 1, 1 ) & 0x0F );   // Last 4 bits of second byte is IPTC segment #
        if ( $segment_length <= 2 ) {
        	return false;
        }

        $thisexistingsegment = substr( $destination_image_contents, 0, $segment_length + 2 );
        if ( ( 1 <= $iptc_segment_number) && ( ! $exif_added ) ) {
            $portion_to_add .= $exif_data;
            $exif_added = true;
            if ( 1 === $iptc_segment_number ) {
                $thisexistingsegment = '';
            }
        }

        if ( ( 13 <= $iptc_segment_number ) && ( ! $iptc_added ) ) {
            $portion_to_add .= $iptc_data;
            $iptc_added = true;
            if ( 13 === $iptc_segment_number ) {
                $thisexistingsegment = '';
            }
        }

        $portion_to_add .= $thisexistingsegment;
        $destination_image_contents = substr( $destination_image_contents, $segment_length + 2 );
    }

    // Write the EXIF and IPTC data to the new file
    if ( ! $exif_added ) {
        $portion_to_add .= $exif_data;
    }
    if ( ! $iptc_added ) {
        $portion_to_add .= $iptc_data;
    }

    $output_file = fopen( $destination_image, 'w' );
    if ( $output_file ) {
    	return fwrite( $output_file, $portion_to_add . $destination_image_contents );
    }

    return false;

}

/**
 * Enable uploading photos from main profile
 *
 * @return boolean
 *
 * @since 1.0.6
 */
function um_gallery_allow_quick_upload(){
	return false;
}
/**
 * [um_gallery_form_modal description]
 * @return [type] [description]
 */
function um_gallery_form_modal(){
	?>
    <div id="um-gallery-modal" class="um-gallery-popup mfp-hide"></div>
    <?php
}
add_action('wp_footer', 'um_gallery_form_modal');
