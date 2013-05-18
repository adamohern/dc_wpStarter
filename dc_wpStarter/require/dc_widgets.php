<?php

// widget: dc_image
// Insert an arbitrary link image into a sidebar

class dc_image extends WP_Widget {

	function __construct() {
		parent::WP_Widget( 
						'dc_image', // Base ID
						'dc_image', // Name
						array( 'description' => 'Drop an arbitrary image into a sidebar.' ) );
	}

	function widget($args, $instance) {
		extract($args, EXTR_SKIP);
				if (!empty($instance['css'])){ 
					$css = ' style="'.$instance['css'].'"'; 
				}
				
				c('widget: ' . $instance['title'],1);
				e('<div class="dc_image" id="'.$this->get_field_id('imageURL').'"'.$css.'>');
				
				if ( !empty( $instance['imageURL'] ) && !empty( $instance['linkURL'] ) ) { 
					e('<a href="'.$instance['linkURL'].'"><img src="'.$instance['imageURL'].'"></a>'); 
				};
				
				e('</div>');
				c('/'.$instance['title']);
	
	}
	
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['imageURL'] = $new_instance['imageURL'];
		$instance['linkURL'] = $new_instance['linkURL'];
		$instance['css'] = $new_instance['css'];
		
		return $instance;
	}
	
	function form($instance) {
		$instance = wp_parse_args( (array) $instance, array( 'imageURL' => '', 'linkURL' => '', 'css' => '') );
		$imageURL = $instance['imageURL'];
		$linkURL = $instance['linkURL']; 
		$css = $instance['css'];?>

<p>
	<label for="<?php echo $this->get_field_id('imageURL'); ?>">
		Image URL: <input class="widefat" id="<?php echo $this->get_field_id('imageURL'); ?>" name="<?php echo $this->get_field_name('imageURL'); ?>" type="text" value="<?php echo attribute_escape($imageURL); ?>" />
	</label>
</p>
<p>
	<label for="<?php echo $this->get_field_id('linkURL'); ?>">
		Link URL: <input class="widefat" id="<?php echo $this->get_field_id('LinkURL'); ?>" name="<?php echo $this->get_field_name('linkURL'); ?>" type="text" value="<?php echo attribute_escape($linkURL); ?>" />
	</label>
</p>
<p>
	<label for="<?php echo $this->get_field_id('css'); ?>">
		Inline CSS: <input class="widefat" id="<?php echo $this->get_field_id('css'); ?>" name="<?php echo $this->get_field_name('css'); ?>" type="text" value="<?php echo attribute_escape($css); ?>" />
	</label>
</p>

<?php }

}

register_widget('dc_image');


?>