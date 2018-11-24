<?php 
//declare(strict_types=1);
namespace AT_Gear;

final class GearData extends \AT_Lib\DataHelperBase {
	public function getSortOrder()    : int    { return parent::getInt( 'gear_sort_order' ); }
	public function getProductUrl()   : string { return parent::getUrl( 'gear_product_url' ); }
}