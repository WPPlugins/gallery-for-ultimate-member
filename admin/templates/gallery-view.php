<?php
	if(!isset($_GET['album_id'])){
		echo __('No album selected. Go back and try again', 'um-gallery-pro');
		return;
	}
	$album_id = (int)$_GET['album_id']; 
	global $wpdb;
	$query = "SELECT a.* FROM {$wpdb->prefix}um_gallery AS a WHERE a.album_id='{$album_id}' ORDER BY a.id DESC";
	$photos = $wpdb->get_results($query);
?>
<div class="wrap">
	<h2><?php _e('Album'); ?></h2>
    <div class="um-gallery-album-list">
    <?php 
		if(!empty($photos)):
			foreach($photos as $item):
				global $photo;
				$photo = um_gallery_setup_photo($item);
			?>
    <div class="um-gallery-grid-item">
      <div class="um-gallery-inner">
        <div class="um-gallery-img"><a href="<?php //echo um_gallery()->admin->album_view_url(); ?>"><img src="<?php echo um_gallery()->get_user_image_src($photo->user_id, $photo->file_name); ?>"></a></div>
        <div class="um-gallery-info">
          <div class="um-gallery-title"><h2><?php echo $photo->caption; ?></h2><?php /*?><a href="<?php //echo um_gallery()->admin->album_view_url(); ?>"><?php echo $photo->caption; ?></a><?php */?></div>
          <div class="um-gallery-meta"></div>
          <div class="um-gallery-action"></div>
        </div>
      </div>
    </div>
    <?php
			endforeach;
		else:
			?>
    <div class="um-gallery-none">
      <?php _e('No photos found', 'um-gallery-pro'); ?>
    </div>
    <?php
		endif; 
		?>
    </div>
</div>