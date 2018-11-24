<?php
declare(strict_types=1);
namespace AT_Lib;
/*
Plugin Name: AT  Lib
Plugin URI: 
Description: download code library. It has to be loaded first. Don't change magic "--" in the file name.
Version: 1.0
Author: Alex Tim
Author URI: 
License: GPLv2
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


define( 'AT_LIB_ABSPATH', dirname( __FILE__ ) . '/' );
	
/*
if ( is_admin() || is_customize_preview() ) {
	require AT_LIB_ABSPATH . '/inc/class-sanitize.php';
}
*/
$files = glob(AT_LIB_ABSPATH . '/inc/*.php');
foreach ($files as $file) {
	include  $file;
}

if ( is_admin() ) {
	$files = glob(AT_LIB_ABSPATH . '/inc/admin/*.php');
	foreach ($files as $file) {
		include  $file;
	}
}