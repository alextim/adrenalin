<?php
declare(strict_types=1);
namespace AT_Faq;


final class FaqData {
	private $post;
	private $post_meta;

	function __construct( $post = null ) {
		$this->post = is_null( $post ) ? get_post( get_the_ID() ) : $post;
		$this->post_meta = get_post_meta( $this->post->ID );
		return $this->post;
	}
	
	public function get_sort_order()  : int    { return $this->get_field_int( 'faq_sort_order' ); }
	
	private function get_field_int( string $field )  : int { 
		if ( isset( $this->post_meta[$field][0] ) && '' !== $this->post_meta[$field][0] ) {
			return absint( $this->post_meta[$field][0] ); 
		}

		return 0; 
	}
}