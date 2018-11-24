<?php 
declare(strict_types=1);
namespace AT_Trip;


final class RelatedTripsField extends RepeaterFieldBase {
	private const DISPLAY_TRIP_LIMIT = 3;
	private const SELECT_TRIP_LIMIT = 200;

	
	public function __construct(int $postID) {
		parent::__construct('trip_related_trips', $postID);
		$this->tableCaption = 'Список связанных мероприятий';
		$this->caption = 'Мероприятие';
	}
	
	
	protected function prepareArray() : array {
		$new = [];
		
		for ( $i = 0; $i < self::DISPLAY_TRIP_LIMIT; $i++ ) {
			$new[]  = '';
		}
		
		$key = $this->name;
		
		$items = $_POST[$key];


		$count = min( count( $items ), self::DISPLAY_TRIP_LIMIT );
		
		for ( $i = 0; $i < $count; $i++ ) {
			$new[$i]  = \AT_Lib\sanitizeValue( $items[$i], 'text' );
		}
		return $new;
	}	

	
	public function renderInput() {
		$items = [];
		for( $i = 0; $i < self::DISPLAY_TRIP_LIMIT; $i++) {
			$items[] = '';
		}
		
		$values = $this->get();

		if ($values) {
			if (is_array($values)) {
				$i = 0;
				foreach ( $values as $val ) {
					$items[$i] = $val;
					$i++;
					if ($i >= self::DISPLAY_TRIP_LIMIT) {
						break;
					}
				}
			}
		}
		$choices = \AT_Lib\getPostsChoices(self::SELECT_TRIP_LIMIT, AT_TRIP_POST_TYPE);
		$choices[0] = '&mdash; Select &mdash;';
		
		
?>
		<table width="100%">
			<?php if ( !empty($this->tableCaption) ) : ?>
			<caption><?php echo $this->tableCaption; ?></caption>
			<?php endif; ?>
			<thead>
				<tr>
				<?php 
					$this->renderHead();
				?>
				</tr>
			</thead>
			<tbody>
				<?php foreach ( $items as $val ) : ?>
					<tr>
						<td><?php \AT_Lib\printCombobox($this->name . '[]', $choices, $val, ''); ?></td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	
<?php	
	}
		
		
	protected function renderHead() {
		echo '<th>' . $this->caption  . '</th>';
	}
	
	public function renderDisplay() {
		$values = $this->get();

		if (!$values) {
			return;
		}
		if (!is_array($values)) {
			return;
		}

		$i = 0;
		$post_ids = [];
		foreach ( $values as $val ) {
			if (!empty($val)) {
				$post_ids[] = $val;
			}
			$i++;
			if ($i >= self::DISPLAY_TRIP_LIMIT) {
				break;
			}
		}
		
		$qargs = ['numberposts' => self::DISPLAY_TRIP_LIMIT];
		$qargs['post_type'] = AT_TRIP_POST_TYPE;
		$qargs['post__in'] = $post_ids;
		$posts = get_posts($qargs);
		
		if ($posts) {
			foreach($posts as $post) { ?>
			<p>
				<a href="<?php echo get_permalink( $post );?>" title="<?php echo esc_attr( $post->post_title );?>">
					<?php if ( has_post_thumbnail($post) ) {
						echo get_the_post_thumbnail( $post, 'thumbnail'); // array( 'class' => 'alignleft' ) );
					} ?>
					<p><?php echo $post->post_title; ?></p>
				</a>
			</p>
<?php		}
		}
		wp_reset_postdata();		
	}
	
}	