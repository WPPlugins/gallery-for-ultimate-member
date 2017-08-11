<?php global $album; $album_id = @(int)$album->id; ?>
<div class="um-gallery-form-wrapper" id="um-gallery-album">
  <div class="um-modal-header">
    <?php _e('Manage Album'); ?>
  </div>
  <div class="um-modal-body">
    <form>
      <div class="um-gallery-form-header">
          <input type="hidden" name="album_name" id="album_name" placeholder="<?php _e('Enter Album Name'); ?>" value="<?php echo @$album->album_name; ?>" />
          <input type="hidden" name="album_description" id="album_description" placeholder="<?php _e('Enter Album Name'); ?>" value="<?php echo @$album->album_name; ?>" />
      </div>
      <div >
        <div class="um-clear"></div>
      </div>
      <div id="dropzone" class="dropzone um-gallery-upload"> </div>
      <input type="hidden" name="album_id" value="<?php echo $album_id; ?>" />
    </form>
    <div class="um-modal-footer">
      <div class="um-modal-left">
        <div class="um-gallery-form-field">
          <input type="hidden" name="album_privacy" id="album_privacy" value="public" />
        </div>
      </div>
      <div class="um-modal-right"> <a href="#" class="um-modal-btn image" id="um-gallery-save" data-id="<?php echo $album_id; ?>" data-type="album">
        <?php _e('Save', 'um-gallery-pro'); ?>
        </a> <a href="#" class="um-modal-btn um-gallery-close alt" id="um-gallery-cancel">  <?php _e('Cancel', 'um-gallery-pro'); ?></a> </div>
      <div class="um-clear"></div>
    </div>
  </div>
</div>
