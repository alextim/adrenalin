<?php
abstract class AT_FormField {
	public $name;
	public $sqlName;
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
	
	public function __construct(string $name, bool $required, int $maxLength, string $placeholder = '', string $pattern = '', string $before = '', string $after = '', array $messages = [], string $sql_name = '', string $classes = '') {
		$this->name = $name;
		$this->sqlName = empty($sql_name) ? $name : $sql_name;
			
		//html
		$this->required    = $required;
		$this->maxLength   = absint($maxLength);
		$this->placeholder = $placeholder;
		$this->pattern     = $pattern;
		$this->classes     = $classes;		
		
		$this->before      = $before;
		$this->after       = $after;

		$this->messages    = $messages;
	}
	
	abstract protected function _validate() :  bool;
	abstract protected function _sanitize();
	abstract protected function _render() : string;
	
	function validate() :  bool {
		if (!$this->preValidate()) {
			return false;			
		}

		if (!$this->_validate()) {
			return false;
		} 

		if (!$this->postValidate()) {
			return false;			
		}		

		return true;
	}		
	
	protected function preValidate() :  bool {
		$this->value = isset($_POST[$this->name]) ? $_POST[$this->name] : '';
		if ( $this->required && empty($this->value) ) {
			$this->error_code = 'required';
			return false;
		}		
		return true;
	}

	protected function postValidate() :  bool {
		if ( $this->maxLength > 0 && strlen($this->value) > $this->maxLength ) {
			$this->error_code = 'too_long';
			return false;			
		}
		return true;
	}
	
	function render() : string {
		$s = '';
		if ( !empty($this->before) ) {
			$s .= $this->before;
		}
		
		$s .= $this->_render();
		
		$s .= $this->renderError();

		if ( !empty($this->after) ) {
			$s .= $this->after;
		}	
		return $s;
	}
	
	protected function renderError() : string {
		$s = '';
		if (!empty($this->error_code) ) {
			$s = '<div class="error">';  
			$msg = '';
			if ( !empty($this->messages) ) {
				if (isset($this->messages[$this->error_code])) {
					$msg = $this->messages[$this->error_code];
				} elseif (isset($this->messages['default']) ) {
					$msg = $this->messages['default'];
				}
			}
			if ( empty($msg) ) {
				$msg = 'Ошибочное значение!';
			}
				
			$s .= esc_html( $msg );
			$s .= '</div>';
		}
		return $s;		
	}
	
	protected function render_attr() : string {
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
	
	protected function render_textarea(int $rows, int $cols)  : string {
		if ($rows <= 0 ) {
			$rows = 10;
		}
		if ($cols <= 0 ) {
			$cols = 40;
		}
		
		$s = '<textarea rows="' . $rows . '" cols="' . $cols . '" ' . $this->render_attr() . '>';
		
		if( isset($_POST[$this->name]) ) {
			$s .= esc_html($_POST[$this->name]);
		}
		$s .= '</textarea>';
		
		return $s;		
	}
	
	protected function render_input( string $type ) : string {
		$s = '<input type="' . $type . '" ';
		$s .= $this->render_attr();
		
		$s .= ' value="';
		if( isset($_POST[$this->name]) ) {
			$s .= esc_attr($_POST[$this->name]);
		}
		$s .= '" ';
		
		$s .= '/>';
		return $s;
	}
	
	function sanitize() {
		$this->_sanitize();
		$this->postSanitize();
	}
	protected function postSanitize() {
		if ( $this->maxLength > 0 ) {
			$this->value = substr( $this->value, 0, $this->maxLength );
		}
	}	
}


class AT_TextFormField extends AT_FormField {
	protected function _render() : string {
		return $this->render_input('text');
	}
	
	protected function _validate() :  bool {
		$this->value = sanitize_text_field($this->value);
		if (empty($this->value)) {
			$this->error_code = 'invalid';
			return false;
		} 
		return true;
	}
	
	protected function _sanitize() {
		$this->value = sanitize_text_field($this->value);
	}
}

class AT_TextareaFormField extends AT_TextFormField {
	public $rows;
	public $cols;
	protected function _render() : string {
		return $this->render_textarea(absint($this->rows), absint($this->cols));
	}
}

class AT_PhoneFormField extends AT_TextFormField {
	protected function _render()  : string  {
		return $this->render_input('tel');
	}
	
	protected function _validate() :  bool {
		$this->value = sanitize_text_field($this->value);
		if (empty($this->value)) {
			$this->error_code = 'invalid';
			return false;
		} 
		$this->value = preg_replace('/[^0-9+]/', '', $this->value);
		$tel_length = strlen($this->value);	
		if ($tel_length < 7 || $tel_length > 13 ) {
			$this->error_code = 'invalid';
			return false;
		}		
		return true;
	}
}

class AT_EmailFormField extends AT_FormField {
	protected function _render() : string {
		return $this->render_input('email');
	}
	
	protected function _validate() :  bool {
		if (!is_email($this->value)) {
			$this->error_code = 'invalid';
			return false;
		} 
		return true;
	}
	
	protected function _sanitize() {
		$this->value = sanitize_email($this->value);
	}
}


class AT_DropdownFormField extends AT_TextFormField {
	private $choices;
	
	public function __construct(array $choices, string $name, bool $required, int $maxLength, string $placeholder = '', string $pattern = '', string $before = '', string $after = '', array $messages = [], string $sql_name = '', string $classes = '') {
		$this->choices = $choices;	
		parent::__construct($name, $required, $maxLength, $placeholder, $pattern, $before, $after, $messages, $sql_name, $classes);
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
