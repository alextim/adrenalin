<?php
namespace AT_Lib;

function sanitizeValue( $val, string $type ) {				
	switch ( $type ) {
		case 'email':  return sanitize_email($val);
		case 'int':    return absint($val);
		case 'time':   return strtotime($val);
		case 'url':    return esc_url($val);
		case 'reg_form_url': return esc_url($val);
		/*
			if ( strpos($sanitized_value, 'https://docs.google.com/forms') === false && 
				 strpos($sanitized_value, 'https://goo.gl/forms') === false) {
				return '';
			}
			*/
		case 'html': return wp_kses($val, \AT_Lib\getAllowedHtml());					
		default:     return sanitize_text_field($val);
	}
}


function removeMetaboxes($postType) {
	//remove_meta_box( 'authordiv',$postType,'normal' ); // Author Metabox
	remove_meta_box( 'commentstatusdiv', $postType, 'normal' ); // Comments Status Metabox
	remove_meta_box( 'commentsdiv',$postType,'normal' ); // Comments Metabox
	//remove_meta_box( 'postcustom',$postType,'normal' ); // Custom Fields Metabox
	//remove_meta_box( 'postexcerpt',$postType,'normal' ); // Excerpt Metabox
	//remove_meta_box( 'revisionsdiv', $postType, 'normal' ); // Revisions Metabox
	//remove_meta_box( 'slugdiv',$postType,'normal' ); // Slug Metabox
	//remove_meta_box( 'trackbacksdiv', $postType, 'normal' ); // Trackback Metabox
}


function save_fields_a( $post_id, array $fields ) {

	// Store data in post meta table if present in post data
	
	foreach ( $fields as $item ) {
		$field = $item[0];
		if ('bool' === $item[1]) {
			$val = 0;
			if ( isset( $_POST[$field] ) ) {
				if ( 'yes' === sanitize_text_field( wp_unslash( $_POST[$field] ) ) ) {
					$val = 1;
				}
			}
			update_post_meta( $post_id, $field, $val );				
		} elseif ( isset( $_POST[$field] ) ) {
			update_post_meta( $post_id, $field,  sanitizeValue($_POST[$field], $item[1]) );
		}
	}
}


function checkBeforeSave( $post_id, string $postType, string $nonce, string $action) : bool {
	// если это автосохранение ничего не делаем
	if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) {
		return false;
	}
	
	// проверяем права юзера
	if( ! current_user_can( 'edit_post', $post_id ) ){
		return false;	
	}
	
	// If this isn't a 'cpt' post, don't update it.
	if ( $postType !== get_post_type($post_id) ) {
		return false;
	}
	
	if ( empty( $_POST ) ) {
		return false;
	}

	if ( ! isset( $_POST[$nonce] ) ) {
		return false;
	}
	
	// проверяем nonce нашей страницы, потому что save_post может быть вызван с другого места.
	if (! wp_verify_nonce( $_POST[$nonce], $action ) ) {
		return false;
	}
	return true;
}