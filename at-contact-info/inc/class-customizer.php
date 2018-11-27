<?php
final class AT_Contact_Info_Customizer {
	/*
	private static $instance;
	
	public static function get_instance() {
        if ( null == self::$instance ) {
			self::$instance = new self;
        }
        return self::$instance;
    } 	
	
    private function __construct() {	
		add_action( 'customize_register', [&$this, 'register'], 51);
	}
	*/

	public static function get_panels(array $defaults) : array {

		$validate_phone_cb = '';
		$validate_skype_cb = '';
		$sanitize_phone_cb = 'at_sanitize_phone';
		
		$sections = [ 
		  'aco_address_settings' => [
			    'Address', 
			    [
					['company_name',     '' , 'Company name',     '', 'text'],
					['street_address_1', '' , 'Street address 1', '', 'text'],
					['street_address_2', '' , 'Street address 2', '', 'text'],
					['city',             '' , 'City',             '', 'text'],
					['postal_index', 'at_sanitize_postal_index', 'Postal Index', '', 'number'],
					['country',          '',  'Country',          '', 'text'],
			   ]
          ]	   
		  'aco_communication_settings' => [
				'Communication',
				[
					['phone_1', $sanitize_phone_cb,      'Phone 1',  '', 'text', $validate_phone_cb ],
					['phone_2', $sanitize_phone_cb,      'Phone 2',  '', 'text', $validate_phone_cb ],
					['phone_3', $sanitize_phone_cb,      'Phone 3',  '', 'text', $validate_phone_cb ],
					['fax',     $sanitize_phone_cb,      'Fax',      '', 'text', $validate_phone_cb ],
					['email_1', 'sanitize_email',	     'E-mail 1', '', 'email'],
					['email_2', 'sanitize_email',	     'E-mail 2', '', 'email'],
					['skype',   'at_sanitize_skype',     'Skype',    '', 'text', $validate_skype_cb ],
					['whatsapp',$sanitize_phone_cb,      'WhatsApp', '', 'text', $validate_phone_cb ],
					['tg',      'at_sanitize_telegram',  'Telegram', '', 'text'],
					['viber',   $sanitize_phone_cb,	     'Viber',    '', 'text', $validate_phone_cb],
					['url',     'esc_url_raw',           'URL',      '', 'url'],
				]	
          ],			   
		  'aco_opening_time_settings' => [	  
				'Opening Time',
				[
					['opening_time_1', '' , 'Line #1', '', 'text'],
					['opening_time_2', '' , 'Line #2', '', 'text'],
					['opening_time_3', '' , 'Line #3', '', 'text'],
					['opening_time_4', '' , 'Line #4', '', 'text'],
					['opening_time_5', '' , 'Line #5', '', 'text'],
					['opening_time_6', '' , 'Line #6', '', 'text'],
					['opening_time_7', '' , 'Line #7', '', 'text'],
				]	
		  ],
		];
		$panels = [ 
		  'contact_panel'=> [ 'Contact', 'Enter contact data here: address, phones etc', $sections ],
		];
		return $panels;
		
	}
}