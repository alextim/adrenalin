<?php
namespace AT_Trip;

new AdminAssets();


final class AdminAssets {
	var $assets_path;
	private $suffix;
	
	public function __construct() {
		$this->assets_path = plugin_dir_url( AT_TRIP_PLUGIN_FILE );
		$this->suffix = ''; //'.min';
// better use get_current_screen(); or the global $current_screen
//if (isset($_GET['page']) && $_GET['page'] == 'my_plugin_page') {		
		add_action( 'admin_enqueue_scripts', [&$this, 'styles'] );
		add_action( 'admin_enqueue_scripts', [&$this, 'scripts'] );
//}
		
	}
	
	function styles( $hook ) {
		global $post_type;

		if ( ( 'post.php' == $hook || 'post-new.php' == $hook ) && ( AT_TRIP_POST_TYPE == $post_type ) ) {
			wp_enqueue_style('jquery-ui-css', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');

			wp_enqueue_style( 'trip-tabs', $this->assets_path . 'assets/css/tabs' . '' . '.css', [] );
			
			wp_enqueue_style('thickbox');
		}
	}

	function scripts( $hook ) {
		global $post_type;

		if ( ( 'post.php' == $hook || 'post-new.php' == $hook ) && ( AT_TRIP_POST_TYPE == $post_type ) ) {
/*
			wp_enqueue_script('jquery');
			wp_enqueue_script('jquery-ui-core');
			wp_enqueue_script('jquery-ui-datepicker');
*/
			wp_enqueue_script('media-upload');
			wp_enqueue_script('thickbox');	
			
			wp_enqueue_script(
				'at-trip-back-end',
				$this->assets_path . 'assets/js/at-trip-back-end.js',
				[ 'jquery', 'jquery-ui-core', 'jquery-ui-datepicker' ],
				null,
				true
			);
		}
	}
}