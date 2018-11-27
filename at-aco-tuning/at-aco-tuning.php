<?php
/*

Plugin Name: AT ACO Tuning

Author: Alex Tim

Version: 1.0

*/
//TO-Do
// https://perfmatters.io/features/

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}	
/*
// YOAST
//https://gist.github.com/paulcollett/4c81c4f6eb85334ba076
//if (defined('WPSEO_VERSION')) {
  add_action('wp_head',function() { ob_start(function($o) {
  return preg_replace('/^\n?\<\!\-\-.*?[Y]oast.*?\-\-\>\n?$/mi','',$o);
  }); },~PHP_INT_MAX);
//}
*/

function remove_json_api () {

    // Remove the REST API lines from the HTML Header
    remove_action( 'wp_head', 'rest_output_link_wp_head', 10 );
    remove_action( 'wp_head', 'wp_oembed_add_discovery_links', 10 );

    // Remove the REST API endpoint.
    remove_action( 'rest_api_init', 'wp_oembed_register_route' );

    // Turn off oEmbed auto discovery.
    add_filter( 'embed_oembed_discover', '__return_false' );

    // Don't filter oEmbed results.
    remove_filter( 'oembed_dataparse', 'wp_filter_oembed_result', 10 );

    // Remove oEmbed discovery links.
    remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );

    // Remove oEmbed-specific JavaScript from the front-end and back-end.
    remove_action( 'wp_head', 'wp_oembed_add_host_js' );

   // Remove all embeds rewrite rules.
   add_filter( 'rewrite_rules_array', 'disable_embeds_rewrites' );
}
add_action( 'after_setup_theme', 'remove_json_api' );

function disable_json_api () {

  // Filters for WP-API version 1.x
  add_filter('json_enabled', '__return_false');
  add_filter('json_jsonp_enabled', '__return_false');

  // Filters for WP-API version 2.x
  add_filter('rest_enabled', '__return_false');
  add_filter('rest_jsonp_enabled', '__return_false');

}
add_action( 'after_setup_theme', 'disable_json_api' );

//REST
function disable_json_api () {

  // Filters for WP-API version 1.x
  add_filter('json_enabled', '__return_false');
  add_filter('json_jsonp_enabled', '__return_false');

  // Filters for WP-API version 2.x
  add_filter('rest_enabled', '__return_false');
  add_filter('rest_jsonp_enabled', '__return_false');

}
add_action( 'after_setup_theme', 'disable_json_api' );

//REST API Links
add_action( 'after_setup_theme', function() {
	remove_action( 'wp_head',      'rest_output_link_wp_head'              );

	remove_action( 'template_redirect', 'rest_output_link_header', 11, 0 );
}, 10, 0 );



// EMBED
add_action( 'init', function() {

    // Remove the REST API endpoint.
    remove_action('rest_api_init', 'wp_oembed_register_route');

    // Turn off oEmbed auto discovery.
    // Don't filter oEmbed results.
    remove_filter('oembed_dataparse', 'wp_filter_oembed_result', 10);

    // Remove oEmbed discovery links.
    remove_action('wp_head', 'wp_oembed_add_discovery_links');

    // Remove oEmbed-specific JavaScript from the front-end and back-end.
    remove_action('wp_head', 'wp_oembed_add_host_js');
}, PHP_INT_MAX - 1 );
/*
add_action( 'wp_footer', function (){
 wp_deregister_script( 'wp-embed' );
} );
*/


// remove wp version param from any enqueued scripts
function vc_remove_wp_ver_css_js( $src ) {
    if ( strpos( $src, 'ver=' . get_bloginfo( 'version' ) ) )
        $src = remove_query_arg( 'ver', $src );
    return $src;
}
add_filter( 'style_loader_src', 'vc_remove_wp_ver_css_js', 9999 );
add_filter( 'script_loader_src', 'vc_remove_wp_ver_css_js', 9999 );

/**
 *  https://contactform7.com/restricting-access-to-the-administration-panel/
 *
 *  define( 'WPCF7_ADMIN_READ_CAPABILITY', 'manage_options' );
 *	define( 'WPCF7_ADMIN_READ_WRITE_CAPABILITY', 'manage_options' );
 *  
 *   or
 *
 *  remove_menu_page('wpcf7');
 *
 */
 


/**
 * Flamingo
 *
 * https://wordpress.org/support/topic/howto-provide-access-to-flamingo-for-non-admin-users/#post-8268712
 *
 */ 
 





/**
 * Manually Disable Screen Options Button in WordPress
 *
 * http://www.wpbeginner.com/wp-tutorials/how-to-disable-the-screen-options-button-in-wordpress/
 *
 * или плуги Adminimize
 */
add_filter('screen_options_show_screen', function () { 
	if( !current_user_can('manage_options') ) {
		return false;
	}
	return true; 
});





remove_action ( 'welcome_panel', 'wp_welcome_panel' );


// remove junk from head

// https://habrahabr.ru/post/252531/	
// 5. Скрываем версию Wordpress'a	
remove_action('wp_head', 'wp_generator');

remove_action('wp_head', 'wp_shortlink_wp_head');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'rsd_link');
//remove_action('wp_head', 'feed_links', 2);
//remove_action('wp_head', 'feed_links_extra');
remove_action('wp_head', 'adjacent_posts_rel_link_wp_head');

