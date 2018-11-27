<?php
declare(strict_types=1);
namespace AT_Gear;


final class TaxonomyAdrenalinUrlField extends \AT_Lib\TaxonomyMetaUrlField {
	private const DOMAIN_NAME = 'adrenalin.od.ua';
	
	
	public function __construct() {
		$placeholder = 'https://' . self::DOMAIN_NAME;
		$pattern = 'https?://' . self::DOMAIN_NAME . '/.+.';
		$title = 'The URL must be in a ' . self::DOMAIN_NAME . ' domain with at least one symbol after /';
		
		parent::__construct('gear_type_url', 'Catalogue/Product Url', 'Enter Url for product or catalogue page on ' . self::DOMAIN_NAME, $placeholder, $pattern, $title);
	}
}


final class GearTypeAdminMetaboxes extends \AT_Lib\TaxonomyAdminMetaboxesBase {
	public function __construct() {
		parent::__construct('gear_type');
		$this->addField( new \AT_Lib\TaxonomyMetaPositiveNumberField('gear_type_order', 'Sort Order', 'Numeric sort for Gear List output', true, true) );
		$this->addField( new TaxonomyAdrenalinUrlField() );
		$this->init();
	}
}

new GearTypeAdminMetaboxes();