<?php
declare(strict_types=1);
namespace AT_Trip;
/***********************
 * Надо добавить в CSS темы для работы плейсхолдера даты
 *

#start-date, #finish-date {
  text-align: right;
}

#start-date:before, #finish-date:before {
  content: attr(placeholder) !important;
  margin-right: 0.5em;
}

*/

final class SearchForm {
	private $action_url;
	private $showposts;
	private $nonce;
	
	
	public function __construct(string $action_url, int $showposts, string $nonce) {
		$this->action_url = $action_url;
		$this->showposts  = $showposts;
		$this->nonce      = $nonce;
	}
/*	
	private function printDateInput(string $name, string $placeholder) {
		$value = '';
		if (isset($_GET[$name]) && !empty($_GET[$name])) {
			$value = $_GET[$name];
		}
		?>
		<input id="<?php echo $name;?>" placeholder="<?php echo $placeholder; ?>" type="date" name="<?php echo $name;?>" value="<?php echo $value; ?>">
		<?php
	}
*/	
	private function my_sort(array $choices, string $meta_key) : array {
		// WORDPRESS не сортирует таксономии по custom meta
		$tmp = [];
		foreach($choices as $term_id => $value) {
			$sort_order = get_term_meta($term_id, $meta_key, true);
			$tmp[] = [$sort_order, $term_id, $value];
		}
	}
	private function printTaxCombo(string $tax, string $all_title, string $order_meta_key = '') {
		$value = '';
		if (isset($_GET[$tax]) && !empty($_GET[$tax])) {
			$value = $_GET[$tax];
		}
		
		$args = [
			'taxonomy' => $tax,
		];


		$choices = \AT_Lib\getTaxChoices($args, $order_meta_key);
		array_splice($choices, 0, 0, ['' => $all_title]);
		\AT_Lib\printCombobox($tax, $choices, $value);		
	}
	
	public function render() {

	?>
<form  method="get" action="<?php echo $this->action_url;?>">
	<div class="container">
		<div class="row">
			<div class="column column-3"><?php  $this->printTaxCombo('destination', 'Все направления'); ?></div>
			<div class="column column-3"><?php  $this->printTaxCombo('activity', 'Все виды'); ?></div>
			<div class="column column-3"><?php  $this->printTaxCombo('season', 'Все сезоны', 'season_order'); ?></div>
			<div class="column column-3"><input type="submit"/></div>
		</div>
	</div>
</form>
	<?php	
	}
	
	private function prepareTaxArgs() : array {
		$list = [];
		$item = [];
		
		$taxonomies = ['destination', 'activity', 'season'];
		foreach( $taxonomies as $key ) {
			if (isset($_GET[$key])) {

				$value = $_GET[$key];
				if ( !empty($value) ) {
					$item['taxonomy'] = htmlspecialchars($key);
					$item['terms'] = htmlspecialchars($value);
					$item['field'] = 'slug';
					$list[] = $item;
				}
			}
		}
		$tax_args = array_merge(['relation' => 'AND'], $list);
		
		return $tax_args;
	}
/*	
	private function getDateArgs() : array {
		$start_date = '';
		$finish_date = '';
	
		$key = 'start-date';
		if (isset($_GET[$key])) {
			$value = $_GET[$key];
			if ( !empty($value) ) {
				$start_date = $value;
			}
		}
		
		$key = 'finish-date';
		if (isset($_GET[$key])) {
			$value = $_GET[$key];
			if ( !empty($value) ) {
				$finish_date = $value;
			}
		}
		
		$start_date = strtotime($start_date);
		$finish_date = strtotime($finish_date);
			
		if (empty($start_date) && empty($end_date)) {
			return [];
		}

		
		$meta_query = [];
		if (!empty($start_date) && !empty($finish_date)) {
			$meta_query['relation'] = 'AND';
		}
		

		if (!empty($start_date)) {
			$meta_query[] = [
			    [
					'key' => 'trip_dates',
					'value' => serialize(['startdate' => $start_date]),
					'compare' => '>=',
					'type' => 'NUMERIC',
				]			
			];
		}
		
		if (!empty($finish_date)) {
			$meta_query[] = [
			    [
					'key' => 'trip_dates%startdate',
					'value' => $finish_date,
					'compare' => '<=',
					'type' => 'NUMERIC',
				]			
			];
		}		

		return $meta_query;
		
	}
	*/
	public function getQuery() : \WP_Query {
		$tax_args = $this->prepareTaxArgs();
		
		$args = [ 
			'post_type' => AT_TRIP_POST_TYPE,
			'showposts' => $this->showposts,
			'paged' => (get_query_var('paged')) ? get_query_var('paged') : 1,
			'tax_query' => $tax_args,
			'post_status' => 'publish',
		];
/*		
		$dates = $this->getDateArgs();
		
		if (!empty($dates)) {
			$args['meta_query'] = $dates;
		}
*/	
		$query = new \WP_Query( $args );
		//echo "Last SQL-Query: {$query->request}";
		
		return $query;
	}
	

}