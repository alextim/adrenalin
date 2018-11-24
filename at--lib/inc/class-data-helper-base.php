<?php 
declare(strict_types=1);
namespace AT_Lib;


abstract class DataHelperBase {
	public $post;
	protected $post_meta;

	public function __construct( $post ) {
		$this->post = is_null( $post ) ? get_post( get_the_ID() ) : $post;
		$this->post_meta = get_post_meta( $this->post->ID );
	}

	
	protected function getRaw( string $field ) : string {	
		if ( isset( $this->post_meta[$field][0] ) && '' !== $this->post_meta[$field][0] ) {
			return $this->post_meta[$field][0]; 
		}
		return '';
	}

	
	protected function getText_( string $field ) : string  {	
		if ( isset( $this->post_meta[$field][0] ) && '' !== $this->post_meta[$field][0] ) {
			return esc_html( $this->post_meta[$field][0] ); 
		}
		return '';
	}
	
	
	protected function getUrl( string $field ) : string  {	
		if ( isset( $this->post_meta[$field][0] ) && '' !== $this->post_meta[$field][0] ) {
			return esc_url( $this->post_meta[$field][0] ); 
		}
		return '';
	}
	
	
	protected function getDate_( string $field ) : string  {	
		if ( isset( $this->post_meta[$field][0] ) && '' !== $this->post_meta[$field][0] ) {
			return date( $this->date_format_mask, (int)$this->post_meta[$field][0] ); 
		}
		return '';
	}
	
	
	protected function getInt( string $field )  : int { 
		if ( isset( $this->post_meta[$field][0] ) && '' !== $this->post_meta[$field][0] ) {
			return absint( $this->post_meta[$field][0] ); 
		}
		return 0; 
	}
	
	
	protected function getTaxonomy( string $taxonomy, string $before, string $sep, string $after ) : string {
		$lists = get_the_term_list( $this->post->ID, $taxonomy, $before, $sep, $after );
		if ( false !== $lists && !is_wp_error($lists) ) {
			return (string)$lists;
		}
		return '';	
	}
}	
