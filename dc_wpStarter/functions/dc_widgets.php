<?php

// True to the EvD ethos, evd_Blank does nothing at all. That's the point!

class evd_Blank extends WP_Widget {

	function __construct() {
		parent::WP_Widget( 
            'evd_Blank', // Base ID
            'evd_Blank', // Name
            array( 'description' => 'Allows you to blank out the defaults for any sidebar.' ) );
	}

	function widget() {
		c('evd_Blank widget',1);
	}
}
register_widget("evd_Blank");


// evd_eval -------------------------------------------------------
// Insert arbitrary PHP into a sidebar ----------------------------

class evd_eval extends WP_Widget {
	function __construct() {
		parent::WP_Widget( 
						'evd_eval', // Base ID
						'evd_eval', // Name
						array( 'description' => 'Drop arbitrary PHP into a sidebar. WARNING: THIS COULD KILL YOU. Be careful kids.' ) );
	}
	function widget($args, $instance) {
		extract($args, EXTR_SKIP);
				if (!empty($instance['code'])){ eval($instance['code']); }
	}
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['code'] = $new_instance['code'];
		return $instance;
	}
	function form($instance) {
		$instance = wp_parse_args( (array) $instance, array( 'code' => 'echo "<!--evd_eval-->";' ) );
		$code = $instance['code'];?>
		<p><label for="<?php echo $this->get_field_id('code'); ?>">Code: <input class="widefat" id="<?php echo $this->get_field_id('code'); ?>" name="<?php echo $this->get_field_name('code'); ?>" type="text" value="<?php echo attribute_escape($code); ?>" /></label></p>
<?php }
}
register_widget('evd_eval');



// evd_image -------------------------------------------------------
// Insert an arbitrary link image into a sidebar -------------------

class evd_image extends WP_Widget {
	function __construct() {
		parent::WP_Widget( 
						'evd_image', // Base ID
						'evd_image', // Name
						array( 'description' => 'Drop an arbitrary image into a sidebar.' ) );
	}
	function widget($args, $instance) {
		extract($args, EXTR_SKIP);
				if (!empty($instance['css'])){ $css = ' style="'.$instance['css'].'"'; }
				echo '<!-- widget \'' . $instance['title'] . '\' -->'."\n".'<div class="evd_image" id="'.$this->get_field_id('imageURL').'"'.$css.'>'."\n";
				if ( !empty( $instance['imageURL'] ) && !empty( $instance['linkURL'] ) ) {  
					echo '<a href="'.$instance['linkURL'].'"><img src="'.$instance['imageURL'].'"></a>'."\n"; 
				};
				echo '</div>'."\n".'<!-- /widget \'' . $instance['title'] . '\' -->'."\n\n";
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
		<p><label for="<?php echo $this->get_field_id('imageURL'); ?>">Image URL: <input class="widefat" id="<?php echo $this->get_field_id('imageURL'); ?>" name="<?php echo $this->get_field_name('imageURL'); ?>" type="text" value="<?php echo attribute_escape($imageURL); ?>" /></label></p>
		<p><label for="<?php echo $this->get_field_id('linkURL'); ?>">Link URL: <input class="widefat" id="<?php echo $this->get_field_id('LinkURL'); ?>" name="<?php echo $this->get_field_name('linkURL'); ?>" type="text" value="<?php echo attribute_escape($linkURL); ?>" /></label></p>
		<p><label for="<?php echo $this->get_field_id('css'); ?>">Custom CSS: <input class="widefat" id="<?php echo $this->get_field_id('css'); ?>" name="<?php echo $this->get_field_name('css'); ?>" type="text" value="<?php echo attribute_escape($css); ?>" /></label></p>
<?php }
}
register_widget('evd_image');




// evd_latestVideo -------------------------------------------------
// Insert the first video embed in the most recent post with video -

