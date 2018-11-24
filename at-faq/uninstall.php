<?php

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit();
}

function at_faq_delete_plugin() {

	$posts = get_posts(
		array(
			'numberposts' => -1,
			'post_type' => AT_FAQ_POST_TYPE,
			'post_status' => 'any',
		)
	);

	foreach ( $posts as $post ) {
		wp_delete_post( $post->ID, true );
	}
	
	$terms = get_terms( array( 
		'fields' => 'ids',
		'taxonomy' => 'faq_type',
		'hide_empty' => false,
	) );
	

	if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
		foreach ( $terms as $value ) {
			wp_delete_term( $value, 'category' );
        }
	}
	
	unregister_taxonomy_for_object_type( 'faq_type', AT_FAQ_POST_TYPE )

	unregister_post_type ( AT_FAQ_POST_TYPE );
	
	unregister_taxonomy ( 'faq_type' );
	
}

at_faq_delete_plugin();