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
	
	
	public function getHtml() : string {
		$values = $this->get();
		if (!$values || !is_array($values)) {
			return '';
		}
		
		if (isset($currency)) {
			$currency = \AT_Lib\getCurrencySymbol($currency);
		}		
		ob_start(); ?>
<div class="price-list-wrap">
	<table>
		<thead>
			<tr>
				<th>Количество участников</th>
				<th>Стоимость на одного участника</th>
			</tr>
		</thead>
		<tbody>
		<?php foreach ($values as $item) : $full_price = intval($item['pl_full_price']); $sale_price = 0;?>
			<tr>
				<td><?php echo $item['pl_description']; ?></td>
				<td>
				<?php $enable_sale = $this->enable_sale;
				if ( $full_price > 0 ) :
					if ( $enable_sale ) {
						$sale_price  = intval($item['pl_sale_price']);
						$enable_sale = ($sale_price > 0);
					} ?>
				
					<span class="trip-info-value<?php if ($enable_sale) echo 'old-price';?>"><?php echo $full_price . '&nbsp;' . $this->currency; ?></span>
					
					<?php if ( $enable_sale ) :?>
						<span class="trip-info-value sale-price"><?php echo $sale_price . '&nbsp;' . $this->currency;?></span>
					<?php endif;
				endif; ?>
				</td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
</div>
<?php		
		$s = ob_get_contents();
		ob_end_clean();
		return $s;
	}
}