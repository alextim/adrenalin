<?php
declare(strict_types=1);
namespace AT_Lib;


function getPostsChoices ( int $limit_post_number, string $post_type = '' ) : array {
	$qargs = ['posts_per_page' => $limit_post_number];
	if ( !empty($post_type) ) {
		$qargs['post_type'] = $post_type;
	}
	$posts = get_posts($qargs);
	
	foreach ($posts as $post) {
		$choices[$post->ID] = $post->post_title;
	}	
	return $choices;		
} 


function printCombobox(string $name, array $choices, $value, string $style = '') {
?>	
	<select name="<?php echo $name;?>" style="<?php echo $style;?>">
	<?php foreach($choices as $key => $item) : ?>
		<option value="<?php echo $key; ?>"<?php echo selected( $key, $value ); ?>><?php echo $item; ?></option> 
	<?php endforeach; ?>
	</select>
<?php	
}

function getDropdownCurrencyList( array $args = [] ) : string {

	$currency_list = getCurrencyList();
	if (!$currency_list ) {
		return '';
	}

	$default = [
		'id'		=> '',
		'class'		=> '',
		'name'		=> '',
		'option'	=> '',
		'options'	=> '',
		'selected'	=> '',
		];

	$args = array_merge( $default, $args );

	$dropdown = ('' === $args['option']) ? '' : '<option value="" >' . $args['option'] . '</option>';

	foreach ( $currency_list as $key => $currency ) {
		$dropdown .= '<option value="' . $key . '" ' . selected( $args['selected'], $key, false ) . '  >' . $currency . ' (' . getCurrencySymbol( $key ) . ')</option>';
	}
	return '<select name="' . $args['name'] . '" id="' . $args['id'] . '" class="' . $args['class'] . '" >' . $dropdown  . '</select>';
}