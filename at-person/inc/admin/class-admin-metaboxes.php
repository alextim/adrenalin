<?php
declare(strict_types=1);
/**

 *
 * @package at-person\inc\admin
 */


final class AT_Person_Admin_Metaboxes {

	public function __construct() {
		add_action( 'add_meta_boxes_' . AT_PERSON_POST_TYPE, [&$this, 'register_metaboxes'], 10, 2 );
		add_action( 'do_meta_boxes',                         [&$this, 'remove_metaboxes'], 10, 2 );
		add_action( 'save_post',                             [&$this, 'save_meta_data'] );
	}

	function register_metaboxes() {
		add_meta_box( 'person_meta_box', 'Person details',  [&$this, 'render_meta_box_callback'], AT_PERSON_POST_TYPE, 'normal', 'high' );
	}

	function render_meta_box_callback( $post ) {
		wp_nonce_field( plugin_basename(__FILE__), 'person_noncename' );
		
		$helper = new AT_Person_Person( $post );
	
		$person_sort_order 	= $helper->get_sort_order();
		
		$person_position  	= $helper->get_position();
		$person_sport_level	= $helper->get_sport_level();

		$person_email	 = $helper->get_email();
		$person_phone	 = $helper->get_phone();
		$person_skype	 = $helper->get_skype();
		$person_facebook = $helper->get_facebook();
		

		?>
		<table>
			<tr>
				<td>Sort Order</td>
				<td><input type="number" size="4" name="person_sort_order" value="<?php echo $person_sort_order; ?>" /></td>
			</tr>
		
			<tr>
				<td>Position</td>
				<td><input type="text" size="80" name="person_position" value="<?php echo $person_position; ?>" /></td>
			</tr>
			<tr>
				<td>Sport level</td>
				<td><input type="text" size="80" name="person_sport_level" value="<?php echo $person_sport_level; ?>" /></td>
			</tr>
			<tr>
				<td>E-mail</td>
				<td><input type="email" size="50" name="person_email" value="<?php echo $person_email; ?>" /></td>
			</tr>
			<tr>
				<td>Phone</td>
				<td>
					<input type="tel" size="20" name="person_phone" value="<?php echo $person_phone; ?>" />
					<i>Только цифры, без '+' спереди. Номера отличные от 12 цифр не сохраняютя.</i>
				</td>
			</tr>
			<tr>
				<td>Skype</td>
				<td>
					<input type="text" size="32" name="person_skype" value="<?php echo $person_skype; ?>" maxlength="32"/>
					<i>5-32 букв латиницы, цифры. Допустимы '.' и '_'. Начало с буквы.</i>
				</td>
			</tr>
			<tr>
				<td>Facebook</td>
				<td><input type="url" size="80" name="person_facebook" value="<?php echo $person_facebook; ?>" /></td>
			</tr>

		</table>
		<?php
	}	
	
	function remove_metaboxes() {
		$object_type = AT_PERSON_POST_TYPE;
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
		
		// If this isn't a 'person' post, don't update it.
		if ( AT_PERSON_POST_TYPE !== get_post_type($post_id) ) {
			return;   
		}
	
		if ( empty( $_POST ) ) {
			return;
		}

		if ( ! isset( $_POST['person_noncename'] ) ) {
			return;
		}
		
		// проверяем nonce нашей страницы, потому что save_post может быть вызван с другого места.
		if ( ! wp_verify_nonce( $_POST['person_noncename'], plugin_basename(__FILE__) ) ) {
			return;
		}
			
		$fields = [
			['person_sort_order',  'int'],
			['person_position',    ''],
			['person_sport_level', ''],
			['person_email',	   'email'],
			['person_phone',	   'tel'],
			['person_skype',	   'skype'],
			['person_facebook',	   'url'],
		];
		// Store data in post meta table if present in post data
		
		foreach ( $fields as $item ) {
			$field = $item[0];
			if ( isset( $_POST[$field] ) ) {
				switch ( $item[1] ) {
					case 'email':
						$sanitized_value = sanitize_email($_POST[$field]);
						break;
					case 'int':
						$sanitized_value = absint($_POST[$field]);
						break;
					case 'tel':
						$sanitized_value = at_sanitize_phone($_POST[$field], new AT_Sanitize_Setting_Stub());
						break;					
					case 'skype':
						$sanitized_value = at_sanitize_skype($_POST[$field], new AT_Sanitize_Setting_Stub());
						break;					
					case 'url':
						$sanitized_value = esc_url_raw($_POST[$field]);
						break;
					default:
						$sanitized_value = sanitize_text_field($_POST[$field]);
						break;
				}
				update_post_meta( $post_id, $field,  $sanitized_value );
			}
		}
	}
}