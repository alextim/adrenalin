<?php
declare(strict_types=1);
namespace AT_Lib;


final class SanitizeSettingStub {
	public $default;
	
	public function __construct($default = '') { 
		$this->default = $default; 
	}
}


function sanitizeCheckbox( $checked  ) {
	return ( ( isset( $checked ) && true === $checked ) ? true : false );
}
	
	
function sanitizeDropdownPages( $page_id, $setting ) {
	$page_id = absint( $page_id );
	return ( 'publish' === get_post_status( $page_id ) ? $page_id : $setting->default );
}	


function sanitizeSelect( $input, $setting ) {
	// Ensure input is a slug.
	$input = sanitize_key( $input );

	// Get list of choices from the control associated with the setting.
	$choices = $setting->manager->get_control( $setting->id )->choices;

	// If the input is a valid key, return it; otherwise, return the default.
	return array_key_exists( $input, $choices ) ? $input : $setting->default;
}


function sanitizePhone( $value, $setting ) {
	$san_value = preg_replace('/\D/', '', $value);
	return (strlen($san_value) === 12) ? $san_value : $setting->default;		
}


function sanitizeSkype( $value, $setting ) {
	$san_value = preg_replace('/[^A-Za-z0-9\._]/', '', $value);
	return preg_match('/^[a-z][a-z0-9\.,\-_]{5,31}$/i', $san_value) ? $san_value : $setting->default;		
}


function sanitizeTelegram( $value, $setting ) {
	$san_value = preg_replace('/[^A-Za-z0-9_]/', '', $value);

	$n = strlen($san_value);
	return ($n >= 5 && $n <= 32) ? $san_value : $setting->default;		
}


function sanitizePostalIndex( $value, $setting ) {
	$postal_index = absint($value);
	
	return (5 === strlen($postal_index)) ? $postal_index : $setting->default;
}

class Sanitize {	
	public static function validate_member_count( $validity, $value ) {
		$member_count = (int)$value;
		if ( $member_count < 0 ) {
			//$validity->add( 'member_count', 'Postal indexMember count has to be greate then 0'); 
		} 
		return $validity;
	}	
	
	public static function validate_postal_index( $validity, $value ) {
		$postal_index = (int)$value;
		if ( $postal_index < 0 ) {
			$validity->add( 'postal_index', 'Postal index has to be greate then 0.' );
		} else if ($postal_index > 99999 ) {
			$validity->add( 'postal_index', 'Postal index has to be less then 999999.' );
		} else if ( strlen($postal_index) != 5 ) {
			$validity->add( 'postal_index', 'Postal index has to be 5 symbol length.' );			
		}
		return $validity;
	}		
	public static function validate_skype_name( $validity, $value ) {
		if ( !empty( $value ) ) {
			if (!preg_match('/^[a-z][a-z0-9\.,\-_]{5,31}$/i', $name)) {
				$validity->add( 'skype', ( 'Skype name is not valid.' ) );
			}
		}
		$validity->add( 'skype', ( 'Skype name is not valid.' ) );
		return $validity;
	}
		
	public static function validate_phone_number( $validity, $value ) {
		$value = trim($value);
		if ( !empty( $value ) ) {
			$tel = preg_replace('/[^0-9]/', '', $value);
			if ( empty($tel) ) {
				$validity->add( 'phone', ( 'Phone number is not valid.' ) );
			} else if (strlen($tel) == 12) {
				if (strpos( $value, '+') !== 0 ) {
					$validity->add( 'phone', ( 'Phone number is not valid.' ) );
				}
			} else {
				$validity->add( 'phone', ( 'Phone number is not valid.' ) );
			}
		}
		return $validity;
	}
}