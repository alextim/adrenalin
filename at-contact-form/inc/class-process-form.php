<?php
final class AT_Process_ContactForm extends AT_CaptchaForm { 
	private $fixed_subject;
	private $subject_max_length = 100;

	public function __construct(bool $enable_last_name = false, bool $enable_phone = false, $fixed_subject = '') {
		parent::__construct();
		
		$this->fields['first_name'] = new AT_TextFormField('first_name', true, 60, 'Ваше имя', '[A-Za-zА-Яа-яЁё ]+', 
			'<li class="atcf-i-field atcf-i-first-name">',  '</li>',
			[
				'required'=> 'Пожалуйста введите Ваше имя!',
			]
		);
		if ($enable_last_name) {
			$this->fields['last_name'] = new AT_TextFormField('last_name', false, 30, 'Ваша фамилия', '[A-Za-zА-Яа-яЁё ]+', 
				'<li class="atcf-i-field">', '</li>',
				[
					'required' => 'Пожалуйста введите Вашу фамилию!',
				]
			);
		}
		if ($enable_phone) {
			$this->fields['phone'] = new AT_PhoneFormField('phone', true, 15, 'Ваш телефон', '', 
				'<li class="atcf-i-field atcf-i-phone">', '</li>',
				[
					'required' => 'Пожалуйста введите Ваш номер телефона!',
					'invalid'  => 'Вы ввели неверный номер телефона!',
				]
			);
		}
		$this->fields['email'] = new AT_EmailFormField('email', true, 50, 'Ваш e-mail', '', 
			'<li class="atcf-i-field atcf-i-email">', '</li>',
			[
				'required'     => 'Пожалуйста введите Ваш e-mail!',
				'invalid'    => 'Вы ввели неверный e-mail!'
			]
		);

		$this->setSubjectField($fixed_subject);

		
		$this->fields['message'] = new AT_TextareaFormField('messageText', true, 512, 'Ваше сообщение', '', 
			'<li>',  '</li>', 
			[
				'default'=>'Пожалуйста введите сообщение!'
			], 
			'message'
		);
	}
	

	public function setSubjectField( $subject ) {
		if ( empty($subject)) {
			
			$this->fields['subject'] = new AT_TextFormField('subject', true, $this->subject_max_length, 'Тема сообщения', '', 
				'<li class="atcf-i-field atcf-i-subject">', '</li>', 
				[
					'required' => 'Пожалуйста введите тему сообщения!'
				] 
			);
		} elseif ( is_array($subject) ) {
			$this->fields['subject'] = new AT_DropdownFormField($subject, 'subject', true, $this->subject_max_length, 'Тема сообщения', '', 
				'<li class="atcf-i-field">',  '</li>', ['required' => 'Пожалуйста введите тему сообщения!']);
		} else {
			$this->fixed_subject = $subject;
			
			if ( array_key_exists('subject', $this->fields) ) {
				unset( $this->fields['subject'] );
			}
		}	
	}

	static private function get_message_text(string $key) : string {
		$messages = [
			'success'            => 'Спасибо! Ваше сообщение отправлено!',
			'fault'              => 'Ошибка! Ваше сообщение не отправлено! Попробуйте повторить позже.',
			'nonce_not_valid'    => 'Ошибка! Bad nonce!',
			'empty_capture'      => 'Пожалуйста введите Captcha!',
			'ivalid_capture'     => 'Captсha введена неправильно!',
		]; 
		return $messages[$key];
	}
	

	protected function validate() : bool {
		if (! wp_verify_nonce( $_POST['_wpnonce'], '_contact_form_submit' ) ) {
			$this->info_message  = self::get_message_text('nonce_not_valid');
			$this->hasError = true;
			return false;
		}
		
		foreach ($this->fields as $field) {
			if (!$field->validate() ){
				$this->hasError = true;
			}
		}
		
		$this->validateCaptcha();
		
		return !$this->hasError;
	}
	
	protected function sanitize() {
		foreach ($this->fields as $field) {
			$field->sanitize();
		}
		if ( !empty($this->fixed_subject) ) {
			$this->fixed_subject = sanitize_text_field($this->fixed_subject);
			$this->fixed_subject = substr($this->fixed_subject, 0, $this->subject_max_length);
		}		
	}
	
	protected function submit() {
		$sent  = false;
		$saved = false;
		
		if ($this->enable_admin_email()) {
			$emailTo = get_option('admin_email');
			if ( $this->send_email( $emailTo ) ) {
				$sent = true;
			}
		}
		
		if ($this->enable_email()) {
			$emailTo = $this->get_email();
			if ( $this->send_email( $emailTo ) ) {
				$sent = true;				
			}
		}
		
		if ($this->enable_db()) {
			$saved = $this->save_message_to_db();
		}
		
		if ( $this->enable_db() ) {
			$this->hasError = !$saved;
		} else {
			$this->hasError = !$sent;
		}
		
		if ( $this->hasError ) {
			$this->info_message = self::get_message_text('fault');			
		} else {
			$this->info_message = self::get_message_text('success');			
		}
	}
	
