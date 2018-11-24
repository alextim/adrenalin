<?php
namespace AT_Trip;
/**
 * Metabox for Trip fields.
 *
 * @package ap-trip\inc\admin
 */

/**
 * AdminMetaboxes Class.
 */
//use function AT_Lib\doSave;
 
final class AdminMetaboxes {

	private const NONCE_NAME = 'trip_noncename';
	private const ACTION_NAME = 'at_trip_save_data_process';		
	
	public function __construct() {

		add_action( 'add_meta_boxes_' .  AT_TRIP_POST_TYPE, [ &$this, 'registerMetaboxes'], 10, 2 );
		add_action( 'do_meta_boxes', '\AT_Lib\removeMetaboxes', 10, 2 );
		add_action( 'save_post',     [ &$this, 'saveMetaData' ] );
		
	}
	
	public function saveMetaData( $post_id ) {
		if (!\AT_Lib\checkBeforeSave( $post_id, AT_TRIP_POST_TYPE, self::NONCE_NAME, self::ACTION_NAME )) {
			return;
		}
		$this->doSave( $post_id );
	}	
	
	public function registerMetaboxes() {
		add_meta_box( 'trip_meta_box1', 'Trip Info',         [ &$this, 'render_info_callback' ],              AT_TRIP_POST_TYPE, 'normal', 'high' );

		add_meta_box( 'trip_meta_box2`', 'Dates',           [ &$this, 'render_dates_callback' ],             AT_TRIP_POST_TYPE, 'normal', 'default');

		add_meta_box( 'trip_meta_box3', 'Trip Registration', [ &$this, 'render_registration_form_callback' ], AT_TRIP_POST_TYPE, 'normal', 'default' );
		add_meta_box( 'trip_meta_box4', 'Trip Price',        [ &$this, 'render_price_callback' ],             AT_TRIP_POST_TYPE, 'normal', 'default' );
		add_meta_box( 'trip_meta_box5', 'Trip Tabs',         [ &$this, 'render_tabs_callback' ],              AT_TRIP_POST_TYPE, 'normal', 'default' );

		add_meta_box( 'trip_meta_box6', 'RelatedTrips',      [ &$this, 'render_related_trips_callback' ],     AT_TRIP_POST_TYPE, 'normal', 'default' );
	}
	
	
	public function render_related_trips_callback( $post ) {
		$helper = new TripData( $post ); ?>
		<table>
			<tr>
				<?php $helper->getRelatedTripsField()->renderInput(); ?>
			</tr>
		</table>
<?php		
	}
	
	
	public function render_dates_callback( $post ) {
		$helper = new TripData( $post );
		
		$trip_show_dates 	   = $helper->get_show_dates();

		$trip_duration_days    = $helper->get_duration_days();
		$trip_duration_nights  = $helper->get_duration_nights();

?>
		<table>
			<tr>
				<td><label for="trip_duration_days">Продолжительность</label></td>
				<td>
					<input type="number" name="trip_duration_days"   value="<?php echo $trip_duration_days; ?>"   min="0" step="1">Дней&nbsp;
					<input type="number" name="trip_duration_nights" value="<?php echo $trip_duration_nights; ?>" min="0" step="1">Ночей
				</td>
			</tr>
			
			<tr>
				<td><label for="trip_show_dates">Показывать даты</label></td>
				<td><input type="checkbox" name="trip_show_dates" id="trip-show-dates" value="yes" <?php echo $trip_show_dates ? 'checked' : ''; ?>></td>
			</tr>
		</table>
		<br/>
		<div class="trip-date-range-row" style="display:<?php echo ( 1 === $trip_show_dates ) ? 'table-row' : 'none'; ?>">
			<?php $helper->getDateRangeField()->renderInput(); ?>
		</div>
<?php }

