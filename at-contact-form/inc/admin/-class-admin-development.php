<?php
final class AT_Contact_Form_Admin {
	private const PAGE_SLUG       = 'atcf_setting_admin';
	private const PAGE_TITLE      = 'AT Contact Form';	
	private const SECTION_GENERAL = 'atcf_setting_section_general';
	private const SECTION_CAPTCHA = 'atcf_setting_section_captcha';
	


	public function __construct() {
		add_action( 'admin_init',        [&$this, 'page_init'] );
		add_action( 'admin_menu',        [&$this, 'add_plugin_page']);	
	    add_action( 'init',              [&$this, 'options_init'], 9 );		
	}
	
	function add_plugin_page() {
        add_options_page( 'Settings Admin',  self::PAGE_TITLE, 'manage_options', 
			self::PAGE_SLUG, [&$this, 'render_admin_page'] );	
	}
	
	private function get_default_options() {
		 $options = array(
			  'enable_db'          => true,
			  'email'              => '',
			  'enable_email'       => false,
			  'enable_admin_email' => false,
			  
			  'use_captcha'        => false,
			  'site_key'           => '',
			  'secret_key'         => '',
		 );
		 return $options;
	}

	function options_init() {
		 $options = get_option( AT_CF_OPTIONS_NAME );
		 if ( false === $options ) {
			  $options = $this->get_default_options();
		 }
		 update_option( AT_CF_OPTIONS_NAME, $options );
	}

	
	private function get_settings_page_tabs() {
		 $tabs = array(
			  'general' => 'General',
			  'captcha' => 'Captcha'
		 );
		 return $tabs;
	}	
	
	private function admin_options_page_tabs( $current = 'general' ) {
		 if ( isset ( $_GET['tab'] ) ) :
			  $current = $_GET['tab'];
		 else:
			  $current = 'general';
		 endif;
		 
		 $tabs = $this->get_settings_page_tabs();
		 $links = array();
		 $page_slug = self::PAGE_SLUG;
		 
		 foreach( $tabs as $tab => $name ) :
			  if ( $tab == $current ) :
				   $links[] = '<a class="nav-tab nav-tab-active" href="?page=' . $page_slug . '&tab=' . $tab .'">' . $name . '</a>';
			  else :
				   $links[] = '<a class="nav-tab" href="?page=' . $page_slug . '&tab=' . $tab . '">' . $name . '</a>';
			  endif;
		 endforeach;
		 ?>
		<div id="icon-themes" class="icon32"><br /></div>
		<h2><?php echo self::PAGE_TITLE; ?></h2>
		<?php settings_errors(); ?>			 
		
		<h2 class="nav-tab-wrapper">
		  <?php foreach ( $links as $link ) echo $link; ?>
		</h2>
		<?php
	}
	
	function render_admin_page() {
		$this->options = get_option( AT_CF_OPTIONS_NAME );
		?>
		<div class="wrap">
		<?php $this->admin_options_page_tabs(); 
			if ( isset( $_GET['settings-updated'] ) ) {
			  echo "<div class='updated'><p>Theme settings updated successfully.</p></div>";
			} ?>
			<form action="options.php" method="post">
			    <?php 
					settings_fields( AT_CF_OPTIONS_NAME );
					//do_settings_sections( self::AT_CF_OPTION_GROUP );
					do_settings_sections( self::PAGE_SLUG );
					$tab = ( isset( $_GET['tab'] ) ? $_GET['tab'] : 'general' ); 

				?>
				<?php submit_button(); ?>
				<?php
				/*
				<input name="<?php echo AT_CF_OPTIONS_NAME; ?>[submit-<?php echo $tab; ?>]" type="submit" class="button-primary" value="<?php esc_attr_e('Save Settings', 'oenology'); ?>" />
				<input name="<?php echo AT_CF_OPTIONS_NAME; ?>[reset-<?php echo $tab; ?>]" type="submit" class="button-secondary" value="<?php esc_attr_e('Reset Defaults', 'oenology'); ?>" />
				*/
				?>
			</form>
		</div>
	<?php }	
	
	public function page_init() {  
global $pagenow;
	 
if ( 'options-general.php' == $pagenow && isset( $_GET['page'] ) && self::PAGE_SLUG  === $_GET['page'] ) :
     if ( isset ( $_GET['tab'] ) ) :
          $tab = $_GET['tab'];
     else:
          $tab = 'general';
     endif;


     switch ( $tab ) :
          case 'general' :
		  {
        register_setting( self::PAGE_SLUG, AT_CF_OPTIONS_NAME, [&$this, 'sanitize'] );

        add_settings_section( self::SECTION_GENERAL,  'General', [&$this, 'print_general_section_info'], self::PAGE_SLUG );  

        add_settings_field( 'enable_db', 'Save to database', [&$this, 'enable_db_callback'], 
			self::PAGE_SLUG, self::SECTION_GENERAL ); 		
        add_settings_field( 'email',        'E-mail',      [&$this, 'email_callback'],        
			self::PAGE_SLUG, self::SECTION_GENERAL ); 	
        add_settings_field( 'enable_email', 'Send E-mail', [&$this, 'enable_email_callback'], 
			self::PAGE_SLUG, self::SECTION_GENERAL ); 		
        add_settings_field( 'enable_admin_email', 'Send copy to admin', [ &$this, 'enable_admin_email_callback' ], 
			self::PAGE_SLUG, self::SECTION_GENERAL ); 		
			  
		  }
               break;
          case 'captcha' :
		  {
        register_setting( self::PAGE_SLUG, AT_CF_OPTIONS_NAME, [&$this, 'sanitize'] );

        add_settings_section( self::SECTION_CAPTCHA, 'Google Captcha', [&$this, 'print_captcha_section_info'], self::PAGE_SLUG ); 		

        add_settings_field( 'use_captcha', 'Use Captcha',[ &$this, 'use_captcha_callback' ],     self::PAGE_SLUG, self::SECTION_CAPTCHA ); 		
	    add_settings_field( 'site_key',   'Site Key',    [ &$this, 'site_key_callback' ],        self::PAGE_SLUG, self::SECTION_CAPTCHA );      
        add_settings_field( 'secret_key', 'Secret Key',  [ &$this, 'secret_key_callback' ],      self::PAGE_SLUG, self::SECTION_CAPTCHA );  
        add_settings_field( 'captcha_working', 'Status', [ &$this, 'captcha_working_callback' ], self::PAGE_SLUG, self::SECTION_CAPTCHA );		
			  
		  }

               break;
     endswitch;
endif;


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
	private function checked($name) {
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