class evd_latestVideo extends WP_Widget {
	function __construct() {
		parent::WP_Widget( 
						'evd_latestVideo', // Base ID
						'evd_latestVideo', // Name
						array( 'description' => 'Insert the first video embed in the most recent post with the \'video\' format.' ) );
	}
	function widget($args, $instance) {
		extract($args, EXTR_SKIP);
		$args = array(
		
		$myquery['tax_query'] => array(
				array(
					'taxonomy' => 'post_format',
					'field' => 'slug',
					'terms' => 'video',
				)
			)
		);
		$myquery['order'] = 'desc'; 
		$myquery['posts_per_page'] = 1;
		query_posts($myquery);
		
		if(have_posts()){
		while (have_posts()) : the_post();
			$qp = htmlqp(do_shortcode(get_the_content()));
			$content = $qp
			->top('body')
			->find('iframe')
			// qp will change <iframe></iframe> syntax to <iframe /> if there is no
			// content between the tags, so we add some.
			->text('video')
			->top('body')
			->find('iframe,object,.evdPlayer')
			->html();
			$content = preg_replace('/width=\"[0-9]*\"/','width="'.$instance['width'].'"',$content);
			$content = preg_replace('/height=\"[0-9]*\"/','height="'.$instance['height'].'"',$content);
			
			echo '<!--evd_latestVideo--><div class="evd_latestVideo"'; 
			if ($instance['css']!=''){echo ' style="'.$instance['css'].'"';}
			echo '>'.$content; 
			if(strpos($content,'evdPlayer')!==false) echo '<div class="evdPlayer_bigPlay">play</div>';
			echo '</div>'."\n";
		endwhile;	
		} else { echo '<!-- No posts found with the \'video\' format. --><!--/evd_latestVideo-->'."\n"; }
		
		wp_reset_query();
		

	}
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['width'] = $new_instance['width'];
		$instance['height'] = $new_instance['height'];
		$instance['css'] = $new_instance['css'];
		return $instance;
	}
	function form($instance) {
		$instance = wp_parse_args( (array) $instance, array( 'width' => '', 'height' => '', 'css' => '') );
		$width = $instance['width'];
		$height = $instance['height'];
		$css = $instance['css']; ?> 
		<p><label for="<?php echo $this->get_field_id('width'); ?>">width: <input class="widefat" id="<?php echo $this->get_field_id('width'); ?>" name="<?php echo $this->get_field_name('width'); ?>" type="text" value="<?php echo attribute_escape($width); ?>" /></label></p>
		<p><label for="<?php echo $this->get_field_id('height'); ?>">height: <input class="widefat" id="<?php echo $this->get_field_id('height'); ?>" name="<?php echo $this->get_field_name('height'); ?>" type="text" value="<?php echo attribute_escape($height); ?>" /></label></p>
		<p><label for="<?php echo $this->get_field_id('css'); ?>">Custom CSS: <input class="widefat" id="<?php echo $this->get_field_id('css'); ?>" name="<?php echo $this->get_field_name('css'); ?>" type="text" value="<?php echo attribute_escape($css); ?>" /></label></p>
		<?php }
}
register_widget('evd_latestVideo');


// Displays the logged in users display name and role

class evd_userInfo extends WP_Widget {

	function __construct() {
		parent::WP_Widget( 
            'evd_userInfo', // Base ID
            'evd_userInfo', // Name
            array( 'description' => 'Displays the logged in users display name and role.' ) );
	}

	function widget() {
		$current_user = wp_get_current_user();
		echo '<!-- widget \'' . $instance['title'] . '\' -->'."\n".'<div id="'.$this->get_field_id('evd_userInfo').'" class="widget evd_userInfo"><p>';
	    if ( ! empty( $current_user->roles ) )
	    	echo 'Welcome ' . $current_user->display_name . ', your level is ' . implode(", ", $current_user->roles);
	    echo '</p></div>'."\n";
	}
}
register_widget("evd_userInfo");


?>