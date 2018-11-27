<?php
declare(strict_types=1);
namespace AT_Lib;


function getTermsSortedByMeta( array $args, string $order_meta_key, $user_func = null) : array {
	$terms = get_terms($args);
	
	if ( empty( $terms ) || is_wp_error( $terms ) ) {
		return [];
	}
	
	if (empty($order_meta_key)) {
		return $terms;
	}
	
	// WORDPRESS не сортирует таксономии по custom meta
	$tmp = [];
	
	foreach ( $terms as $term ) {
		$sort_order = get_term_meta($term->term_id, $order_meta_key, true);
		if (!empty($user_func)) {
			$term = $user_func($term);
		}
		$tmp[] = [$sort_order, $term];
	}

	usort( $tmp, function($a, $b) {
		if ($a[0]==$b[0]) return 0;
		return $a[0]>$b[0] ? 1 : -1;
	});
	
	$ordered_terms = [];
	foreach ( $tmp as $item ) {
		$ordered_terms[] = $item[1];;
	}
	

	return $ordered_terms;
}


function getPostsChoices ( int $limit_post_number, string $post_type = '' ) : array {
	$qargs = ['posts_per_page' => $limit_post_number];
	if ( !empty($post_type) ) {
		$qargs['post_type'] = $post_type;
	}
	$posts = get_posts($qargs);
	$choices = [];
	foreach ($posts as $post) {
		$choices[$post->ID] = $post->post_title;
	}	
	return $choices;		
} 

function getTaxChoices ( array $args, string $order_meta_key ) : array {
	$choices = [];
	$terms = getTermsSortedByMeta($args, $order_meta_key);
	if ( !empty( $terms )  ){
		foreach ($terms as $term) {
			$choices[$term->slug] = $term->name;
		}	
	}
	return $choices;		
} 


function printCombobox(string $name, array $choices, $value, string $style = '') {
?>	
	<select name="<?php echo $name;?>" style="<?php echo $style;?>">
	<?php foreach($choices as $key => $item) : ?>
		<option value="<?php echo $key; ?>"<?php if ('' . $value !== '') echo selected( $key, $value ); ?>><?php echo $item; ?></option> 
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