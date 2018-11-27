<?php 
declare(strict_types=1);
namespace AT_Gear;


class GearHierarchy extends TaxonomyHierarchyBase {
	public function __construct( string $slug2, bool $hide_empty ) {
		parent::__construct( 'gear_type', AT_GEAR_POST_TYPE, 'recommended_use', $slug2, 'gear_type_order', 'gear_type_url', $hide_empty );
	}
	
	
	protected function GetLeaf() : array {
		global $post;
		
		$data = new GearData($post);
		$url = $data->getProductUrl();

		return [ 'title' => $post->post_title, 'url' => $url, 'excerpt' => $post->post_excerpt ];	
	}
}


final class GearView {
	private function render( array $data ) {
		if ( empty($data) ) {
			return;
		}?>

		<ul>
		<?php foreach( $data as $term ) : ?>
			<li>
			<h5>
				<?php if (isset($term->url) && !empty($term->url)) : ?>
					<a target="_blanc" rel="noopener noreferrer nofollow" href="<?php echo $term->url; ?>"><?php echo $term->name; ?></a>
				<?php else :
					echo $term->name; 		
				endif; ?>
			</h5>
			<?php 
				$this->printProducts( $term->leaves );
				$this->render( $term->children );
			?>
			</li>
		<?php endforeach; ?>
		</ul>
		<?php
	}

	
	function getGearList(string $slug2) : string {
		$helper = new GearHierarchy( $slug2, true );
		$data = $helper->getHyData();
		
		ob_start();
		$this->render( $data );
		$html = ob_get_contents();
		ob_end_clean();
		
		return $html;
	}
	
	
	public function printGearList( string $slug2 ) {
		$helper = new GearHierarchy( $slug2, true );
		
		$this->render( $helper->getHyData() );
	}
	
	
	private function printProducts( $items ) {
		if ( empty($items) ) {
			return;
		}?>
		<ul>

		<?php foreach( $items as $item ) : ?>
			<li>
			<?php if ( empty($item['url']) ) {
					echo $item['title']; 
				} else { ?>
					<a target="_blanc" rel="noopener noreferrer nofollow" href="<?php echo $item['url']; ?>"><?php echo $item['title']; ?></a>
				<?php }?>&nbsp;<cite><?php echo $item['excerpt']; ?></cite>
			</li>
		<?php endforeach; ?>
		
		</ul>
<?php	
	}
}