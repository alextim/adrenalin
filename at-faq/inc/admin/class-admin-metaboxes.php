<?php
declare(strict_types=1);
namespace AT_Faq;


final class AdminMetaboxes {
	private const NONCE_NAME = 'faq_noncename';
	private const ACTION_NAME = 'faq_save_data_process';	

	
	public function __construct() {
		add_action( 'add_meta_boxes_' . AT_FAQ_POST_TYPE, [&$this, 'register_metaboxes'], 10, 2 );
		add_action( 'do_meta_boxes',                      '\AT_Lib\removeMetaboxes', 10, 2 );
		add_action( 'save_post',                          [&$this, 'saveMetaData'] );
	}

	
	public function saveMetaData( $post_id ) {
		if (!\AT_Lib\checkBeforeSave( $post_id, AT_FAQ_POST_TYPE, self::NONCE_NAME, self::ACTION_NAME )) {
			return;
		}
		$this->doSave( $post_id );
	}

	
	function register_metaboxes() {
		add_meta_box( 'faq_meta_box', 'FAQ details',  [&$this, 'render_meta_box_callback'], AT_FAQ_POST_TYPE, 'normal', 'high' );
	}

	
	function render_meta_box_callback( $post ) {
		wp_nonce_field( self::ACTION_NAME, self::NONCE_NAME );
		
		$helper = new FaqData( $post );
	
		$faq_sort_order = $helper->get_sort_order();
		?>
		<table>
			<tr>
				<td>Sort Order</td>
				<td><input type="number" size="4" name="faq_sort_order" value="<?php echo $faq_sort_order; ?>" /></td>
			</tr>
		</table>
		<?php
	}	
	
	
	protected function doSave( $post_id ) {
		\AT_Lib\save_fields_a( $post_id, [
			['faq_sort_order',   'int'],
		]);
	}
}