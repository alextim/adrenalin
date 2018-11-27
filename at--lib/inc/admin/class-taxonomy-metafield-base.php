<?php
declare(strict_types=1);
namespace AT_Lib;

abstract class TaxonomyMetaFieldBase {
	public $index;
	public $caption; 
	public $description; 
	public $show_column;
	public $sortable; 
	
	protected abstract function renderInput(string $name, $val);
	
	public function __construct(string $index, string $caption, string $description = '', bool $show_column = false, bool $sortable = false) {
		$this->index       = $index;
		$this->caption     = $caption; 	
		$this->description = $description; 	
		$this->show_column = $show_column; 	
		$this->sortable    = $sortable; 
		
		if (!$show_column && $sortable) {
			throw new Exception('TaxonomyMetaField: !$show_column && $sortable');
		}
	}

	
	public function save($term_id) {
		if ( !isset( $_POST[$this->index] ) ) {
			return;
		}
		$value = $_POST[$this->index];
		
		update_term_meta( $term_id, $this->index, $value );		
		
	}

	
	public function get($term_id) {
		$value = get_term_meta($term_id, $this->index, true );
		$retval = '';
		if ( ! empty( $value ) ) {
			$retval = $value;
		}
		return $retval;		
	}

	
	public function render($val) {
		if (!empty($val)) {
			echo $val;	
		}
	}	

	
	function renderAdminInput(string $description = '', $val = '') {
		$name = $this->index;	?>
		<th scope="row"><label for="<?php echo $name; ?>"><?php echo $this->caption; ?></label></th>
		<td>
			<?php $this->renderInput($name, $val);?>
			<?php if (!empty($description)) : ?>
				<p class="description"><?php echo $description; ?></p>
			<?php endif; ?>
		</td>		
	<?php
	}	
}