<?php 
declare(strict_types=1);
namespace AT_Trip;

abstract class Field {
	protected $name;
	protected $postID;
	protected $caption;
	
	public function __construct(string $name, int $postID) {
		$this->name   = $name;
		$this->postID = $postID;
	}
	
	public abstract function get();		
	public abstract function renderInput();
	public abstract function getHtml() : string;
	
	public function getName() : string { return $this->name; }

	abstract public function save();
}


abstract class TextField extends Field {
	public function save() {
		if (isset( $_POST[$this->name] )) {
			update_post_meta( $this->postID, $this->name, $this->sanitize($_POST[$this->name]) );
		}
	}	

	protected function sanitize($val) {	
		return sanitizeVal($val, 'text'); 
	}
	
	protected function esc($val) {	
		return esc_attr($val); 
	}
	
	public function renderInput() {
		$val = $this->get();
?>
		<td><label for="<?php echo $this->name; ?>"><?php echo $this->caption; ?></label></td>
		<td><input type="text" name="<?php echo $this->name; ?>" value="<?php echo $this->esc($val); ?>"/></td>
	
<?php	
	}
}	