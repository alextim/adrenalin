<?php 
declare(strict_types=1);
namespace AT_Gear;

abstract class TaxonomyHierarchyBase {
	private $hide_empty;
	private $meta_key;
	private $index;
	private $index2;

	private $args_template;
	private $args2;

	
	protected abstract function getLeaf() : array;
	
	public function __construct(string $taxonomy, string $post_type, string $taxonomy2, string $slug2, string $meta_key, string $index, string $index2, bool $hide_empty) {
		$this->hide_empty = $hide_empty;
		
		$this->meta_key   = $meta_key;
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
		
		$terms = get_terms($args);
		
		if (empty($this->meta_key)) {
			return $terms;
		}
		
		$tmp = [];
		
		foreach ( $terms as $term ) {
			$meta = get_term_meta($term->term_id, $this->meta_key, false);
			$sort_order = $meta[0][$this->index];
			$url        = $meta[0][$this->index2];
			$tmp[] = [$sort_order, $url, $term];
		}

		usort( $tmp, function($a, $b) {
			if ($a[0]==$b[0]) return 0;
			return $a[0]>$b[0] ? 1 : -1;
		});
		
		$ordered_terms = [];
		foreach ( $tmp as $item ) {
			if ( !empty($item[1]) ) {
				$item[2]->url = $item[1];
			}
			$ordered_terms[] = $item[2];;
		}
		

		return $ordered_terms;
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
