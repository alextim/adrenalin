<?php
declare(strict_types=1);
namespace AT_Season;


final class SeasonAdminMetaboxes extends \AT_Lib\TaxonomyAdminMetaboxesBase {
	public function __construct() {
		parent::__construct('season');
		
		$this->addField( new \AT_Lib\TaxonomyMetaPositiveNumberField('season_order', 'Sort Order', 'Numeric sort for Seasons', true, true) );
		$this->init();
	}
}

new SeasonAdminMetaboxes();