<?php
final class AT_Contact_Form_Admin {
	private const PAGE_SLUG = 'atcf-setting-admin';
	private const PAGE_TITLE = 'AT Contact Form';
	private const SECTION_GENERAL = 'atcf_setting_section_general';
	private const SECTION_CAPTCHA = 'atcf_setting_section_captcha';
		
	private const OPTION_GROUP = 'atcf_option_group';	
	
	public function __construct() {
		add_action( 'admin_init', [&$this, 'page_init'] );
		add_action( 'admin_menu', [&$this, 'add_plugin_page']);	
	}

	function add_plugin_page() {
        add_options_page( 'Settings Admin', self::PAGE_TITLE, 'manage_options', self::PAGE_SLUG, [ &$this, 'render_admin_page' ] );	
	}
	
	
	function render_admin_page() {
        $this->options = get_option( AT_CF_OPTIONS_NAME );
        ?>
        <div class="wrap">
            <h1><?php echo self::PAGE_TITLE; ?></h1>
            <form method="post" action="options.php">
            <?php
                 settings_fields( self::OPTION_GROUP );
                do_settings_sections( self::PAGE_SLUG );
                submit_button();
            ?>
            </form>
        </div>
        <?php
    }
	
	public function page_init() {        
        register_setting( self::OPTION_GROUP, AT_CF_OPTIONS_NAME, [&$this, 'sanitize'] );

        add_settings_section( self::SECTION_GENERAL,  'General', [&$this, 'print_general_section_info'], self::PAGE_SLUG );  
        add_settings_field( 'enable_db', 'Save to database', [&$this, 'enable_db_callback'], self::PAGE_SLUG, self::SECTION_GENERAL ); 		

        add_settings_field( 'email',        'E-mail',      [&$this, 'email_callback'],        self::PAGE_SLUG, self::SECTION_GENERAL ); 	
        add_settings_field( 'enable_email', 'Send E-mail', [&$this, 'enable_email_callback'], self::PAGE_SLUG, self::SECTION_GENERAL ); 		
        add_settings_field( 'enable_admin_email', 'Send copy to admin', [ &$this, 'enable_admin_email_callback' ], self::PAGE_SLUG, self::SECTION_GENERAL ); 		

		
        add_settings_section( self::SECTION_CAPTCHA, 'Google Captcha', [&$this, 'print_captcha_section_info'], self::PAGE_SLUG ); 		

        add_settings_field( 'use_captcha', 'Use Captcha',[ &$this, 'use_captcha_callback' ],     self::PAGE_SLUG, self::SECTION_CAPTCHA ); 		
	    add_settings_field( 'site_key',   'Site Key',    [ &$this, 'site_key_callback' ],        self::PAGE_SLUG, self::SECTION_CAPTCHA );      
        add_settings_field( 'secret_key', 'Secret Key',  [ &$this, 'secret_key_callback' ],      self::PAGE_SLUG, self::SECTION_CAPTCHA );  
        add_settings_field( 'captcha_working', 'Status', [ &$this, 'captcha_working_callback' ], self::PAGE_SLUG, self::SECTION_CAPTCHA );		
    }
    private static function filter_string( $string ) {
        return trim(filter_var($string, FILTER_SANITIZE_STRING)); //must consist of valid string characters
    }	
    private static function is_valid_key( $string ) {
        if (strlen($string) === 40) {
            return true;
        } else {
            return false;
        }
    }	
    public function sanitize( $input ) : array {
        $new_input = [];
        if( isset( $input['site_key'] ) ) {
            $new_input['site_key'] = self::filter_string( $input['site_key'] );
		}

        if( isset( $input['secret_key'] ) ) {
            $new_input['secret_key'] = self::filter_string( $input['secret_key'] );
		}

        if( isset( $input['email'] ) ) {
            $new_input['email'] = sanitize_email( $input['email'] );
		}

		$enable_db = 0;
		if( isset( $input['enable_db'] ) ) {
			if (sanitize_text_field( $input['enable_db'] ) === 'yes') {
				$enable_db = 1;
			}
		}
		$new_input['enable_db'] = $enable_db;
		
		$enable_email = 0;
		if( isset( $input['enable_email'] ) ) {
			if (sanitize_text_field( $input['enable_email'] ) === 'yes') {
				$enable_email = 1;
			}
		}
		$new_input['enable_email'] = $enable_email;
		
		$enable_admin_email = 0;
		if( isset( $input['enable_admin_email'] ) ) {
			if (sanitize_text_field( $input['enable_admin_email'] ) === 'yes') {
				$enable_admin_email = 1;
			}
		}
		$new_input['enable_admin_email'] = $enable_admin_email;
		
		$use_captcha = 0;
		if( isset( $input['use_captcha'] ) ) {
			if (sanitize_text_field( $input['use_captcha'] ) === 'yes') {
				$use_captcha = 1;
			}
		}
		$new_input['use_captcha'] = $use_captcha;	
		
		$new_input['captcha_working'] = 0;
		if ($use_captcha && 
		 ( isset($new_input['site_key']) && self::is_valid_key($new_input['site_key']) && 
		   isset($new_input['secret_key']) && self::is_valid_key($new_input['secret_key']) 					
		 )
		) {
			$new_input['captcha_working'] = 1;
		}
			
        return $new_input;
    }

    public function print_general_section_info() { echo 'Enter general settings below:'; }
    public function print_captcha_section_info() { echo 'Enter captcha settings below:'; }

    public function site_key_callback()   { $this->textbox_callback('site_key');  }
    public function secret_key_callback() { $this->textbox_callback('secret_key');  }
    public function email_callback()      { $this->textbox_callback('email');  }

	public function captcha_working_callback() {
		$working = (isset($this->options['captcha_working']) && absint($this->options['captcha_working']) ) ? true : false;
		
	    printf('<p>Captcha is %s</p>', $working ? 'OK' : '<b>NOT</b> working!');
	}
	private function checked($name) : string {
		$checked = 0;
		if (isset( $this->options[$name] )) {
			$checked = absint($this->options[$name] );
		}
		return $checked ? 'checked' : '';		
	}
	public function enable_db_callback()    { $this->checbox_callback('enable_db');  }
	public function enable_email_callback() { $this->checbox_callback('enable_email');  }
	public function enable_admin_email_callback() { $this->checbox_callback('enable_admin_email');  }
	public function use_captcha_callback()  { $this->checbox_callback('use_captcha');   }
	public function textbox_callback($name) {
        printf(
            '<input type="text" id="%s" name="%s[%s]" size="60" value="%s" />', $name, AT_CF_OPTIONS_NAME, $name,
            isset( $this->options[$name] ) ? esc_attr( $this->options[$name]) : ''
        );
    }	
	public function checbox_callback($name) {
		$checked = $this->checked($name);
		
        printf('<input type="checkbox" id="%s" name="%s[%s]" size="60" value="yes" %s />', $name, AT_CF_OPTIONS_NAME, $name, $checked );
    }
}	