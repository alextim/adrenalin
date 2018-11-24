<?php 
declare(strict_types=1);
namespace AT_Trip;

/**
 * Tab class
 *
 * @package AT Trip
 */
class Tab {
	public $id;
	private $title;
	private $content;
	private $label_class;
	
	public function __construct(string $id, string $title, string $content, string $label_class = '') {
		$this->id = $id;
		$this->title = $title;
		$this->content = $content;
		$this->label_class = $label_class;		
	}
	
	public function render(bool $is_active) {
		if ( empty( $this->content ) ) {
			return;
		}
		
		$label_class = $this->label_class;
		if(!empty($label_class)) {
			$label_class = 'class="' . $label_class . '" ';
		}
		?>
		<input type="radio" name="tabs" id="<?php echo $this->id; ?>"<?php if ($is_active) echo  ' checked="checked"'; ?>>
		<label <?php echo $label_class;?>for="<?php echo $this->id; ?>"><?php echo $this->title; ?></label>
		<div class="tab">
			<?php echo apply_filters('the_content', $this->content); ?>
		</div>
		<?php 
	}
}

final class ServiceTab extends Tab {
		public function __construct(string $id, string $title, string $trip_include, string $trip_exclude) {
			$s = '';

			if ( !empty($trip_include)) {
				$s .= '<div class="trip-service-tab-wrap">';
					$s .= '<div class="trip-service-tab-subtitle"><span class="fa fa-plus"></span><span>Включено</span></div>';
					$s .= $trip_include;
				$s .= '</div>';
			}
			if ( !empty($trip_exclude)) {
				$s .= '<div class="trip-service-tab-wrap">';
					$s .= '<div class="trip-service-tab-subtitle"><span class="fa fa-minus"></span><span>Не включено</span></div>';
					$s .= $trip_exclude;
				$s .= '</div>';
			}			
			
			parent::__construct($id, $title, $s);
	}
}

final class OutlineTab extends Tab {
	public function __construct(string $id, string $title, string $outline_title, string $outline, string $outline_days) {
		$s = '';
		if ( !empty($outline_title)) {
			$s .= '<h4>' . $outline_title . '</h4>';
		}

		if ( !empty($outline)) {
			$s .= '<div>' . $outline . '</div>';
		}

		if ( !empty($outline_days)) {
			$s .= $outline_days;
		}			

		parent::__construct($id, $title, $s);
	}
}