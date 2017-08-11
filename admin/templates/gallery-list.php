<?php
	global $wpdb;
	$query = "SELECT a.*,d.file_name, COUNT(d.id) AS total_photos FROM {$wpdb->prefix}um_gallery_album AS a LEFT JOIN {$wpdb->prefix}um_gallery AS d ON a.id=d.album_id GROUP BY a.id ORDER BY a.id DESC";
	global $albums;
	$albums = $wpdb->get_results($query);
?>
<div class="wrap">
  <h2>
    <?php _e('Albums', 'um-gallery'); ?>
    <?php /*?><a href="" class="page-title-action">
    <?php _e('Add New Album', 'um-gallery-pro'); ?>
    </a><?php */?> </h2>
    <div class="tablenav top">

				<div class="alignleft actions bulkactions">
			<label for="user-selector-top" class="screen-reader-text">Select user</label><select name="action" id="user-action-selector-top">
            <?php $users = um_gallery_get_users(); ?>
            <?php if(!empty($users)): foreach($users as $u=>$userID): um_fetch_user( $userID ); ?>
			<option value="<?php echo $userID; ?>"><?php echo um_user('display_name') ?></option>
			<?php um_reset_user(); endforeach; endif; ?>
</select>
<input type="submit" id="doaction" class="button action" value="Filter">
		</div>
		<br class="clear">
	</div>
  <div class="um-gallery-album-list">
    <?php 
		if(!empty($albums)):
			foreach($albums as $item):
				global $album;
				$album = $item;
			?>
    <div class="um-gallery-grid-item">
      <div class="um-gallery-inner">
        <div class="um-gallery-img"><a href="<?php echo um_gallery()->admin->album_view_url(); ?>"><img src="<?php echo um_gallery()->get_user_image_src($album->user_id, $album->file_name); ?>"></a></div>
        <div class="um-gallery-info">
          <div class="um-gallery-title"><a href="<?php echo um_gallery()->admin->album_view_url(); ?>"><?php echo $album->album_name; ?></a></div>
          <div class="um-gallery-meta"><?php echo um_gallery_photos_count_text(); ?></div>
          <div class="um-gallery-action"></div>
        </div>
      </div>
    </div>
    <?php
			endforeach;
		else:
			?>
    <div class="um-gallery-none">
      <?php _e('No albums found', 'um-gallery-pro'); ?>
    </div>
    <?php
		endif; 
		?>
  </div>
</div>
