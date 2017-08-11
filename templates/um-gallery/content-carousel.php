<?php
global $ultimatemember, $images;
$user_id = um_profile_id();
//$images = $this->get_images_by_user_id($user_id);
$data = array();
$users = array();
?>
<div id="owl-example" class="owl-carousel um-gallery-carousel">
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
            <div class="um-gallery-inner item um-gallery-item" id="um-photo-<?php echo $item->id; ?>">
                <a href="<?php echo um_gallery()->get_user_image_src($user_id, $image->file_name, 'none'); ?>"  data-lightbox="example-set" data-title="" id="um-gallery-item-<?php echo $image->id; ?>" class="um-gallery-open-photo" data-id="<?php echo $image->id; ?>"><img src="<?php echo um_gallery()->get_user_image_src($user_id, $image->file_name); ?>" />
                </a>
                <?php if(um_gallery()->is_owner()): ?>
                <div class="um-gallery-mask">
                    <a href="#" class="um-gallery-delete-item" data-id="<?php echo $image->id; ?>"><i class="um-faicon-trash"></i></a>
                    <?php /*?><a href="#" class="um-manual-trigger"  data-parent=".um-edit-form" data-child=".um-btn-auto-width" data-id="<?php echo $image->id; ?>"><i class="um-faicon-pencil"></i></a><?php */?>
                </div>
                <?php endif; ?>
            </div>
<?php
    }
    endif;
    ?>
</div>


<?php
	//Carousel Options from admin
	$autoplay = um_get_option('um_gallery_seconds_count');
    if($autoplay) {
        $autoplay = $autoplay * 1000;
    } else {
        $autoplay = 'false';
    }
    if (um_get_option('um_gallery_autoplay') == 'off'){
        $autoplay = 'false';
    }
    $carousel_item_count = um_get_option('um_gallery_carousel_item_count');
    if(!$carousel_item_count) {
        $carousel_item_count = 10;
    }
    $seconds_count = um_get_option('um_gallery_seconds_count');
    $pagination = um_get_option('um_gallery_pagination');
    if ($pagination == 1) {
        $pagination = 'true';
    } else {
        $pagination = 'false';
    }
    $autoheight = um_get_option('um_gallery_autoheight');
    if ($autoheight == 1) {
        $autoheight = 'true';
    } else {
        $autoheight = 'false';
    }
?>
<script type="text/javascript">
jQuery(document).ready(function($) {
    $('#owl-example').owlCarousel({
		items : <?php echo $carousel_item_count; ?>,
		autoPlay: <?php echo $autoplay;?>,
		pagination :  <?php echo $pagination; ?>,
        autoHeight : <?php echo $autoheight; ?>
	});
});
</script>
<script type="text/javascript" id="um-gallery-data">
	var um_gallery_images = <?php echo json_encode($data); ?>;
	var um_gallery_users = <?php echo json_encode($users); ?>;
</script>