	public function render_info_callback( $post ) {
		wp_nonce_field( self::ACTION_NAME, self::NONCE_NAME );
		
		$helper = new TripData( $post );
		
		$trip_sticky 	           = $helper->get_sticky();
		
		$trip_highest_point 	   = $helper->get_highest_point();
		$trip_technical_difficulty = $helper->get_technical_difficulty();
		$trip_fitness_level        = $helper->get_fitness_level();
		$trip_group_size		   = $helper->get_group_size();?>
		
		<table>
			<tr>
				<td><label for="trip_sticky">Sticky</label></td>
				<td><input type="checkbox" name="trip_sticky" value="yes" <?php echo $trip_sticky ? 'checked' : ''; ?>></td>
			</tr>
		
			<tr>
				<td><label for="trip_highest_point">Высшая точка</label></td>
				<td><input type="number" name="trip_highest_point" value="<?php echo $trip_highest_point; ?>" min="0" max="8848">м</td>
			</tr>
			
			<tr>
				<td><label for="trip_technical_difficulty">Техническая сложность</label></td>
				<td>
					<select name="trip_technical_difficulty">
						<option value="0" <?php echo selected( 0, $trip_technical_difficulty ); ?>>--</option> 
						<option value="1" <?php echo selected( 1, $trip_technical_difficulty ); ?>>Low</option> 
						<option value="2" <?php echo selected( 2, $trip_technical_difficulty ); ?>>Medium</option> 
						<option value="3" <?php echo selected( 3, $trip_technical_difficulty ); ?>>High</option> 
						<option value="4" <?php echo selected( 4, $trip_technical_difficulty ); ?>>Very high</option> 
					</select>
				</td>
			</tr>
			
			<tr>
				<td><label for="trip_fitness_level">Физическая подготовка</label></td>
				<td>
					<select style="width: 100px" name="trip_fitness_level">
						<option value="0" <?php echo selected( 0, $trip_fitness_level ); ?>>--</option> 
						<option value="1" <?php echo selected( 1, $trip_fitness_level ); ?>>Low</option> 
						<option value="2" <?php echo selected( 2, $trip_fitness_level ); ?>>Medium</option> 
						<option value="3" <?php echo selected( 3, $trip_fitness_level ); ?>>High</option> 
						<option value="4" <?php echo selected( 4, $trip_fitness_level ); ?>>Very high</option> 
					</select>
				</td>
			</tr>
			
			<tr>
				<td><label for="trip_group_size">Размер группы</label></td>
				<td><input type="number" name="trip_group_size" value="<?php echo $trip_group_size; ?>" min="0" max="100"></td>
			</tr>
		</table>
		<?php
	}
	
	function render_registration_form_callback( $post ) {
		$helper = new TripData( $post );
		$trip_registration_enabled  = $helper->get_registration_enabled();
		$trip_registration_end_date	= $helper->get_registration_end_date();
		$trip_registration_form	    = $helper->get_registration_form();
		?>
		
		<table>
			<tr>
				<td><label for="trip_registration_enabled">Регистрация разрешена</label></td>
				<td><input type="checkbox" name="trip_registration_enabled" id="trip-registration-enabled" value="yes" <?php echo $trip_registration_enabled ? 'checked' : ''; ?>></td>
			</tr>			
			<tr class="trip-registration-row" style="display:<?php echo ( 1 === $trip_registration_enabled ) ? 'table-row' : 'none'; ?>">
				<td><label for="trip_registration_end_date">Дата окончания регистрации</label></td>
				<td><input type="text" name="trip_registration_end_date" id="trip-registration-end-date" value="<?php echo $trip_registration_end_date; ?>"></td>
			</tr>
			<tr class="trip-registration-row" style="display:<?php echo ( 1 === $trip_registration_enabled ) ? 'table-row' : 'none'; ?>">
				<td><label for="trip_registration_form">Ссылка</label></td>
				<td><input size="80" maxlength="128" type="url" name="trip_registration_form" value="<?php echo $trip_registration_form; ?>"><br/><i>Допускаются ссылки только на Google Forms</i></td>
			</tr>

		</table>
		<?php
	}
	
