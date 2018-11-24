<?php
declare(strict_types=1);
namespace AT_Trip;


final class PriceListField extends MultilineRepeaterField {
	public $currency;
	public $enable_sale;
	
	public function __construct(int $postID) {
		$input_names = [ 
			'pl_description' => [ 'type' => 'text',   'title' => 'Количество участников' ],
			'pl_full_price'  => [ 'type' => 'absint', 'title' => 'Цена',               'render' => [&$this, 'renderPrice'] ],
			'pl_sale_price'  => [ 'type' => 'absint', 'title' => 'Цена на распродаже', 'render' => [&$this, 'renderPrice'] ],
		];

		parent::__construct('trip_price_list', $postID, $input_names, 'pl', 'Прайс-лист', true, false);
    }

	
	public function renderPrice(string $key, $val, string $id) { ?>
		<input type="number" min="0" name="<?php echo $key; ?>[]" value="<?php if ($val != '') echo esc_attr( $val ); ?>" />
<?php	
	}
	
	
	public function renderDisplay() {
		echo $this->getHtml();
	}
	
	
	public function getHtml() {

		$values = $this->get();

		if (!$values) {
			return '';
		}
		if (!is_array($values)) {
			return '';
		}
		
		if (isset($currency)) {
			$currency = \AT_Lib\getCurrencySymbol($currency);
		}		

		$s = '<div class="price-list-wrap">';
		$s .= '<table>';
		$s .= '<thead><tr>';
		$s .= '<th>Количество участников</th>';
		$s .= '<th>Стоимость на одного участника</th>';
		$s .= '</tr></thead>';
		$s .= '<tbody>';
		
		foreach ($values as $item) {
			$full_price = intval($item['pl_full_price']);
			$sale_price = 0;
			
			$s .= '<tr>';

			$s .= '<td>';
			$s .= $item['pl_description'];
			$s .= '</td>';
			
			$s .= '<td>';
			$enable_sale = $this->enable_sale;
			if ( $full_price > 0 ) {
				if ( $enable_sale ) {
					$sale_price  = intval($item['pl_sale_price']);
					$enable_sale = ($sale_price > 0);
				}
				
				$s .= '<span class="trip-info-value' . ($enable_sale ? ' old-price' : '') . '">' . $full_price;
				$s .= '&nbsp;' . $this->currency;
				$s .= '</span>';
				
				if ( $enable_sale ) {
					$s .= '<span class="trip-info-value sale-price">' . $sale_price;
					$s .= '&nbsp;' . $this->currency;
					$s .= '</span>';
				}
			}
			$s .= '</td>';
			
			
			$s .= '</tr>';
		}

		$s .= '</tbody>';
		$s .= '</table>';
		$s .= '</div>';
		
		return $s;
	}
}