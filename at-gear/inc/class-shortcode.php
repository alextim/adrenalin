<?php
declare(strict_types=1);
namespace AT_Gear;

/**
 * Shortcode callbacks.
 *
 * @package at-gear\inc
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; 
}

\AT_Gear\Shortcodes::init();

final class Shortcodes {

	static function init() {
		add_shortcode( 'at_gear_list',  ['\AT_Gear\Shortcodes', 'getGearListShortcode'] ); 
	}
	
	public static function getGearListShortcode($atts = []) {
		$atts = array_change_key_case((array)$atts, CASE_LOWER);	
		$atts = shortcode_atts([ 'use' => '', ], $atts);
		
		$view = new GearView();

		return $view->getGearList($atts['use']);
	}
}
