<?php
declare(strict_types=1);
namespace AT_Trip;


abstract class FormFieldBase {
	public $name;
	public $value;
	
	protected $error_code;
	private $messages;
	//html
	protected $required = true;
	protected $maxLength = 0;
	protected $placeholder = '';
	protected $pattern = '';
	protected $class = '';
	
	protected $before = '';
	protected $after = '';
	
	public function __construct(string $name, bool $required, int $maxLength, string $pattern = '', string $before = '', string $after = '', array $messages = [], string $sql_name = '', string $classes = '') {
		$this->name = $name;
			
		//html
		$this->required    = $required;
		$this->maxLength   = absint($maxLength);
		$this->placeholder = $placeholder;
		$this->pattern     = $pattern;
		$this->classes     = $classes;		
		
		$this->before      = $before;
		$this->after       = $after;
	}
	

	abstract protected function _render() : string;
	
	function get() {
		if (isset($_POST[$this->name])) {
			$this->sanitize( $_POST[$this->name] );
			if ( $this->maxLength > 0 ) {
				$this->value = substr( $this->value, 0, $this->maxLength );
			}
		} else {
			$this->value = '';
		}
	}		
	

	function render() : string {
		$s = '';
		if ( !empty($this->before) ) {
			$s .= $this->before;
		}
		
		$s .= $this->_render();
		
		if ( !empty($this->after) ) {
			$s .= $this->after;
		}	
		return $s;
	}
	
	private function render_attr() : string {
		$s = ' ';
		if ( $this->required ) {
			$s .= 'required ';
		}

		$s .= 'name="' . $this->name . '" ';
		$s .= 'id="'   . $this->name . '" ';
		
		if ( $this->maxLength > 0 ) {
			$s .= 'maxlength="' . $this->maxLength . '" ';
		}		
		
		if ( !empty($this->pattern) ) {
			$s .= 'pattern="' . $this->pattern . '" ';
		}	
		
		if ( !empty($this->placeholder) ) {
			$s .= 'placeholder="' . $this->placeholder . '" ';
		}
		if ( !empty($this->classes) ) {
			$s .= 'class="' . $this->clases . '" ';
		}
		return $s;
	}
	

	protected function render_input( string $type ) : string {
		$s = '<input type="' . $type . '" ';
		$s .= $this->render_attr();
		
		$s .= ' value="';
		if( isset($_POST[$this->name]) ) {
			$s .= esc_attr($_POST[$this->name]);
		}
		$s .= '"/>';
		return $s;
	}
	
	protected abstract function sanitize();
}


class TextFormField extends FormFieldBase {
	protected function _render() : string {
		return $this->render_input('text');
	}
	
	function sanitize() {
		$this->value = sanitize_text_field($this->value);
	}
}

class DropdownFormField extends TextFormField {
	private $choices;
	
	public function __construct(array $choices, string $name, bool $required, int $maxLength, string $placeholder = '', string $pattern = '', string $before = '', string $after = '', array $messages = [], string $classes = '') {
		$this->choices = $choices;	
		parent::__construct($name, $required, $maxLength, $placeholder, $pattern, $before, $after, $messages, $classes);
	}

	protected function _render()  : string {
		$s = '';
		
		if (!empty($this->placeholder)) {
			$s .= '<label for="' . $this->name . '">' . $this->placeholder . '</label>';
		}
		$s .= '<select required name="' . $this->name . '" id="' . $this->name . '" ';
		if ( !empty($this->classes) ) {
			$s .= 'class="' . $this->clases . '" ';
		}		
		$s .= '>';
		foreach($this->choices as $item) {
			$s .= '<option value="' . $item . '" ';
			$value = isset($_POST[$this->name]) ? esc_attr($_POST[$this->name] ) : '';
			if ( $value == $item ) {
				$s .= ' selected="selected" ';
			}
			$s .= '>' . esc_html($item) . '</option>';
		}
		$s .= '</select>';
		return $s;
	}
}