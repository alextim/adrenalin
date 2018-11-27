<?php
declare(strict_types=1);
namespace AT_Trip;


abstract class FormBase {
	protected $hasError = false;
	protected $info_message = '';
	
	public abstract function render_form();
	
	protected abstract function validate() : bool;
	protected abstract function sanitize();
	protected abstract function submit();	
	
	public function process_data() : bool {

		if( isset($_POST['submitted'])) {
			if ( $this->validate() ) {
				$this->sanitize();
				$this->submit();
			}
		}
		return true;
	}
}