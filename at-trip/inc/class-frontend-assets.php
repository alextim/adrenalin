<?php
/*
namespace AT_Trip;

final class FrontendAssets {
	var $assets_path;
	
	
	public function __construct() {
		$this->assets_path = plugin_dir_url( AT_TRIP_PLUGIN_FILE );
		add_action( 'wp_enqueue_scripts', [ &$this, 'styles' ] );
//		add_action( 'wp_enqueue_scripts', [ &$this, 'scripts' ] );
	}

	
	function styles() {
		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
		wp_enqueue_style( 'trip-tabs',     $this->assets_path . 'assets/css/accordion' . '' . '.css', [] );
		wp_enqueue_style( 'trip-frontend', $this->assets_path . 'assets/css/trip-frontend' . '' . '.css', [] );
	}
	
	
	function scripts() {
	}
}

new FrontendAssets();
*/