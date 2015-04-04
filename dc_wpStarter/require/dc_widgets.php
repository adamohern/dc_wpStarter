<?php

// widget: dc_image
// Insert an arbitrary link image into a sidebar

class dc_text extends WP_Widget {

	function __construct() {
		parent::WP_Widget( 
						'dc_text', // Base ID
						'dc text', // Name
						array( 'description' => 'Drop text into a sidebar. (Added by the destructive-creative theme.)' ) );
	}

	function widget($args, $instance) {
		extract($args, EXTR_SKIP);
				if (!empty($instance['css'])){ 
					$css = ' style="'.$instance['css'].'"'; 
				}
				
				c('widget: ' . $instance['title'],1);
				if (!empty( $instance['textID'])){
				    e('<div class="dc_text" id="'.$instance['textID'].'"'.$css.'>');
				} else {
				    e('<div class="dc_text" id="'.$this->get_field_id('theText').'"'.$css.'>');
				}
				
				if (!empty( $instance['theText'])){
				    e($instance['theText']);
				}
				
				e('</div>');
				c('/'.$instance['title']);
	
	}
	
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['theText'] = $new_instance['theText'];
		$instance['textID'] = $new_instance['textID'];
		$instance['css'] = $new_instance['css'];
		
		return $instance;
	}
	
	function form($instance) {
		$instance = wp_parse_args( (array) $instance, array( 'theText' => '', 'textID' => '', 'css' => '') );
		$theText = $instance['theText'];
		$textID = $instance['textID']; 
		$css = $instance['css'];?>

<p>
	<label for="<?php echo $this->get_field_id('theText'); ?>">
		The Text: <input class="widefat" id="<?php echo $this->get_field_id('theText'); ?>" name="<?php echo $this->get_field_name('theText'); ?>" type="text" value="<?php echo attribute_escape($theText); ?>" />
	</label>
</p>
<p>
	<label for="<?php echo $this->get_field_id('textID'); ?>">
		Element ID: <input class="widefat" id="<?php echo $this->get_field_id('textID'); ?>" name="<?php echo $this->get_field_name('textID'); ?>" type="text" value="<?php echo attribute_escape($textID); ?>" />
	</label>
</p>
<p>
	<label for="<?php echo $this->get_field_id('css'); ?>">
		Inline CSS: <input class="widefat" id="<?php echo $this->get_field_id('css'); ?>" name="<?php echo $this->get_field_name('css'); ?>" type="text" value="<?php echo attribute_escape($css); ?>" />
	</label>
</p>

<?php }

}

register_widget('dc_text');

class dc_image extends WP_Widget {

	function __construct() {
		parent::WP_Widget( 
						'dc_image', // Base ID
						'dc image', // Name
						array( 'description' => 'Drop an image into a sidebar. (Added by the destructive-creative theme.)' ) );
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
				}
				
				elseif (!empty( $instance['imageURL'] )){
				    e('<img src="'.$instance['imageURL'].'" />');
				}
				
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



class dc_downloads extends WP_Widget {

	function __construct() {
		parent::WP_Widget( 
						'dc_downloads', // Base ID
						'dc downloads', // Name
						array( 'description' => 'Lists anything in any "downloads" custom field(s). (Added by the destructive-creative theme.)' ) );
	}

	function widget($args, $instance) {
	    if(is_single()){
    		extract($args, EXTR_SKIP);
			if (!empty($instance['css'])){ 
				$css = ' style="'.$instance['css'].'"'; 
			}
    				
			c('widget: dc_downloads',1);
			$meta_values = get_post_meta( get_the_ID(), 'download', false );
			
			if($meta_values){
			e('<div class="dc_downloads"'.$css.'><ul>'."\n");
			e('<h3>'.$instance['title'].'</h3>');
			    foreach($meta_values as $value){
			        e('<li>'.$value.'</li>');
			    }
			e('</ul></div>');
			c('/dc_downloads');
			} else {
			    c('no post meta fields called "download" found found',1);
			}
	    } else {
	        c('the dc_downloads widget only renders on "is_single()"');
	    }
	}
	
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['css'] = $new_instance['css'];
		$instance['title'] = $new_instance['title'];
		return $instance;
	}
	
	function form($instance) {
		$instance = wp_parse_args( (array) $instance, array('css' => '','title' => '') );
		$css = $instance['css'];
		$title = $instance['title'];?>

<p>
	<label for="<?php echo $this->get_field_id('title'); ?>">
		Title: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo attribute_escape($title); ?>" />
	</label>
</p>
<p>
	<label for="<?php echo $this->get_field_id('css'); ?>">
		Inline CSS: <input class="widefat" id="<?php echo $this->get_field_id('css'); ?>" name="<?php echo $this->get_field_name('css'); ?>" type="text" value="<?php echo attribute_escape($css); ?>" />
	</label>
</p>

<?php }

}

register_widget('dc_downloads');


class dc_requirements extends WP_Widget {

	function __construct() {
		parent::WP_Widget( 
						'dc_requirements', // Base ID
						'dc requirements', // Name
						array( 'description' => 'Lists anything in any "requirements" custom field(s). (Added by the destructive-creative theme.)' ) );
	}

	function widget($args, $instance) {
	    if(is_single()){
    		extract($args, EXTR_SKIP);
			if (!empty($instance['css'])){ 
				$css = ' style="'.$instance['css'].'"'; 
			}
    				
			c('widget: dc_requirements',1);
			$meta_values = get_post_meta( get_the_ID(), 'requirements', false );
			
			if($meta_values){
			e('<div class="dc_requirements"'.$css.'><ul>'."\n");
			e('<h3>'.$instance['title'].'</h3>');
			    foreach($meta_values as $value){
			        e('<li>'.$value.'</li>');
			    }
			e('</ul></div>');
			c('/dc_requirements');
			} else {
			    c('no post meta fields called "requirements" found found',1);
			}
	    } else {
	        c('the dc_requirements widget only renders on "is_single()"');
	    }
	}
	
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['css'] = $new_instance['css'];
		$instance['title'] = $new_instance['title'];
		return $instance;
	}
	
	function form($instance) {
		$instance = wp_parse_args( (array) $instance, array('css' => '','title' => '') );
		$css = $instance['css'];
		$title = $instance['title'];?>

<p>
	<label for="<?php echo $this->get_field_id('title'); ?>">
		Title: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo attribute_escape($title); ?>" />
	</label>
</p>
<p>
	<label for="<?php echo $this->get_field_id('css'); ?>">
		Inline CSS: <input class="widefat" id="<?php echo $this->get_field_id('css'); ?>" name="<?php echo $this->get_field_name('css'); ?>" type="text" value="<?php echo attribute_escape($css); ?>" />
	</label>
</p>

<?php }

}

register_widget('dc_requirements');


?>