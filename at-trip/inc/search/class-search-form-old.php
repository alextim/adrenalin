<?php
declare(strict_types=1);
namespace AT_Trip;


final class SearchForm extends FormBase { 
	public function __construct() {
		parent::__construct();
			
		$this->fields['subject'] = new AT_DropdownFormField($subject, 'subject', true, $this->subject_max_length, 'Тема сообщения', '', 
			'<li class="atcf-i-field">',  '</li>');
	}

	static private function get_message_text(string $key) : string {
		$messages = [
			'success'            => 'Спасибо!',
			'fault'              => 'Ошибка!',
			'nonce_not_valid'    => 'Ошибка! Bad nonce!',
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
			$field->get();
		$this->hasError = false;

		return !$this->hasError;
	}
	
	protected function sanitize() {
		foreach ($this->fields as $field) {
			$field->sanitize();
		}
	}
	
	protected function submit() {
		$sent  = false;
		
		
		if ( $this->hasError ) {
			$this->info_message = self::get_message_text('fault');			
		} else {
			$this->info_message = self::get_message_text('success');			
		}
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