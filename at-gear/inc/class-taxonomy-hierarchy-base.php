<?php 
declare(strict_types=1);
namespace AT_Gear;

abstract class TaxonomyHierarchyBase {
	private $hide_empty;
	private $index;
	private $index2;

	private $args_template;
	private $args2;

	
	protected abstract function getLeaf() : array;
	
	public function __construct(string $taxonomy, string $post_type, string $taxonomy2, string $slug2, string $index, string $index2, bool $hide_empty) {
		$this->hide_empty = $hide_empty;
		
		$this->index      = $index;
		$this->index2     = $index2;
		
		$this->args_template = [
			'taxonomy'   => $taxonomy, 
			'parent'     => 0, 
			'hide_empty' => $hide_empty
		];
		
		if ( !empty($post_type) ) {
			$this->args2 = [
				'post_type' => $post_type,
				'tax_query' => [],
			];
				
			if ( !empty($slug2) ) {
				$this->args2['tax_query']['relation'] = 'AND';			
			}
			
			$this->args2['tax_query'][] = [
					'taxonomy'         => $taxonomy,
					'field'            => 'slug',
					'terms'            => '',
					'include_children' => false,
			];
				
			if ( !empty($slug2) ) {
				$this->args2['tax_query'][] = [
					'taxonomy'         => $taxonomy2,
					'field'            => 'slug',
					'terms'            => $slug2,
					'include_children' => false,
				];
			}
		}

	}
	
	
	protected function getLeaves() : array {
		$leaves = [];
		
		$loop = new \WP_Query($this->args2);
		if( $loop->have_posts() ) {
			global $post;
			
			while ( $loop->have_posts() ) {
				$loop->the_post();
				$leaves[] = $this->getLeaf();
			}
		} 
		
		return $leaves;
	}
	
	
	private function getTerms( int $parent ) : array {
		$args = $this->args_template;
		$args['parent'] = $parent;

		return \AT_Lib\getTermsSortedByMeta($args, $this->index, function ($term) {
			$url = get_term_meta($term->term_id, $this->index2, true);
			if (!empty($url)) {
				$term->url = $url;
			}
			return $term;
		});
	}
	
	
	function getHyData( int $parent = 0 ) : array {
		$children = [];
			
		$terms = $this->getTerms($parent );
		
		foreach ( $terms as $term ) {
				if ( !empty($this->args2) ) {
				$this->args2['tax_query'][0]['terms'] = $term->slug;
				$term->leaves = $this->getLeaves();
			}
			
			$term->children = $this->getHyData( $term->term_id );
			
			if ($this->hide_empty) {
				if (empty($this->args2)) { 
					if ( !empty($term->children) ) {
						$children[ $term->term_id ] = $term;
					}
				} elseif (!empty($term->leaves) || !empty($term->children) ) {
					$children[ $term->term_id ] = $term;
				}			
			} else {
				$children[ $term->term_id ] = $term;		
			}
		}
		return $children;
	}	
}
