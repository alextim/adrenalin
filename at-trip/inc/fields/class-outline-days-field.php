<?php
declare(strict_types=1);
namespace AT_Trip;


final class OutlineDaysField extends MultilineRepeaterField {
	public function __construct(int $postID) {
		$input_names = [ 
			'ol_title'        => [ 'type' => 'text',     'title' => 'Заголовок'],
			'ol_img'          => [ 'type' => 'text',     'title' => 'Изображение' ],
			'ol_description'  => [ 'type' => 'textarea', 'title' => 'Описание' ],
		];
	
		parent::__construct('trip_outline_days', $postID, $input_names, 'ol', 'Детализация программы по дням', true, true);
	}
	
	
	protected function renderHead() {
		echo '<th>№</th><th>Описание дня</th>';
	}

	
	protected function renderItemContent($item, int $i) {
		$keys = array_keys($this->input_names);
		
		$key  = $this->main_key;
		$key2 = $keys[1];
		$key3 = $keys[2];
		
		$val       = '';
		
		$image_id  = '';
		$image_src = '';
		
		$val3      = '';
		
		$id = $this->main_key . $i;
		
		if ($i !== 0) {
			$val = $item[ $key ];
			
			$image_id = $item[ $key2 ];
			if ( !empty($image_id )) {
				//$image_src = wp_get_attachment_url( $image_id );
				$image_attr = wp_get_attachment_image_src( $image_id, 'medium');
				if ($image_attr) {
					$image_src = $image_attr[0];
				}
			}

			$val3 = $item[ $key3 ];
		}
?>	
	<td class="ol-day-number" style="background-color: lightgrey; text-align: center; vertical-align: top; padding-top: 5px; cursor: move;">
		День&nbsp;<?php echo $i; ?>
	</td>
	<td>
		<div>
			<input style="width:100%;" id="<?php echo $id; ?>" type="text" name="<?php echo $key; ?>[]" value="<?php if ($val != '') echo esc_attr( $val ); ?>" />
		</div>
		<div>
			<textarea style="width:100%;" cols="40" rows="4" name="<?php echo $key3; ?>[]"><?php if ($val3 != '') echo esc_textarea( $val3 ); ?></textarea>
		</div>
		<div>
			<img src="<?php echo $image_src ?>" style="max-width:100%;" />
			<input type="hidden" name="<?php echo $key2; ?>[]" value="<?php echo $image_id; ?>" />
			<p>
				<a title="<?php esc_attr_e( 'Set image' ) ?>" href="#" class="set-day-image"><?php _e( 'Set image' ) ?></a>
				<a title="<?php esc_attr_e( 'Remove image' ) ?>" href="#" class="remove-day-image" <?php if(empty($image_id)) echo ' style="display:none;"'; ?>><?php _e( 'Remove image' ) ?></a>
			</p>
			
		</div>
		<hr>
	</td>
<?php		
	}
	
	
	protected function _printJS_4_field() {?>
       var td = row.find('.ol-day-number');
       td.text('День\u00A0' + num);
<?php
	}
	
	
	protected function _printJS() {?>

	jQuery(document).ready(function($) {
		$('.remove-day-image').click(function() {
			var div = $(this).parent().parent();
            div.find('img').attr('src', '');
            div.find('input').val('');
			
			$(this).hide();
			
			return false;
		});

		// save the send_to_editor handler function
		var container_div;
		var formfield = null;
		var img_field;
		var input_field;
		
		$('.set-day-image').click(function() {

		container_div = $(this).parent().parent();
			input_field = container_div.find('input');
			img_field = container_div.find('img');

			formfield = input_field.attr('name');
		
			// replace the default send_to_editor handler function with our own
			window.send_to_editor =	function(html) {
				try {
					var img = $(html);	
					
					var imgclass = img.attr('class');
					var imgid    = parseInt(imgclass.replace(/\D/g, ''), 10);
					var imgurl   = img.attr('src');

					input_field.val(imgid);
					img_field.attr('src', imgurl);
					
					container_div.find('.remove-day-image').show();
				} 
				catch(e) {
					//alert(e);
				}
				finally {
					try { 
						tb_remove();
					} catch(e) {
						//alert(e);
					}
					
					// restore the send_to_editor handler function
					window.send_to_editor = window.send_to_editor_default;
				}
			}
			
			tb_show('', 'media-upload.php?type=image&amp;TB_iframe=1');
			
			return false;
		});
	});
<?php	
	}
	
	public function renderDisplay() {
		echo getHtml();
	}
	
	public function getHtml() : string {
		$values = $this->get();

		if (!$values) {
			return '';
		}
		if (!is_array($values)) {
			return '';
		}
		$day_counter = 0; 
		ob_start();
		?>
		
		<div class="outline-days">
		<?php foreach ($values as $day) : ++$day_counter; ?>
			<div class="outline-day-row">
				<h4><span class="outline-day-num">День&nbsp;<?php echo $day_counter; if (!empty($day['ol_title'])) echo '.&nbsp;';?></span><?php if (!empty($day['ol_title'])) echo '<span  class="outline-day-title">' . $day['ol_title'] . '</span>'; ?></h4>
				<?php if (!empty($day['ol_description'])) : ?>
					<p class="outline-day-description"><?php echo $day['ol_description']; ?></p>
				<?php endif; ?>
				<?php if (!empty($day['ol_img'])) :
					$image_attr = wp_get_attachment_image_src( $day['ol_img'], 'medium');
					if ($image_attr) { 
						$alt_text = get_post_meta($day['ol_img'], '_wp_attachment_image_alt', true);
					?>
						<p class="outline-day-img"><img src="<?php echo $image_attr[0]; ?>"<?php if ($alt_text) echo ' alt="' . $alt_text . '"'; ?>/></p>
					<?php } ?>
				<?php endif; ?>
			</div>
		<?php endforeach; ?>
		</div>
<?php   $s = ob_get_contents();
		ob_end_clean();
		return $s;
		
	}
		
}