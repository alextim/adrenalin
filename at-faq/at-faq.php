<?php
/*
Plugin Name: AT FAQ
Plugin URI: 
Description: Declares a plugin that will create a custom post type "faq"
Version: 1.0
Author: Alex Tim
Author URI: 
License: GPLv2
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function AT_FAQ() {
	return AT_FAQ::get_instance();
}
AT_FAQ();	


final class AT_FAQ {
	private static $instance;
	
	public static function get_instance() {
        if ( null == self::$instance ) {
			self::$instance = new self;
        }
        return self::$instance;
    } 	
	
	private function __construct() {
		$this->define_constants();
		$this->includes();
		$this->init_hooks();
	}
	
	private function define_constants() {
		define( 'AT_FAQ_POST_TYPE', 'faq' );
		define( 'AT_FAQ_PLUGIN_FILE', __FILE__ );
		define( 'AT_FAQ_PLUGIN_DIR', untrailingslashit( dirname( AT_FAQ_PLUGIN_FILE ) ) );	
		define( 'AT_FAQ_ABSPATH', dirname( __FILE__ ) . '/' );
	}
	
	private function includes() {
		require AT_FAQ_ABSPATH . '/inc/class-post-types.php';
		require AT_FAQ_ABSPATH . '/inc/class-taxonomies.php';

		require AT_FAQ_ABSPATH . '/inc/class-faq.php';
		
		if ( is_admin() ) {
			require AT_FAQ_ABSPATH . '/inc/admin/admin-helper.php';
			require AT_FAQ_ABSPATH . '/inc/admin/class-admin-metaboxes.php';
		}
	}
	
	private function init_hooks() {
		
		register_activation_hook( __FILE__, function () {
			$posts = new AT_FAQ_Post_Types();
			$posts::init();
			
			$taxonomy = new AT_FAQ_Taxonomies();
			$taxonomy::init();	
			
			flush_rewrite_rules();
		});
		
		add_action( 'init', [ 'AT_FAQ_Post_Types', 'init' ] );
		add_action( 'init', [ 'AT_FAQ_Taxonomies', 'init' ] );	
		if ( is_admin() ) {
			$mb = new AT_FAQ_Admin_Metaboxes();
		} 
		

		add_action('wp_enqueue_scripts', function () {
			wp_register_style( 'myCSS', plugins_url( '/css/at-faq.css', __FILE__ ) );
			wp_enqueue_style( 'myCSS' );

			wp_enqueue_script( 'jquery' );
			wp_enqueue_script( 'jquery_scrollTo', 'https://cdnjs.cloudflare.com/ajax/libs/jquery-scrollTo/2.1.2/jquery.scrollTo.min.js', false );
			wp_enqueue_script( 'jquery-ui', 'https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js', false );
			wp_enqueue_script( 'myJS', plugins_url( '/js/at-faq.js', __FILE__ ), false );
		});

/*
 * Add [at_faq limit=-1 toc=1] shortcode
 *
*/		
		add_shortcode( 'at_faq', function ( $atts, $content = null ) {

	$atts = shortcode_atts([
		'limit'    => -1,
		'toc'      => true,
		'category' => null,
	], $atts );
	
	// Define limit
	$posts_per_page = intval( $atts['limit'] ); 
	if ( 0 === $posts_per_page ) { 
		$posts_per_page = -1;
	}
	
	$show_toc = boolval( $atts['toc'] );
    $category = $atts['category'];
	
	// ob_start();

	// Create the Query
	$orderby   = 'menu_order';
	$order     = 'ASC';
	$args = [ 
		'post_type'      => AT_FAQ_POST_TYPE,
		'posts_per_page' => $posts_per_page,
		'orderby'        => $orderby, 
		'order'          => $order,
		'no_found_rows'  => 1
	];

	if ( !empty($category) ) {
		$args['tax_query'] = [
			[
				'taxonomy' => 'faq_category',
				'field'    => 'slug',
				'terms'    => $category,
			]
        ];
	}
	
	$query = new WP_Query( $args );
	
	//Get post type count
	$post_count = $query->post_count;
	$i = 1;
	
	// Displays FAQ info
	$toc = '';
	$s = '';
	
	if ( $post_count > 0) {
	
		// Loop
		$s = '<div id="answers"><ul>';
		while ($query->have_posts()) : $query->the_post(); 
			$title = esc_html(get_the_title());
			$id = get_the_ID();
			
			if ($show_toc) {
				if ( empty($toc) ) {
					$toc .= '<!-- TOC begin --><div id="questions"><ul>';
				}
				$toc .= '<li><a href="#answer' . $id  . '">' . $title . '</a></li>';
			}
		
			$s .= '<li id="answer' . $id  . '">';
			$s .= '<h4>' . $title . '</h4>';
			$s .= get_the_content();
			$s .= '</li>';

			$i++;
		endwhile;
		$s .= '</ul></div>';
		
		if ( !empty($toc) ) {
			$toc .= '</ul></div><!-- TOC end -->';
		}
		
	}
	
	// Reset query to prevent conflicts
	wp_reset_query();
	$s .= '<script type="text/javascript">
	<!--
		function rc_faq_toggle(id) {
			var e = document.getElementById(id);
			e.style.display = ((e.style.display!="none") ? "none" : "block");
		}
	//-->
	</script>';

	return $toc . $s;
	//return ob_get_clean();
}
		
		);
	}
}