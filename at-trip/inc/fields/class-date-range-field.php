<?php
declare(strict_types=1);
namespace AT_Trip;


final class DateRangeField extends MultilineRepeaterField {
	public $durationDays;
	private $date_format_mask;
	
	public function __construct(int $postID, string $date_format_mask) {
		$input_names = [ 
			'startdate' => [ 'type' => 'time', 'title' => 'Дата начала' ],
		];
		
		$this->date_format_mask = $date_format_mask;

		parent::__construct('trip_dates', $postID, $input_names, 'd', 'Список дат путешествий', true, false);
    }
	
	protected function renderItemContent($item, int $i) {
		$val = '';
		
		if ($i !== 0) {
			$val   = $item[ $this->main_key ];
			if ($val != '') {
				$val = date( $this->date_format_mask, (int)$val ); 
			}
		}
?>
		<td>
		<input <?php if ($i !== 0) echo 'class="' . $this->main_key . '"';?> id="<?php echo $this->main_key . $i; ?>" type="text" name="<?php echo $this->main_key; ?>[]" value="<?php if ($val != '') echo $val; ?>" />
		</td>
<?php		
	}	
	
	
	protected function _printJS_4_field() {?>
		new_input.datepicker();	
		new_input.addClass('<?php echo $this->main_key; ?>');
		
<?php }


	protected function _printJS() {?>
		$('input.<?php echo $this->main_key; ?>').datepicker({ minDate: new Date()});
<?php }


	public function renderDisplay() {
		$values = $this->get();

		if (!$values) {
			return;
		}
		if (!is_array($values)) {
			return;
		}
		
		
		$i = 0;

			
		foreach ( $values as $field ) {
			$start_date = $field['startdate'];
			if ( !empty($start_date) ) {
				$start_date = date( $this->date_format_mask, (int)$start_date );
				$s = $this->format_date_duration_s($start_date, $this->durationDays);
				if ( !empty($s) ) {
					if ( 0 === $i ) {
						print_info_item( get_fa('calendar'), $s );
						$i++;
						
					} else {
						
						print_info_item( '<i class="fa">&nbsp;&nbsp;&nbsp;&nbsp;</i>', $s );
					}
				}
			}
		}		
	}
	
	private function format_date_duration_s(string $start_date, int $duration) {
		$s = '';
		if ( !empty($start_date) ) {
			if (0 == $duration) {
				$duration = 1;
			}
			
			$s = $start_date;
			if ( $duration > 1 ) {
				$end_date = date($this->date_format_mask, strtotime($start_date . ' + ' . ($duration - 1) . ' days'));
				$s .= '&nbsp;-&nbsp;' . $end_date;
			}
		}
		return $s;		
	}
		
}