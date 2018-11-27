<?php
abstract class AT_Form {
	protected $options;
	protected $hasError = false;
	protected $info_message = '';
	
	public function __construct() {
		$this->options = get_option( AT_CF_OPTIONS_NAME );
	}
	
	public abstract function render_form();
	
	protected abstract function validate() : bool;
	protected abstract function sanitize();
	protected abstract function submit();	
	
	public function process_data() : bool {
		if (!$this->is_submit_enabled()) {
			return false;
		}
		if( isset($_POST['submitted'])) {
			if ( $this->validate() ) {
				$this->sanitize();
				$this->submit();
			}
		}
		return true;
	}
	
	public function is_submit_enabled() {
		return ($this->enable_db() || ($this->enable_email() && !empty($this->get_email())) || $this->enable_admin_email() );
	}

	protected function enable_db() { 
		return (isset( $this->options['enable_db'] ) && absint($this->options['enable_db']));
	}
	
	protected function enable_email() { 
		return (isset( $this->options['enable_email'] ) && absint($this->options['enable_email']));
	}
	protected function enable_admin_email() { 
		return (isset( $this->options['enable_admin_email'] ) && absint($this->options['enable_admin_email']));
	}

	protected function get_email() { 
		return isset( $this->options['email'] ) ? $this->options['email'] : '';
	}
}

abstract class AT_CaptchaForm extends AT_Form {
	protected $reCAPTCHA;
	
	public function __construct() {
		parent::__construct();
		
		if ( $this->is_submit_enabled() ) {
			$this->reCAPTCHA = new reCAPTCHA();
			$this->reCAPTCHA->setSiteKey( isset( $this->options['site_key'] ) ? $this->options['site_key'] : '' );
			$this->reCAPTCHA->setSecretKey( isset( $this->options['secret_key'] ) ? $this->options['secret_key'] : '' );
			add_action('wp_head', function() { echo $this->reCAPTCHA->getScript();});
		}
	}	
	
	public function use_captcha() { 
		return  (isset( $this->options['use_captcha'] ) && absint($this->options['use_captcha'] )); 
	}
	
	protected function validateCaptcha() {
		if ($this->use_captcha()) {
			
			$recaptcha_response = isset($_POST['g-recaptcha-response']) ? $_POST['g-recaptcha-response'] : '';
			
			if ( empty($recaptcha_response) ) {
				$this->info_message = self::get_message_text('empty_capture');	
				$this->hasError = true;
			} elseif ( !$this->reCAPTCHA->isValid($recaptcha_response) ) {
				$this->info_message = self::get_message_text('ivalid_capture');	
				$this->hasError = true;	
//				var_dump( $this->reCAPTCHA->getErrorCodes() );				
			}

		}		
	}
}