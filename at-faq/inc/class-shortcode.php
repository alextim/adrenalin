<?php
declare(strict_types=1);
namespace AT_Faq;


if ( ! defined( 'ABSPATH' ) ) {
	exit; 
}

Shortcodes::init();

/*
 * Add [at_faq limit=-1 toc=1 category=''] shortcode
 *
*/

final class Shortcodes {

	static function init() {
		add_shortcode( 'at_faq',  ['\AT_Faq\Shortcodes', 'geFaqShortcode'] ); 
	}
	
	public static function geFaqShortcode($atts = []) {
		$atts = array_change_key_case((array)$atts, CASE_LOWER);	
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
		
		$query = new \WP_Query( $args );
		
		//Get post type count
		$post_count = $query->post_count;
		if ( $post_count <= 0 ) {
			return '';
		}

		$i = 1;
		$toc = '';
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

	
		// Reset query to prevent conflicts
		wp_reset_query();
		$s .= 
'<script type="text/javascript">
	<!--
		function rc_faq_toggle(id) {
			var e = document.getElementById(id);
			e.style.display = ((e.style.display!="none") ? "none" : "block");
		}
	//-->
</script>';

		return $toc . $s;
	}
}