remove_action('wp_head', 'index_rel_link');
remove_action('wp_head', 'start_post_rel_link', 10, 0);
remove_action('wp_head', 'parent_post_rel_link', 10, 0);
remove_action('wp_head', 'adjacent_posts_rel_link', 10, 0);

//   primary_remove_recent_comments_style
add_action('widgets_init', function () {
    global $wp_widget_factory;
    remove_action('wp_head', array($wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style'));
});

remove_action('template_redirect', 'wp_shortlink_header', 11);

// https://deluxeblogtips.com/disable-xml-rpc-wordpress/
// primary_remove_x_pingback
add_filter('wp_headers', function ($headers) {
    unset($headers['X-Pingback']);
    return $headers;
});

header_remove('x-powered-by');



/** 
 * XML-RPC
 * https://wordpress.stackexchange.com/questions/78780/xmlrpc-enabled-filter-not-called
 */
//add_filter('xmlrpc_enabled', '__return_false');

//
//


/*
remove_action( 'wp_head', 'rsd_link' );
if ( defined( 'XMLRPC_REQUEST' ) && XMLRPC_REQUEST ) )
    exit;
*/

 

// https://habrahabr.ru/company/xakep/blog/259843/
// https://habrahabr.ru/post/62814/

	// https://habrahabr.ru/post/98083/
	
	// 1. Защищаем Wordpress от XSS-инъекций
	// .htaccess, расположенный в корне сайта.
	/*
	Options +FollowSymLinks
	RewriteEngine On
	RewriteCond %{QUERY_STRING} (\<|%3C).*script.*(\>|%3E) [NC,OR]
	RewriteCond %{QUERY_STRING} GLOBALS(=|\[|\%[0-9A-Z]{0,2}) [OR]
	RewriteCond %{QUERY_STRING} _REQUEST(=|\[|\%[0-9A-Z]{0,2})
	RewriteRule ^(.*)$ index.php [F,L]
	*/
	
	
	// 2. Убираем показ лишней информации
	// functions.php, лежащий в папке с активной темой нашего блога (wp-content/themes/название-вашей-темы/
	// add_filter('login_errors',create_function('$a', "return null;")
	
	// 3. Принудительное использование SSL
	// wp-config.php, обитающий в корне сайта
	/*
	define('FORCE_SSL_ADMIN', true);
	*/
	
	
	// 4. Используем .htaccess для защиты файла wp-config
	/*
	<files wp-config.php>
		order allow,deny
		deny from all
	</files>
	*/
	
	
	// 5. Скрываем версию Wordpress'a
	
	// 5.1 functions.php
remove_action('wp_head', 'wp_generator');
	
	// 5.2 Удалить руками readme.html
	
	// 6. Баним спамеров и ботов
	// .htaccess
	/*
	<Limit GET POST PUT>
		order allow,deny
		allow from all
		deny from aaa.bbb.ccc.ddd
	</LIMIT>
	*/
	
	// 7. Пишем плагин для защиты от зловредных url-запросов
	/*
	global $user_ID; 


	if($user_ID) {
	  if(!current_user_can('level_10')) {
		if (strlen($_SERVER['REQUEST_URI']) > 255 ||
		  strpos($_SERVER['REQUEST_URI'], "eval(") ||
		  strpos($_SERVER['REQUEST_URI'], "CONCAT") ||
		  strpos($_SERVER['REQUEST_URI'], "UNION+SELECT") ||
		  strpos($_SERVER['REQUEST_URI'], "base64")) {
			@header("HTTP/1.1 414 Request-URI Too Long");
		@header("Status: 414 Request-URI Too Long");
		@header("Connection: Close");
		@exit;
		}
	  }
	}
	*/
	// 8. Личеры!
	/*
	RewriteEngine On
#Замените ?mysite\.ru/ на адрес вашего сайта
RewriteCond %{HTTP_REFERER} !^http://(.+\.)?mysite\.ru/ [NC]
RewriteCond %{HTTP_REFERER} !^$
#Замените /images/nohotlink.jpg на название вашей картинки с лозунгом «личер идёт на…»
RewriteRule .*\.(jpe?g|gif|bmp|png)$ /images/nohotlink.jpg [L]
	*/
	
	// 9. Убить админа. (Нет дефолтному юзернейму «admin»)!
	/*
	UPDATE wp_users SET user_login = 'Ваш новый логин' WHERE user_login = 'Admin';
	UPDATE wp_posts SET post_author = 'Ваш новый логин' WHERE post_author = 'admin';
	*/
	
	// 10. Защита директорий на сервере от просмотра
	/*
	Options All -Indexes
	*/

	
// https://kinsta.com/knowledgebase/wordpress-disable-rss-feed/#disable-rss-feed-code
function itsme_disable_feed() {
 wp_die( __( 'No feed available, please visit the <a href="'. esc_url( home_url( '/' ) ) .'">homepage</a>!' ) );
}

add_action('do_feed', 'itsme_disable_feed', 1);
add_action('do_feed_rdf', 'itsme_disable_feed', 1);
add_action('do_feed_rss', 'itsme_disable_feed', 1);
add_action('do_feed_rss2', 'itsme_disable_feed', 1);
add_action('do_feed_atom', 'itsme_disable_feed', 1);
add_action('do_feed_rss2_comments', 'itsme_disable_feed', 1);
add_action('do_feed_atom_comments', 'itsme_disable_feed', 1);

remove_action( 'wp_head', 'feed_links_extra', 3 );
remove_action( 'wp_head', 'feed_links', 2 );