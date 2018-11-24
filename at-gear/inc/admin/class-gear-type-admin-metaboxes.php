<?php
declare(strict_types=1);
namespace AT_Gear;

final class TaxonomyAdrenalinUrlField extends TaxonomyMetaUrlField {
	private const DOMAIN_NAME = 'adrenalin.od.ua';
	
	public function __construct() {
		$placeholder = 'https://' . self::DOMAIN_NAME;
		$pattern = 'https?://' . self::DOMAIN_NAME . '/.+.';
		$title = 'The URL must be in a ' . self::DOMAIN_NAME . ' domain with at least one symbol after /';
		
		parent::__construct('url', 'Catalogue/Product Url', $placeholder, $pattern, $title);
	}
}


final class GearTypeAdminMetaboxes {
	public function __construct() {
		$helper = new TaxonomyAdminMetaboxes('gear_type', 'gear_type_meta');
		
		$helper->addField( new TaxonomyMetaPositiveNumberField('order', 'Sort Order', true, true) );
		$helper->addField( new TaxonomyAdrenalinUrlField() );
		$helper->init();
	}
}

new GearTypeAdminMetaboxes();





