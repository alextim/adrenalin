<?php
//declare(strict_types=1);
namespace AT_Gear;

final class GearAdminMetaboxes {

	private const NONCE_NAME = 'gear_nonce';
	private const ACTION_NAME = 'gear_save_action';

	public function __construct() {
		
		add_action( 'add_meta_boxes_' .  AT_GEAR_POST_TYPE, [ &$this, 'registerMetaboxes'], 10, 2 );
		add_action( 'do_meta_boxes', '\AT_Lib\removeMetaboxes', 10, 2 );
		add_action( 'save_post',     [ &$this, 'saveMetaData' ] );
		
	}
	
	public function saveMetaData( $post_id ) {
		if (!\AT_Lib\checkBeforeSave( $post_id, AT_GEAR_POST_TYPE, self::NONCE_NAME, self::ACTION_NAME )) {
			return;
		}
		\AT_Lib\save_fields_a( $post_id, [
			['gear_product_url', 'url'],
			['gear_sort_order',  'int'],
		] );
	}	

	public function registerMetaboxes() {
		
		add_meta_box( 'gear_meta_box1', 'Extra', [ &$this, 'render_extra_callback' ], AT_GEAR_POST_TYPE, 'normal', 'high' );
	}
	

	function render_extra_callback( $post ) {
		wp_nonce_field( self::ACTION_NAME, self::NONCE_NAME );

		$helper = new GearData( $post );
		$product_url = $helper->getProductUrl();
		$sort_order  = $helper->getSortOrder();
		?>
		
		<table>
			<tr>
				<td><label for="gear_product_url">Ссылка</label></td>
				<td><input size="80" maxlength="128" type="url" name="gear_product_url" value="<?php echo $product_url; ?>"></td>
			</tr>
			<tr>
				<td><label for="gear_sort_order">Сортировка</label></td>
				<td><input type="number" name="gear_sort_order" value="<?php echo $sort_order; ?>" min="0"/></td>
			</tr>

		</table>
		<?php
	}
}