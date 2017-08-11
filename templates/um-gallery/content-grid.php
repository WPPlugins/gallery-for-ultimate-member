<?php
global $ultimatemember, $images;
$user_id = um_profile_id();
$data = array();
$users = array();
$profile_id = um_get_requested_user();
if( $profile_id ){
	um_fetch_user($profile_id);
	$users[$profile_id]  = array(
		'id' => $profile_id,
		'name' => um_user('display_name'),
		'link' => um_user_profile_url(),
		'avatar' => um_user('profile_photo', 50)
	);
	um_reset_user();
}
?>
<div class="um-gallery-item-wrapper um-gallery-grid">
<?php
if(!empty($images)):
foreach ($images as $item) {
		global $photo;
		$image = um_gallery_setup_photo($item);
		$photo = $image;
		$data[$image->id] = array(
		    'id' => $image->id,
			'user_id' => $image->user_id,
			'caption' => $image->caption,
			'description' => esc_html($image->description)
		);
		if( empty($users[$image->user_id]) ){
			um_fetch_user($image->user_id);
			$users[$image->user_id]  = array(
				'id' => $image->user_id,
				'name' => um_user('display_name'),
				'link' => um_user_profile_url(),
				'avatar' => um_user('profile_photo', 50)
			);
			um_reset_user();
		}
	?>
    <div class="um-gallery-item um-gallery-col-1-4" id="um-photo-<?php echo $item->id; ?>">
    	<div class="um-gallery-inner">
            <a href="<?php echo um_gallery()->get_user_image_src($user_id, $image->file_name, 'none'); ?>" class="um-gallery-open-photo" id="um-gallery-item-<?php echo $image->id; ?>" data-title=""  data-id="<?php echo $image->id; ?>"><img src="<?php echo um_gallery()->get_user_image_src($user_id, $image->file_name); ?>" />
            </a>
            <?php if(um_gallery()->is_owner()): ?>
            <div class="um-gallery-mask">
                <a href="#" class="um-gallery-delete-item" data-id="<?php echo $image->id; ?>"><i class="um-faicon-trash"></i></a>
                <?php /*?><a href="#" class="um-manual-trigger"  data-parent=".um-edit-form" data-child=".um-btn-auto-width" data-id="<?php echo $image->id; ?>"><i class="um-faicon-pencil"></i></a><?php */?>
            </div>
            <?php endif; ?>
        </div>
    </div>
    <?php
}
else:
?>
<?php
endif;
?>
</div>
<script type="text/javascript" id="um-gallery-data">
	var um_gallery_images = <?php echo json_encode($data); ?>;
	var um_gallery_users = <?php echo json_encode($users); ?>;
</script>
