<?php
declare(strict_types=1);
namespace AT_Lib;


class TaxonomyMetaTextField extends TaxonomyMetaFieldBase {
	protected function renderInput(string $name, $val) {?>
		<input type="text" name="<?php echo $name; ?>" value="<?php echo $val; ?>">
<?php
	}
}


class TaxonomyMetaUrlField extends TaxonomyMetaFieldBase {
	private $placeholder;
	private $pattern;	
	private $title;	
	
	
	public function __construct(string $index, string $caption, string $description = '', string $placeholder = '', string $pattern = '', string $title = '' ) {
		$this->placeholder = $placeholder;
		$this->pattern = $pattern;
		$this->title = $title;

		parent::__construct($index, $caption, $description);
	}

	
	protected function renderInput(string $name, $val) {?>
		<input type="url" placeholder="<?php if (!empty($this->placeholder) ) echo $this->placeholder; ?>" pattern="<?php if (!empty($this->pattern) ) echo $this->pattern; ?>" title="<?php if (!empty($this->title) ) echo $this->title; ?>" name="<?php echo $name; ?>" value="<?php echo $val; ?>">
		<span class="validity"></span>
<?php
	}
}


final class TaxonomyMetaPositiveNumberField extends TaxonomyMetaFieldBase {
	protected function renderInput(string $name, $val) {?>
		<input type="number" min="0" name="<?php echo $name; ?>" value="<?php echo $val; ?>">
<?php
	}
}