<?php
declare(strict_types=1);
namespace AT_Trip;

abstract class RepeaterFieldBase extends Field {
	protected $tableCaption;
	
	abstract protected function prepareArray() : array;
	
	public function get() {
		return get_post_meta($this->postID, $this->name, true);
	}	

	
	public function save() {
		$old = $this->get();
		$new = $this->prepareArray();

		if ( !empty( $new ) && $new != $old )
			update_post_meta( $this->postID, $this->name, $new );
		elseif ( empty($new) && $old )
			delete_post_meta( $this->postID, $this->name, $old );		
	}	
}	

abstract class MultilineRepeaterField extends RepeaterFieldBase {
	protected $name;
	protected $is_sortable;
	protected $allow_empty_main_field;
	
	private $table_id;
	private $add_button_id;

	protected $input_names;
	protected $main_key;
	
	protected function _printJS_4_field() {}	
	protected function _printJS() {}
	
	
	public function __construct(string $name, int $postID, array $input_names, string $suffix, string $tableCaption, bool $is_sortable, bool $allow_empty_main_field) {
        $this->name     = $name;
		
		$this->tableCaption  = $tableCaption;		
		$this->is_sortable    = $is_sortable;
		$this->allow_empty_main_field = $allow_empty_main_field;
		
		$this->table_id       = 'repeatable-fieldset-' . $suffix;
		$this->add_button_id  = 'add-' . $suffix;		

		$this->input_names = $input_names;
		reset($this->input_names);
		$this->main_key = key($this->input_names);
		
		parent::__construct($name, $postID);
	}
	
	
	protected function prepareArray() : array {
		$new = [];
		
		$items  = [];
		foreach ( $this->input_names as $key => $field ) {
			$items[$key] = $_POST[$key];
		}

		$count = count( $items[$this->main_key] );
		
		for ( $i = 0; $i < $count; $i++ ) {
			if ( $this->allow_empty_main_field || $items[$this->main_key][$i] != '' ) {
				
				$new_item = [];
				
				foreach ( $this->input_names as $key => $field  ) {
					$sanitized_value = \AT_Lib\sanitizeValue( $items[$key][$i], $field['type'] );
					
					
					if ( !$this->allow_empty_main_field && $key === $this->main_key ) {
						if (empty($sanitized_value)) {
							break;
						}
					}
					$new_item[$key] = $sanitized_value;
				}
				
				$is_empty = true;
				foreach($new_item as $e) {
					if (!empty($e)) {
						$is_empty = false;
						break;
					}
				}
				
				if ( !$is_empty ) {
					foreach($new_item as $k => $e) {
						$new[$i][$k] = $e;
					}
				}
			}
		}
		return $new;
	}
	
	
	private function renderItem($item, int $i) {
		echo '<tr' . ($i === 0 ? ' class="empty-row screen-reader-text"' : '') . '>';
		if ($this->is_sortable) {
			echo '<td style="cursor: move;"><span class="dashicons dashicons-move"></td>';
		}
		
		$this->renderItemContent($item, $i);
		
		echo '<td><a class="button remove-row" href="#' . ($i === 0 ? '' : '1') . '"><span class="dashicons-before dashicons-trash" style="vertical-align: middle;"></span></a></td>';

		echo '</tr>';
	}

	
	protected function renderItemContent($item, int $i) {
		foreach( $this->input_names as $key => $field) {
			$id  = '';
			$val = '';
			
			if ($i !== 0) {
				$val = $item[ $key ];
			}
			if ( $this->main_key === $key ) {
				$id = $this->main_key . $i;
			}

			echo '<td>';
			if (isset( $field['render']) ) {
				$obj  = $field['render'][0];
				$func = $field['render'][1];
				$obj->$func($key, $val, $id);
			} else {
				$this->renderText($key, $val, $id);
			}
			echo '</td>';
		}
	}
	
	
	public function renderText(string $key, $val, string $id) { ?>
		<input type="text" <?php if ($id != '') echo ' id="' . $id . '" '; ?> name="<?php echo $key; ?>[]" value="<?php if ($val != '') echo esc_attr( $val ); ?>" />
<?php	}
	
	
	protected function renderHead() {
		foreach ( $this->input_names as $field ) {
			echo '<th>' . $field['title'] . '</th>';
		}		
	}
	
	
	public function renderInput() {
		$items = $this->get();
		?>
		<table id="<?php echo $this->table_id; ?>" width="100%">
			<?php if ( !empty($this->tableCaption) ) : ?>
			<caption><?php echo $this->tableCaption; ?></caption>
			<?php endif; ?>
			<thead>
				<tr>
				<?php 
					if ($this->is_sortable) {
						echo '<th style="min-width:30px;"></th>';
					}
					$this->renderHead();

				?>
					<th></th>
				</tr>
			</thead>
			<tbody>
			<?php
				if ( $items ) {
					$i = 1;
					foreach ( $items as $item ) {
						$this->renderItem($item, $i);
						$i++;
					}
				} 
				$this->renderItem('', 0);
			?>
			</tbody>
		</table>
		<p><a id="<?php echo $this->add_button_id; ?>" class="button" href="#">Add New</a></p>
<?php
		$this->printJS();
	}
	
	
	private function printJS() {
?>
    <script type="text/javascript">
    jQuery(document).ready(function( $ ){
		<?php if ($this->is_sortable) : ?>
		$( '#<?php echo $this->table_id; ?> > tbody' ).sortable();
		<?php endif; ?>
		
		$( '#<?php echo $this->add_button_id; ?>' ).on('click', function() {
			
			var num = 0;

			
			$('#<?php echo $this->table_id; ?> > tbody > tr').each(function() {
				var id = $(this).find("input[name='<?php echo $this->main_key; ?>[]']").attr('id');
				
				var tmp = parseInt(id.substr(<?php echo strlen($this->main_key); ?>));
				if (tmp > num) {
					num = tmp;
				}

			});

			num++;
			
            var row = $( '#<?php echo $this->table_id; ?> > tbody > tr.empty-row.screen-reader-text' ).clone(true);
            //var row = $( '.empty-row.screen-reader-text' ).clone(true);
			row.removeClass('empty-row screen-reader-text');
			
			var new_input = row.find('#<?php echo $this->main_key; ?>0');
			new_input.attr('id', '<?php echo $this->main_key; ?>' + num);
			
			<?php $this->_printJS_4_field(); ?>
			
			
            row.insertBefore( '#<?php echo $this->table_id; ?> tbody>tr:last' );
			
            return false;
        });
		
		<?php $this->_printJS(); ?>
		
        $( '.remove-row' ).on('click', function() {
            $(this).parents('tr').remove();
            return false;
        });
    });
	</script>
<?php		
	}	
}