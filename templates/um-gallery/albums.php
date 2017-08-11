<div class="um-gallery-album-list">
<?php
	global $albums;
	if(!empty($albums)):
		foreach($albums as $item):
			global $album;
			$album = $item;
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
