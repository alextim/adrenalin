<?php
declare(strict_types=1);
/**

 *
 * @package at-faq\inc\admin
 */


final class AT_FAQ_Admin_Metaboxes {

	public function __construct() {
		add_action( 'add_meta_boxes_' . AT_FAQ_POST_TYPE, [&$this, 'register_metaboxes'], 10, 2 );
		add_action( 'do_meta_boxes',                         [&$this, 'remove_metaboxes'], 10, 2 );
		add_action( 'save_post',                             [&$this, 'save_meta_data'] );
	}

	function register_metaboxes() {
		add_meta_box( 'faq_meta_box', 'FAQ details',  [&$this, 'render_meta_box_callback'], AT_FAQ_POST_TYPE, 'normal', 'high' );
	}

	function render_meta_box_callback( $post ) {
		wp_nonce_field( plugin_basename(__FILE__), 'faq_noncename' );
		
		$helper = new AT_FAQ_FAQ( $post );
	
		$faq_sort_order 	= $helper->get_sort_order();
		?>
		<table>
			<tr>
				<td>Sort Order</td>
				<td><input type="number" size="4" name="faq_sort_order" value="<?php echo $faq_sort_order; ?>" /></td>
			</tr>
		</table>
		<?php
	}	
	
	function remove_metaboxes() {
		$object_type = AT_FAQ_POST_TYPE;
		//remove_meta_box( 'authordiv',$object_type,'normal' ); // Author Metabox
		remove_meta_box( 'commentstatusdiv', $object_type, 'normal' ); // Comments Status Metabox
		remove_meta_box( 'commentsdiv',$object_type,'normal' ); // Comments Metabox
		//remove_meta_box( 'postcustom',$object_type,'normal' ); // Custom Fields Metabox
		//remove_meta_box( 'postexcerpt',$object_type,'normal' ); // Excerpt Metabox
		//remove_meta_box( 'revisionsdiv', $object_type, 'normal' ); // Revisions Metabox
		//remove_meta_box( 'slugdiv',$object_type,'normal' ); // Slug Metabox
		//remove_meta_box( 'trackbacksdiv', $object_type, 'normal' ); // Trackback Metabox
	}

	function save_meta_data( $post_id ) {
		// если это автосохранение ничего не делаем
		if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) {
			return;
		}
		
		// проверяем права юзера
		if( ! current_user_can( 'edit_post', $post_id ) ) {
			return;	
		}
		
		// If this isn't a 'faq' post, don't update it.
		if ( AT_FAQ_POST_TYPE !== get_post_type($post_id) ) {
			return;   
		}
	
		if ( empty( $_POST ) ) {
			return;
		}

		if ( ! isset( $_POST['faq_noncename'] ) ) {
			return;
		}
		
		// проверяем nonce нашей страницы, потому что save_post может быть вызван с другого места.
		if ( ! wp_verify_nonce( $_POST['faq_noncename'], plugin_basename(__FILE__) ) ) {
			return;
		}
			
		$field = 'faq_sort_order';

		// Store data in post meta table if present in post data
		if ( isset( $_POST[$field] ) ) {
			$sanitized_value = absint($_POST[$field]);
			update_post_meta( $post_id, $field,  $sanitized_value );
		}
	}
}