	function render_price_callback( $post ) {
	
		$helper = new TripData( $post );

		$trip_price = $helper->get_price();
		$trip_currency = $helper->get_currency();
		
		$currency_list = \AT_Lib\getCurrencyList();
		$currency_args = [
			'id'		=> 'trip_currency',
			'class'		=> 'trip_currency',
			'name'		=> 'trip_currency',
			'selected'	=> $trip_currency,
			'option'	=> 'Select Currency',
			'options'	=> $currency_list,
		];
		
		$trip_sale_price  = $helper->get_sale_price();
		$trip_enable_sale = $helper->get_enable_sale();
		
		$trip_show_price_list = $helper->get_show_price_list();
		?>
		
		<table>
			<tr>
				<td><label for="trip_price">Цена</label></td>
				<td><input type="number" name="trip_price" id="trip_price" value="<?php echo $trip_price; ?>" min="0" max="1000000"/></td>
			</tr>
			<tr>
				<td><label for="trip_currency">Валюта</label></td>
				<td>
					<?php echo \AT_Lib\getDropdownCurrencyList( $currency_args ); ?>
				</td>
			</tr>

			<tr>
				<td><label for="trip_enable_sale">Разрешить распродажу</label></td>
				<td><input type="checkbox" name="trip_enable_sale" id="trip-enable-sale" value="yes" <?php echo $trip_enable_sale ? 'checked' : ''; ?> /></td>
			</tr>
			
			<tr class="trip-sale-price-row" style="display:<?php echo ( 1 === $trip_enable_sale ) ? 'table-row' : 'none'; ?>">
				<td><label for="trip_sale_price">Цена распродажи</label></td>
				<td><input type="number" name="trip_sale_price" value="<?php echo $trip_sale_price; ?>" min="0" max="1000000"/></td>
			</tr>
			
			<tr>
				<td><label for="trip_show_price_list">Показывать прайс-лист</label></td>
				<td><input type="checkbox" name="trip_show_price_list" id="trip-show-price-list" value="yes" <?php echo $trip_show_price_list ? 'checked' : ''; ?>></td>
			</tr>
			
		</table>
		<br/>
		<div class="trip-price-list-row" style="display:<?php echo ( 1 === $trip_show_price_list ) ? 'table-row' : 'none'; ?>">
			<?php $helper->getPriceListField()->renderInput(); ?>
		</div>		
		<?php
	}
	
	
	function render_tabs_callback( $post ) {

		$helper = new TripData( $post );

		$trip_outline_title = $helper->get_outline_title();
		$trip_outline 	    = $helper->get_outline();
	
		$trip_include 	= $helper->get_include();
		$trip_exclude 	= $helper->get_exclude();
		
		$trip_price_details = $helper->get_price_details();
		
		$trip_equipment = $helper->get_equipment();
		
		$trip_additional_info = $helper->get_additional_info();

		$trip_gallery = $helper->get_gallery();
		?>
		
		<div class="tabs">
			<input type="radio" name="tabs" id="tabone" checked="checked">
			<label for="tabone">Описание</label>
			<div class="tab">
				<?php wp_editor( $post->post_content, 'at_trip_editor' ); ?>
			</div>

			<input type="radio" name="tabs" id="tabtwo">
			<label for="tabtwo">Программа по дням</label>
			<div class="tab">
				<div>
					<label for="trip_outline_title">Подзаголовок</label>
					<input type="text" name="trip_outline_title" id="trip_outline_title" value="<?php echo $trip_outline_title; ?>" />
				</div>
				<div>
				<label for="trip_outline">Краткое описание</label>
					<?php wp_editor( $trip_outline, 'trip_outline' ); ?>
				</div>
				
				<div>
					<?php $helper->getOutlineDaysField()->renderInput(); ?>
				</div>
			</div>
			
			<input type="radio" name="tabs" id="tabthree">
			<label for="tabthree">Услуги</label>
			<div class="tab">
				<div>
					<label for="trip_include">Включено</label>
					<?php wp_editor( $trip_include, 'trip_include' ); ?>
				</div>
			
				<div>
					<label for="trip_exclude">Не включено</label>
					<?php wp_editor( $trip_exclude, 'trip_exclude' ); ?>
				</div>
			</div>
			
			<input type="radio" name="tabs" id="tabfour">
			<label for="tabfour">Стоимость</label>
			<div class="tab">
				<?php wp_editor( $trip_price_details, 'trip_price_details' ); ?>
			</div>

			<input type="radio" name="tabs" id="tabfive">
			<label for="tabfive">Снаряжение</label>
			<div class="tab">
				<?php wp_editor( $trip_equipment, 'trip_equipment' ); ?>
			</div>
			
			<input type="radio" name="tabs" id="tabsix">
			<label for="tabsix">Доп.информация</label>
			<div class="tab">
				<?php wp_editor( $trip_additional_info, 'trip_additional_info', ['tinymce' => 0, 'textarea_rows' => 5] ); ?>
			</div>

			<input type="radio" name="tabs" id="tabseven">
			<label for="tabseven">Галерея</label>
			<div class="tab">
				<?php wp_editor( $trip_gallery, 'trip_gallery' ); ?>
			</div>
		</div>
		<?php
	}	
	
	protected function doSave( $post_id ) {
		\AT_Lib\save_fields_a( $post_id, [
			['trip_sticky',   'bool'],		
			
			['trip_show_dates',      'bool'],		
			['trip_duration_days',   'int'],
			['trip_duration_nights', 'int'],
			
			['trip_price',		     'int'],
			['trip_currency',        ''],
			['trip_enable_sale',     'bool'],		
			['trip_sale_price',	     'int'],
			['trip_show_price_list', 'bool'],
			
			['trip_outline_title',    ''],
			['trip_outline',          'html'],
			
			['trip_include',    'html'],
			['trip_exclude',	'html'],
			['trip_price_details',	'html'],
			['trip_equipment',		'html'],
			['trip_additional_info','html'],
			['trip_gallery','html'],
			
			['trip_highest_point',		   'int'],
			['trip_technical_difficulty',  'int'],
			['trip_fitness_level',		   'int'],
			['trip_group_size',		       'int'],
			
			['trip_registration_enabled',  'bool'],		
			['trip_registration_end_date', 'time'],
			['trip_registration_form',	   'reg_form_url'],
		] );
		

		$fields = [
			new RelatedTripsField($post_id), 
			new PriceListField($post_id),
			new DateRangeField($post_id, \AT_Lib\getDateFormatMask()), 
			new OutlineDaysField($post_id),
		];
		
		foreach ($fields as $field) {
			$field->save();
		}
			
	
		if ( isset( $_POST['at_trip_editor'] ) ) {
			$new_content = $_POST['at_trip_editor'];
			$old_content = get_post_field( 'post_content', $post_id );
			if ( ! wp_is_post_revision( $post_id ) && $old_content !== $new_content ) {
				$args = [
					'ID' => $post_id,
					'post_content' => $new_content,
				];

				// Unhook this function so it doesn't loop infinitely.
				remove_action( 'save_post', [&$this, 'saveMetaData'] );
				// Update the post, which calls save_post again.
				wp_update_post( $args );
				// Re-hook this function.
				add_action( 'save_post', [&$this, 'saveMetaData'] );
			}
		}
	}
}