	private function send_email( $emailTo ) {
		if ( !is_email($emailTo) ) {
			return false;
		}
		$name = $this->fields['first_name']->value;
		if ( !empty($this->fields['last_name']->value) ) {
			$name .= ' ' . $this->fields['last_name']->value;
		}
				
		$email   =  $this->fields['email']->value;
		$message =  $this->fields['message']->value;
		
		$url = get_site_url();
		
		$to_remove = ['http://', 'https://' ];
		foreach ( $to_remove as $item ) {
			$url = str_replace($item, '', $url); // to: www.example.com
		}
		$headers = 'Content-type: text/plain;charset=utf-8' . "\r\n";
		$headers .= sprintf('From: %s %s <%s>',  $url, $name, $email) . "\r\n";
		$headers .= 'Reply-To: ' . $email;
		 

		$subject = sprintf('Contact Form Submission from %s, visitor of %s', $name, $url);
		
		$body = 'Name: ' . $name . "\n\n";
		$body .= 'Email: ' . $email . "\n\n";
		$body .= 'Subject: ';
		if ( empty($this->fixed_subject) ) {
			$body .= $this->fields['subject']->value . "\n\n";
		} else {
			$body .= $this->fixed_subject . "\n\n";
		}
		$body .= 'Comments: ' . $message;
		 
		//mail($emailTo, $subject, $body, $headers);
		return wp_mail($emailTo, $subject, $body, $headers);
	} 
	
	
	private function save_message_to_db() {
		global $post;
		$data = ['src_post_id' => $post->ID ];
		 
		if ( !empty($this->fixed_subject) ) {
			$data['subject'] = $this->fixed_subject;
		}
		
		foreach ($this->fields as $field) {
			$data[$field->sqlName] = $field->value;
		}		
		
        global $wpdb;
		
		$message_table = $wpdb->prefix . AT_CF_MESSAGE_TABLE;
        $wpdb->insert($message_table, $data); 
        $rowid = $wpdb->insert_id;
		
		$address_book_table = $wpdb->prefix . AT_CF_ADDRESS_BOOK_TABLE;
		$id = $wpdb->get_var( $wpdb->prepare('SELECT id FROM ' . $address_book_table . ' WHERE email=%s', $this->fields['email']->value) );

		$ip = \AT_Lib\getIP();

		if ( empty($id) ) {
			$data = [
				'ip'               => $ip,
				'email'            => $this->fields['email']->value,
				'is_subscribed'    => 1, //!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
				'first_name'       => $this->fields['first_name']->value,
				//'middle_name'      => ,
				//'birthdate'        => ,
				//'sex'              => ,
				//'street_address_1' => ,
				//'street_address_2' => ,
				//'city'             => ,
				//'country'          => ,
				//'postal_index'     => ,
				//'passport'     => ,
				//'sport_level'  => ,
			];
			if ( isset($this->fields['last_name']) ) {
				$data['last_name'] = $this->fields['last_name']->value;
			}
			if ( isset($this->fields['phone']) ) {
				$data['phone'] = $this->fields['phone']->value;
			}

			$wpdb->insert( $address_book_table, $data );
		} else {
			$data = [
				'ip'         => $ip,
				'visited'    => current_time( 'mysql' ),
			];
			if ( !empty($this->fields['first_name']->value) ) {
				$data['first_name'] = $this->fields['first_name']->value;
			}
			if ( !empty($this->fields['last_name']->value) ) {
				$data['last_name'] = $this->fields['last_name']->value;
			}
			if ( !empty($this->fields['phone']->value) ) {
				$data['phone'] = $this->fields['phone']->value;
			}
			$number = $wpdb->update( $address_book_table, $data, ['id' => absint($id)], null, ['%d'] );
			if ( $number === false ) {
				die('failed to update:' . mysqli_error());
			} elseif ( $number === 0) {
				die('failed to update: id=' . $id . ' not found');				
			}
		}
	    return $rowid !== 0;
	}
	
	public function render_form() {
	?>	
	<div class="atcf-wrapper">
	
		<?php if (!empty($this->info_message) ) : ?>
			<div class="atcf-message-wrapper">
				<span class="<?php echo($this->hasError ? 'errormsg': 'successmsg'); ?>"><?php echo esc_html($this->info_message);?></span>
			</div>
		<?php endif; ?>

		<form action="<?php the_permalink(); ?>" id="contactForm" method="post">
			<ul class="atcf-fields">
				<?php
				foreach ($this->fields as $field) {
					echo $field->render();
				}				
				if ($this->use_captcha()) : ?>
					<li>
						<?php echo $this->reCAPTCHA->getHtml();?>
					</li>
				<?php endif; ?>
				
				<li>
					<input type="submit" value="Отправить"/>
				</li>

			</ul>
			<input type="hidden" name="submitted" id="submitted" value="true" />
			<?php echo wp_nonce_field( '_contact_form_submit' ) ?>
		</form>
	</div>
	<?php	
	} 
}