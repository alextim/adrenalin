<?php
declare(strict_types=1);
namespace AT_Gear;

class TaxonomyAdminMetaboxes {
	private $taxonomy;
	private $meta_key;
	private $fields = [];
	
	public function __construct(string $taxonomy, string $meta_key) {
		$this->taxonomy = $taxonomy;
		$this->meta_key = $meta_key;
	}
	
	public function init() {
		add_action( $this->taxonomy . '_add_form_fields', [&$this, 'add'], 10, 2 );
		add_action( $this->taxonomy . '_edit_form_fields', [&$this, 'edit'], 10 );
		add_action( 'edited_' . $this->taxonomy , [&$this, 'save'] );  
		add_action( 'create_' . $this->taxonomy , [&$this, 'save'] );
		
		// ADMIN COLUMN - HEADERS
		add_filter( 'manage_edit-' . $this->taxonomy . '_columns', function ( $columns ) {
			foreach ($this->fields as $field) {
				if ($field->show_column) {
					$columns[$field->index] = $field->caption;
				}
			}
			return $columns;
		} );	

		// ADMIN COLUMN - CONTENT
		add_action( 'manage_' . $this->taxonomy . '_custom_column', 
			function ( $empty = '', $custom_column = '', $term_id = 0 ) {
					if ( empty( $_REQUEST['taxonomy'] ) || ! empty( $empty ) ) {
					return;
				}
				
				foreach ($this->fields as $field) {
					if ( $field->index === $custom_column ) {
						$meta = get_term_meta( $term_id, $this->meta_key, false );
						$value = $field->getValue($meta);
						$field->render($value);
						return;
					}
				}
			}, 10, 3 );

		// ADMIN COLUMN - SORTING - MAKE HEADERS SORTABLE
		// https://gist.github.com/906872
		add_filter( 'manage_edit-' . $this->taxonomy . '_sortable_columns', function ( $columns ) {
			$custom = [];
			foreach ($this->fields as $field) {
				if ($field->sortable) {
					$custom[$field->index] = $field->index;
				}
			}	
			
			if (!empty($custom)) {
				return wp_parse_args( $custom, $columns );
			}
			
			return $columns;
		} );			
	}
	
	public function addField(TaxonomyMetaFieldBase $field) {
		$field->meta_key = $this->meta_key;
		$this->fields[] = $field;
	}	
	
	function add( ) {
		foreach($this->fields as $field) {
			$field->renderAdminInput();
		}
	}	
	
	
	function edit( $term ) {
		$term_id = $term->term_id;
	 
		$meta = get_term_meta( $term_id, $this->meta_key, false ); 
		
		foreach($this->fields as $field) {
			$val = $field->getValue($meta);
			$field->renderAdminInput($val);
		}
	}
	
	function save( $term_id ) {
		if ( !isset( $_POST[$this->meta_key] ) ) {
			return;
		}
		$values = $_POST[$this->meta_key];
		
		$meta = get_term_meta( $term_id, $this->meta_key, false ); 	
		
		$keys = array_keys( $values );
		foreach ( $keys as $key ) {
			if ( isset ( $values[$key] ) ) {
				$meta[$key] = $values[$key];
			}
		}			
		update_term_meta( $term_id, $this->meta_key, $meta );
	} 
}

class TaxonomyMetaTextField extends TaxonomyMetaFieldBase {
	protected function renderInput(string $name, $val) {?>
		<input type="text" name="<?php echo $name; ?>" value="<?php echo $val; ?>">
<?php
	}
}

abstract class TaxonomyMetaFieldBase {
	public $meta_key;
	public $index;
	public $caption; 
	public $show_column;
	public $sortable; 
	
	protected abstract function renderInput(string $name, $val);
	
	public function __construct(string $index, string $caption, bool $show_column = false, bool $sortable = false) {
		$this->index       = $index;
		$this->caption     = $caption; 	
		$this->show_column = $show_column; 	
		$this->sortable    = $sortable; 
		
		if (!$show_column && $sortable) {
			throw new Exception('TaxonomyMetaField: !$show_column && $sortable');
		}
	}

	
	public function getValue($meta) {
		$retval = '';
		if ( ! empty( $meta ) ) {
			$retval = $meta[0][$this->index];
		}
		return $retval;		
	}

	
	public function render($val) {
		if (!empty($val)) {
			echo $val;	
		}
	}	

	
	function renderAdminInput($val = '') {
		$name = $this->meta_key . '[' . $this->index . ']';
		?>
	<div class="form-field">
		<label for="<?php echo $name; ?>"><?php echo $this->caption; ?></label>
		<?php $this->renderInput($name, $val);?>
	</div>
	<?php
	}	
}

class TaxonomyMetaUrlField extends TaxonomyMetaFieldBase {
	private $placeholder;
	private $pattern;	
	private $title;	
	
	public function __construct(string $index, string $caption, string $placeholder = '', string $pattern = '', string $title = '' ) {
		$this->placeholder = $placeholder;
		$this->pattern = $pattern;
		$this->title = $title;

		parent::__construct($index, $caption);
	}

	protected function renderInput(string $name, $val) {?>
		<input type="url" placeholder="<?php if (!empty($this->placeholder) ) echo $this->placeholder; ?>" pattern="<?php if (!empty($this->pattern) ) echo $this->pattern; ?>" title="<?php if (!empty($this->title) ) echo $this->title; ?>" name="<?php echo $name; ?>" value="<?php echo $val; ?>">
		<span class="validity"></span>
<?php
	}
}

final class TaxonomyMetaPositiveNumberField extends TaxonomyMetaFieldBase {
	protected function renderInput(string $name, $val) {?>
		<input type="number" min="0" name="<?php echo $name; ?>" value="<?php echo $val; ?>">
<?php
	}
}
