<?php
declare(strict_types=1);
namespace AT_Lib;


abstract class TaxonomyAdminMetaboxesBase {
	private $taxonomy;
	private $fields = [];
	
	
	public function __construct(string $taxonomy) {
		$this->taxonomy = $taxonomy;
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
						$value = $field->get($term_id);
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
		$this->fields[] = $field;
	}	
	
	
	function add( ) {
		foreach($this->fields as $field) {
			$field->renderAdminInput($field->description);
		}
	}	
	
	
	function edit( $term ) {
		$term_id = $term->term_id;
	 
		 ?>
<table class="form-table">
	<tbody>
	<?php foreach($this->fields as $field) : ?>
	<tr class="form-field">
		<?php 
			$value = $field->get($term_id);
			$field->renderAdminInput($field->description, $value); 
		?>
	</tr>
	<?php endforeach; ?>
</table>
<?php
	}
	
	function save( $term_id ) {
		foreach ( $this->fields as $field ) {
			$field->save($term_id);
		}
			
	} 
/*	
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
*/	
}