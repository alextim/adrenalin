<?php
declare(strict_types=1);
namespace AT_Lib;


function getIP() : string {
	if (isset($_SERVER)) {
		if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) 
			return $_SERVER["HTTP_X_FORWARDED_FOR"];
		if (isset($_SERVER["HTTP_CLIENT_IP"]))
			return $_SERVER["HTTP_CLIENT_IP"];
		return $_SERVER["REMOTE_ADDR"];
	} else {
		if ( getenv( 'HTTP_X_FORWARDED_FOR' ) )
			return getenv( 'HTTP_X_FORWARDED_FOR' );
		if ( getenv( 'HTTP_CLIENT_IP' ) )
			return getenv( 'HTTP_CLIENT_IP' );
		return getenv( 'REMOTE_ADDR' );
	}
}


function writeLog ( $log )  {
	if ( true === WP_DEBUG ) {
		if ( is_array( $log ) || is_object( $log ) ) {
			error_log( print_r( $log, true ) );
		} else {
			error_log( $log );
		}
	}
}


function getAllowedHtml() : array {
	return [
		'a' => array(
			'class' => [],
			'href'  => [],
			'rel'   => [],
			'title' => [],
			'target'=> [],
		),
		'abbr' => array(
			'title' => [],
		),
		'b' => [],
		'blockquote' => array(
			'cite'  => [],
		),
		'cite' => array(
			'title' => [],
		),
		'code' => [],
		'del' => array(
			'datetime' => [],
			'title' => [],
		),
		'dd' => [],
		'div' => array(
			'class' => [],
			'title' => [],
			'style' => [],
		),
		'dl' => [],
		'dt' => [],
		'em' => [],
		'h1' => [],
		'h2' => [],
		'h3' => [],
		'h4' => [],
		'h5' => [],
		'h6' => [],
		'i' => [],
		'img' => array(
			'alt'    => [],
			'class'  => [],
			'height' => [],
			'src'    => [],
			'width'  => [],
		),
		'li' => array(
			'class' => [],
		),
		'ol' => array(
			'class' => [],
		),
		'p' => array(
			'class' => [],
		),
		'q' => array(
			'cite' => [],
			'title' => [],
		),
		'span' => array(
			'class' => [],
			'title' => [],
			'style' => [],
		),
		'strike' => [],
		'strong' => [],
		'table' => [],
		'tbody' => [],
		'thead' => [],
		'th' => [],
		'tr' => [],
		'td' => [],
		'ul' => array(
			'class' => [],
		),
	];
}

function getDateFormatMask() : string {
	$date_format_mask = get_option( 'date_format' );
	if (! $date_format_mask ) {
		$date_format_mask = 'd.m.Y';
	}
	return $date_format_mask;
}

	