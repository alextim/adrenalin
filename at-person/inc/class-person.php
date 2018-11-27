<?php
declare(strict_types=1);
/**
 * AT Person Person class
 *
 * @package AT Person
 */

final class AT_Person_Person{
	private $post;
	private $post_meta;

	function __construct( $post = null ) {
		$this->post = is_null( $post ) ? get_post( get_the_ID() ) : $post;
		$this->post_meta = get_post_meta( $this->post->ID );
		return $this->post;
	}
	
	public function get_sort_order()  : int    { return $this->get_field_int( 'person_sort_order' ); }
	
	public function get_position()    : string { return $this->get_field_text( 'person_position' ); }
	public function get_sport_level() : string { return $this->get_field_text( 'person_sport_level' ); }
	public function get_email()    	  : string { return $this->get_field_text( 'person_email' ); }
	public function get_phone()		  : string { return $this->get_field_text( 'person_phone' ); }
	public function get_skype()		  : string { return $this->get_field_text( 'person_skype' ); }
	
	public function get_facebook()	  : string { return $this->get_field_url( 'person_facebook' ); }
	
	public function get_types()       : string { return $this->get_term_text ( 'person_type' ); }
	
	private function get_field_url( string $field ) : string {	
		if ( isset( $this->post_meta[$field][0] ) && '' !== $this->post_meta[$field][0] ) {
			return esc_url( $this->post_meta[$field][0] ); 
		}
		return '';
	}
	
	private function get_field_text( string $field ) : string {	
		if ( isset( $this->post_meta[$field][0] ) && '' !== $this->post_meta[$field][0] ) {
			return esc_html( $this->post_meta[$field][0] ); 
		}
		return '';
	}
	
	private function get_field_int( string $field )  : int { 
		if ( isset( $this->post_meta[$field][0] ) && '' !== $this->post_meta[$field][0] ) {
			return absint( $this->post_meta[$field][0] ); 
		}

		return 0; 
	}

	private function get_term_text ( string $field_name ) : string {
		$terms = get_the_terms( $this->postID, $field_name );
		if ( $terms && ! is_wp_error( $terms ) ) {
		   $draught_links = array();
		 
			foreach ( $terms as $term )
				$draught_links[] = $term->name;
								 
			$on_draught = join( ", ", $draught_links );
			
			return sprintf( esc_html__( '%s', 'textdomain' ), esc_html( $on_draught ) );
		}
		return '';
	}
}