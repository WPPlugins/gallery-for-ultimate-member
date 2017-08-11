<?php
class UM_Gallery_Shortcodes{
	/**
	 * [__construct description]
	 */
	public function __construct(){
		add_shortcode('um_gallery_albums', array($this, 'um_gallery_albums') );
	}
	/**
	 * [um_gallery_albums description]
	 * @param  array  $atts [description]
	 * @return [type]       [description]
	 */
	public function um_gallery_albums( $atts = array() ){
		ob_start();
		global $ultimatemember,$albums;
		extract(shortcode_atts(array(
        	'user_id' => '',
        	'id' => '',
            'amount' => '10',
    		), $atts)
		);
		$sql_where = array();
		$sql_where[] = ' 1=1';
		if( !empty($user_id) ){
			$user_id_lists = explode(',',$user_id);
			if (count($user_id_lists) > 1){
				$sql_where[] = ' a.user_id IN ('.implode(',', $user_id_lists).')';
			}else{
				$sql_where[] = ' a.user_id = "'.$user_id_lists[0].'" ';
			}
		}
		if( ! empty( $id ) ){
			$id_lists = explode(',', $id);
			if (count($id_lists) > 1){
				$sql_where[] = ' a.user_id IN ('.implode(',', $id_lists).')';
			}else{
				$sql_where[] = ' a.user_id = "'.$id_lists[0].'" ';
			}
		}
		global $wpdb;
		$query = "SELECT a.*, d.file_name, COUNT(d.id) AS total_photos FROM {$wpdb->prefix}um_gallery_album AS a LEFT JOIN {$wpdb->prefix}um_gallery AS d ON a.id=d.album_id WHERE ".implode(' AND ', $sql_where)." GROUP BY a.id   ORDER BY a.id DESC LIMIT 0, {$amount}";
		$albums = $wpdb->get_results($query);
		um_gallery()->template->load_template('um-gallery/albums');
		$output_string = ob_get_contents();
		ob_end_clean();
		return $output_string;
	}
}
