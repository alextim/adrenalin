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
	
	public function get() {
		return TripDates::getDates($this->postID);
		//return get_post_meta($this->postID, $this->name, false);
	}	
	
	public function save() {
		$old = $this->get();
		$new = $this->prepareArray();
		var_dump('old');
		var_dump($old);
		var_dump('new');
		var_dump($new);

		if ( !empty( $new ) && $new != $old )
			update_post_meta( $this->postID, $this->name, $new );
		elseif ( empty($new) && $old )
			delete_post_meta( $this->postID, $this->name, $old );		
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
	
	public function getHtml() : string { return ''; }
}