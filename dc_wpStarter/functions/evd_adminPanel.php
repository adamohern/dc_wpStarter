<?php 

// Based on Alison Barrett's excellent tutorial:
// http://alisothegeek.com/2011/01/wordpress-settings-api-tutorial-2/

// Usage example: $var = get_option('evd_favicon');

$theme_options = new EvD_Config();

function evd_option( $option ) {
	$options = get_option( 'evd_options' );
	if ( isset( $options[$option] ) )
		return $options[$option];
	else
		return false;
}

class EvD_Config {
	
	private $sections;
	private $checkboxes;
	private $settings;
	
	/**
	 * Construct
	 *
	 * @since 1.0
	 */
	public function __construct() {
		
		// This will keep track of the checkbox options for the validate_settings function.
		$this->checkboxes = array();
		$this->settings = array();
		$this->get_settings();
		
		$this->sections['colors']   = __( 'Colors and Graphics' );
		$this->sections['layout']   = __( 'Layout' );
		$this->sections['sidebars']   = __( 'Sidebars' );
		$this->sections['content']   = __( 'Content' );
		$this->sections['seo']      = __( 'SEO' );
        $this->sections['css']   = __( 'Custom CSS' );
        $this->sections['php']   = __( 'Custom PHP' );
        $this->sections['js']   = __( 'Custom JS' );
		$this->sections['reset']        = __( 'Reset to Defaults' );
		
		// loads the Ace js code editor
		add_action('admin_enqueue_scripts','enqueue_ace');
		
		add_action( 'admin_menu', array( &$this, 'add_pages' ) );
		add_action( 'admin_init', array( &$this, 'register_settings' ) );
		
		if ( ! get_option( 'evd_options' ) )
			$this->initialize_settings();
		
	}
	
	/**
	 * Add options page
	 *
	 * @since 1.0
	 */
	public function add_pages() {
		
		$admin_page = add_theme_page( __( 'Theme Options' ), __( 'Theme Options' ), 'manage_options', 'evd-options', array( &$this, 'display_page' ) );
		
		add_action( 'admin_print_scripts-' . $admin_page, array( &$this, 'scripts' ) );
		add_action( 'admin_print_styles-' . $admin_page, array( &$this, 'styles' ) );
		
	}
	
	/**
	 * Create settings field
	 *
	 * @since 1.0
	 */
	public function create_setting( $args = array() ) {
		
		$defaults = array(
			'id'      => 'default_field',
			'title'   => __( 'Default Field' ),
			'desc'    => __( 'This is a default description.' ),
			'std'     => '',
			'type'    => 'text',
			'section' => 'seo',
			'choices' => array(),
			'class'   => ''
		);
			
		extract( wp_parse_args( $args, $defaults ) );
		
		$field_args = array(
			'type'      => $type,
			'id'        => $id,
			'desc'      => $desc,
			'std'       => $std,
			'choices'   => $choices,
			'label_for' => $id,
			'class'     => $class
		);
		
		if ( $type == 'checkbox' )
			$this->checkboxes[] = $id;
		
		add_settings_field( $id, $title, array( $this, 'display_setting' ), 'evd-options', $section, $field_args );
	}
	
	/**
	 * Display options page
	 *
	 * @since 1.0
	 */
	public function display_page() {
		
		echo '<div class="wrap">
	<div class="icon32" id="icon-options-general"></div>
	<h2>' . __( 'Theme Options' ) . '</h2>';
	
		if ( isset( $_GET['settings-updated'] ) && $_GET['settings-updated'] == true )
			echo '<div class="updated fade"><p>' . __( 'Theme options updated.' ) . '</p></div>';
		
		echo '<form action="options.php" method="post">';
	
		settings_fields( 'evd_options' );
		echo '<div class="ui-tabs">
			<ul class="ui-tabs-nav">';
		
		foreach ( $this->sections as $section_slug => $section )
			echo '<li><a href="#' . $section_slug . '">' . $section . '</a></li>';
		
		echo '</ul>';
		do_settings_sections( $_GET['page'] );
		
		echo '</div>
		<p class="submit"><input name="Submit" type="submit" class="button-primary" value="' . __( 'Save Changes' ) . '" /></p>
		
	</form>';
	
	echo '<script type="text/javascript">
		jQuery(document).ready(function($) {
			var sections = [];';
			
			foreach ( $this->sections as $section_slug => $section )
				echo "sections['$section'] = '$section_slug';";
			
			echo 'var wrapped = $(".wrap h3").wrap("<div class=\"ui-tabs-panel\">");
			wrapped.each(function() {
				$(this).parent().append($(this).parent().nextUntil("div.ui-tabs-panel"));
			});
			$(".ui-tabs-panel").each(function(index) {
				$(this).attr("id", sections[$(this).children("h3").text()]);
				if (index > 0)
					$(this).addClass("ui-tabs-hide");
			});
			$(".ui-tabs").tabs({
				fx: { opacity: "toggle", duration: "fast" }
			});
			
			$("input[type=text], textarea").each(function() {
				if ($(this).val() == $(this).attr("placeholder") || $(this).val() == "")
					$(this).css("color", "#999");
			});
			
			$("input[type=text], textarea").focus(function() {
				if ($(this).val() == $(this).attr("placeholder") || $(this).val() == "") {
					$(this).val("");
					$(this).css("color", "#000");
				}
			}).blur(function() {
				if ($(this).val() == "" || $(this).val() == $(this).attr("placeholder")) {
					$(this).val($(this).attr("placeholder"));
					$(this).css("color", "#999");
				}
			});
			
			$(".wrap h3, .wrap table").show();
			
			// This will make the "warning" checkbox class really stand out when checked.
			// I use it here for the Reset checkbox.
			$(".warning").change(function() {
				if ($(this).is(":checked"))
					$(this).parent().css("background", "#c00").css("color", "#fff").css("fontWeight", "bold");
				else
					$(this).parent().css("background", "none").css("color", "inherit").css("fontWeight", "normal");
			});
			
			// Browser compatibility
			if ($.browser.mozilla) 
			         $("form").attr("autocomplete", "off");
		});
	</script>
</div>';
		
	}
	
	/**
	 * Description for section
	 *
	 * @since 1.0
	 */
	public function display_section() {
		// code
	}
	
	/**
	 * HTML output for text field
	 *
	 * @since 1.0
	 */
	public function display_setting( $args = array() ) {
		
		extract( $args );
		
		$options = get_option( 'evd_options' );
		
		if ( ! isset( $options[$id] ) && $type != 'checkbox' )
			$options[$id] = $std;
		elseif ( ! isset( $options[$id] ) )
			$options[$id] = 0;
		
		$field_class = '';
		if ( $class != '' )
			$field_class = ' ' . $class;
		
		switch ( $type ) {
			
			case 'heading':
				echo '</td></tr><tr valign="top"><td colspan="2"><h4>' . $desc . '</h4>';
				br(1);
				break;
			
			case 'checkbox':
				
				echo '<input class="checkbox' . $field_class . '" type="checkbox" id="' . $id . '" name="evd_options[' . $id . ']" value="1" ' . checked( $options[$id], 1, false ) . ' /> <label for="' . $id . '">' . $desc . '</label>';
				
				br(1);
				break;
			
			case 'select':
				echo '<select class="select' . $field_class . '" name="evd_options[' . $id . ']">';
				
				foreach ( $choices as $value => $label )
					echo '<option value="' . esc_attr( $value ) . '"' . selected( $options[$id], $value, false ) . '>' . $label . '</option>';
				
				echo '</select>';
				
				if ( $desc != '' )
					echo '<br /><span class="description">' . $desc . '</span>';
				
				br(1);
				break;
			
			case 'radio':
				$i = 0;
				foreach ( $choices as $value => $label ) {
					echo '<input class="radio' . $field_class . '" type="radio" name="evd_options[' . $id . ']" id="' . $id . $i . '" value="' . esc_attr( $value ) . '" ' . checked( $options[$id], $value, false ) . '> <label for="' . $id . $i . '">' . $label . '</label>';
					if ( $i < count( $options ) - 1 )
						echo '<br />';
					$i++;
				}
				
				if ( $desc != '' )
					echo '<br /><span class="description">' . $desc . '</span>';
				
				br(1);
				break;
			
			case 'textarea':
				echo '<textarea class="' . $field_class . '" id="' . $id . '" name="evd_options[' . $id . ']" placeholder="' . $std . '" rows="5" cols="30">' . wp_htmledit_pre( $options[$id] ) . '</textarea>';
				
				if ( $desc != '' )
					echo '<br /><span class="description">' . $desc . '</span>';
				
				br(1);
				break;
			
			case 'password':
				echo '<input class="regular-text' . $field_class . '" type="password" id="' . $id . '" name="evd_options[' . $id . ']" value="' . esc_attr( $options[$id] ) . '" />';
				
				if ( $desc != '' )
					echo '<br /><span class="description">' . $desc . '</span>';
				
				br(1);
				break;
			
			case 'text':
			default:
		 		echo '<input class="regular-text' . $field_class . '" type="text" id="' . $id . '" name="evd_options[' . $id . ']" placeholder="' . $std . '" value="' . esc_attr( $options[$id] ) . '" />';
				
		 		if ( $desc != '' )
		 			echo '<br /><span class="description">' . $desc . '</span>';
		 		
				br(1);
		 		break;
				
			case 'color':
			default:
		 		echo '<div id="'.$id.'_swatch" class="swatch" style="width:20px; height:20px; float:left; margin-top:3px; margin-right:10px; background-color:'.esc_attr($options[$id]).';"></div><input class="regular-text' . $field_class . '" type="text" id="' . $id . '" name="evd_options[' . $id . ']" placeholder="' . $std . '" value="' . esc_attr( $options[$id] ) . '" />';
				echo '<script>document.getElementById("'.$id.'").onchange=function(){ document.getElementById("'.$id.'_swatch").style.backgroundColor=this.value; } ;</script>';

		 		if ( $desc != '' )
		 			echo '<br /><span class="description">' . $desc . '</span>';
		 		
				br(1);
		 		break;
				
			case 'css':
			default:
				echo '<div id="'.$id.'_editor" style="border:1px solid #eee;width:95%;height:200px;position:relative;display:block;">'.esc_attr($options[$id]).'</div>'; br();
				echo '<script>'."\n";
				echo 'var '.$id.' = ace.edit("'.$id.'_editor"); '.$id.'.setTheme("ace/theme/textmate"); '.$id.'.getSession().setMode("ace/mode/css");'."\n";
				echo $id.'.getSession().on(\'change\', function(e) { document.getElementById(\''.$id.'\').value = '.$id.'.getSession().getValue(); });'."\n";
				echo '</script>'."\n";
				echo '<textarea style="display:none;" class="' . $field_class . '" id="' . $id . '" name="evd_options[' . $id . ']" placeholder="' . $std . '" rows="5" cols="30">' . wp_htmledit_pre( $options[$id] ) . '</textarea>';

		 		
		 		if ( $desc != '' )
		 			echo '<br /><span class="description">' . $desc . '</span>';
					
		 		br(1);
		 		break;
                 
    		case 'css_big':
			default:
				echo '<div id="'.$id.'_editor" style="border:1px solid #eee;width:95%;height:600px;position:relative;display:block;">'.esc_attr($options[$id]).'</div>'; br();
				echo '<script>'."\n";
				echo 'var '.$id.' = ace.edit("'.$id.'_editor"); '.$id.'.setTheme("ace/theme/textmate"); '.$id.'.getSession().setMode("ace/mode/css");'."\n";
				echo $id.'.getSession().on(\'change\', function(e) { document.getElementById(\''.$id.'\').value = '.$id.'.getSession().getValue(); });'."\n";
				echo '</script>'."\n";
				echo '<textarea style="display:none;" class="' . $field_class . '" id="' . $id . '" name="evd_options[' . $id . ']" placeholder="' . $std . '" rows="5" cols="30">' . wp_htmledit_pre( $options[$id] ) . '</textarea>';

		 		
		 		if ( $desc != '' )
		 			echo '<br /><span class="description">' . $desc . '</span>';
					
		 		br(1);
		 		break;
				
			case 'php':
			default:
		 		echo '<div id="'.$id.'_editor" style="border:1px solid #eee;width:95%;height:200px;position:relative;display:block;">'.esc_attr($options[$id]).'</div>'; br();
				echo '<script>';br();
				echo 'var '.$id.' = ace.edit("'.$id.'_editor"); '.$id.'.setTheme("ace/theme/textmate"); '.$id.'.getSession().setMode("ace/mode/php");'; br();
				echo $id.'.getSession().on(\'change\', function(e) { document.getElementById(\''.$id.'\').value = '.$id.'.getSession().getValue(); });'; br();
				echo '</script>'."\n"; br();
				echo '<textarea style="display:none;" class="' . $field_class . '" id="' . $id . '" name="evd_options[' . $id . ']" placeholder="' . $std . '" rows="5" cols="30">' . wp_htmledit_pre( $options[$id] ) . '</textarea>'; 

		 		
		 		if ( $desc != '' )
		 			echo '<br /><span class="description">' . $desc . '</span>';
		 		
				br(1);
		 		break;
                 
    		case 'php_big':
			default:
		 		echo '<div id="'.$id.'_editor" style="border:1px solid #eee;width:95%;height:600px;position:relative;display:block;">'.esc_attr($options[$id]).'</div>'; br();
				echo '<script>';br();
				echo 'var '.$id.' = ace.edit("'.$id.'_editor"); '.$id.'.setTheme("ace/theme/textmate"); '.$id.'.getSession().setMode("ace/mode/php");'; br();
				echo $id.'.getSession().on(\'change\', function(e) { document.getElementById(\''.$id.'\').value = '.$id.'.getSession().getValue(); });'; br();
				echo '</script>'."\n"; br();
				echo '<textarea style="display:none;" class="' . $field_class . '" id="' . $id . '" name="evd_options[' . $id . ']" placeholder="' . $std . '" rows="5" cols="30">' . wp_htmledit_pre( $options[$id] ) . '</textarea>'; 

		 		
		 		if ( $desc != '' )
		 			echo '<br /><span class="description">' . $desc . '</span>';
		 		
				br(1);
		 		break;
				
			case 'html':
			default:
		 		echo '<div id="'.$id.'_editor" style="border:1px solid #eee;width:95%;height:200px;position:relative;display:block;">'.esc_attr($options[$id]).'</div>'; br();
				echo '<script>'; br();
				echo 'var '.$id.' = ace.edit("'.$id.'_editor"); '.$id.'.setTheme("ace/theme/textmate"); '.$id.'.getSession().setMode("ace/mode/html");'; br();
				echo $id.'.getSession().on(\'change\', function(e) { document.getElementById(\''.$id.'\').value = '.$id.'.getSession().getValue(); });'; br();
				echo '</script>'."\n";br();
				echo '<textarea style="display:none;" class="' . $field_class . '" id="' . $id . '" name="evd_options[' . $id . ']" placeholder="' . $std . '" rows="5" cols="30">' . wp_htmledit_pre( $options[$id] ) . '</textarea>'; 

		 		
		 		if ( $desc != '' )
		 			echo '<br /><span class="description">' . $desc . '</span>';
		 		
				br(1);
		 		break;
                 
            case 'js_big':
			default:
		 		echo '<div id="'.$id.'_editor" style="border:1px solid #eee;width:95%;height:600px;position:relative;display:block;">'.esc_attr($options[$id]).'</div>'; br();
				echo '<script>'; br();
				echo 'var '.$id.' = ace.edit("'.$id.'_editor"); '.$id.'.setTheme("ace/theme/textmate"); '.$id.'.getSession().setMode("ace/mode/javascript");'; br();
				echo $id.'.getSession().on(\'change\', function(e) { document.getElementById(\''.$id.'\').value = '.$id.'.getSession().getValue(); });'; br();
				echo '</script>'."\n";br();
				echo '<textarea style="display:none;" class="' . $field_class . '" id="' . $id . '" name="evd_options[' . $id . ']" placeholder="' . $std . '" rows="5" cols="30">' . wp_htmledit_pre( $options[$id] ) . '</textarea>'; 

		 		
		 		if ( $desc != '' )
		 			echo '<br /><span class="description">' . $desc . '</span>';
		 		
				br(1);
		 		break;
		 	
		}
		
	}
	
	
	/**
	 * Settings and defaults
	 * 
	 * @since 1.0
	 */
	public function get_settings() {




		
		/* Layout
		===========================================*/


		$this->settings['evd_homeListContent'] = array(
			'section' => 'layout',
			'title'   => __( 'Home List Content' ),
			'desc'    => __( 'What type of content should be displayed on the home screen?' ),
			'type'    => 'radio',
			'std'     => 'content',
			'choices' => array(
				'excerpt' => 'Display a content excerpt.',
				'content' => 'Display full content (up to any \'more\' tags).',
				'none' => 'Do not display content.'
			)
		);

		$this->settings['evd_archiveListContent'] = array(
			'section' => 'layout',
			'title'   => __( 'Archive List Content' ),
			'desc'    => __( 'What type of content should be displayed on archives and search results?' ),
			'type'    => 'radio',
			'std'     => 'excerpt',
			'choices' => array(
				'excerpt' => 'Display a content excerpt.',
				'content' => 'Display full content (up to any \'more\' tags).',
				'none' => 'Do not display content.'
			)
		);
		
		$this->settings['evd_displayThumb_archive'] = array(
			'section' => 'layout',
			'title'   => __( 'Display Thumbnails in Archives' ),
			'desc'    => __( 'Show the \'Featured Image\' with posts in a list (i.e. is_archive())?' ),
			'type'    => 'checkbox',
			'std'     => 0 // Set to 1 to be checked by default, 0 to be unchecked by default.
		);
		
		$this->settings['evd_thumbsize_archive'] = array(
			'section' => 'layout',
			'title'   => __( 'Thumbnail Size - Archives' ),
			'desc'    => __( 'If enabled above, how big should the thumbnail be on is_archive()?' ),
			'type'    => 'radio',
			'std'     => '1',
			'choices' => array(
				'evd_thumbnail' => 'Tall (256x144)',
				'evd_large' => 'Grande (720x405)',
				'evd_huge' => 'Venti (960x540)'
			)
		);
		
		$this->settings['evd_displayThumb_single'] = array(
			'section' => 'layout',
			'title'   => __( 'Display Thumbnails on Posts' ),
			'desc'    => __( 'Show the \'Featured Image\' with individual posts (i.e. is_single())?' ),
			'type'    => 'checkbox',
			'std'     => 0 // Set to 1 to be checked by default, 0 to be unchecked by default.
		);
		
		$this->settings['evd_thumbsize_single'] = array(
			'section' => 'layout',
			'title'   => __( 'Thumbnail Size - Posts' ),
			'desc'    => __( 'If enabled above, how big should the thumbnail be on is_single()?' ),
			'type'    => 'radio',
			'std'     => '1',
			'choices' => array(
				'evd_thumbnail' => 'Medium (256x144)',
				'evd_large' => 'Large (720x405)',
				'evd_huge' => 'Supersize Me (960x540)'
			)
		);

		$this->settings['evd_pageTitles'] = array(
			'section' => 'layout',
			'title'   => __( 'Display Page Titles' ),
			'desc'    => __( 'Should we display the titles of pages?' ),
			'type'    => 'radio',
			'std'     => '1',
			'choices' => array(
				'1' => 'yes, show page titles',
				'0' => 'no, hide page titles'
			)
		);
        
        /* CSS
		===========================================*/
        
    	$this->settings['evd_cssOverrides'] = array(
			'title'   => __( 'CSS Overrides' ),
			'desc'    => __( 'Enter any custom CSS here to apply it to your theme. Hint: copy/paste this into a <a href="http://notepad-plus-plus.org/">CSS editor</a> for editing.' ),
			'std'     => '',
			'type'    => 'css_big',
			'section' => 'css',
			'class'   => 'evd_cssOverrides code'
		);
        
    	/* PHP
		===========================================*/
        
        $this->settings['evd_customPHP'] = array(
			'section' => 'php',
			'title'   => __( 'Custom PHP' ),
			'desc'    => __( 'Enter valid PHP that will run before the page is rendered. All valid WordPress hooks can be used.' ),
			'type'    => 'php_big',
			'std'     => ''
		);
		
		$this->settings['evd_postListCode'] = array(
			'section' => 'php',
			'title'   => __( 'Post List Code' ),
			'desc'    => __( 'Enter valid PHP code for a string defining the content between &lt;li&gt; tags in a evd_postsList list.' ),
			'type'    => 'php',
			'std'     => '\'&#039;&lt;div class=&quot;title&quot;&gt;&lt;a href=&quot;&#039;.get_permalink($post-&gt;ID).&#039;&quot; title=&quot;posted &#039;.get_the_date(&#039;d M,Y&#039;).&#039;&quot;&gt;&#039;.get_the_title($post-&gt;ID).&#039;&lt;/a&gt;&lt;/div&gt;&#039;'
		);
        
    	$this->settings['evd_postListTileCode'] = array(
			'section' => 'php',
			'title'   => __( 'Post List Tile Code' ),
			'desc'    => __( 'Enter valid PHP code for a string defining the content between &lt;li&gt; tags in a evd_postsList2 list where tiles=true.' ),
			'type'    => 'php',
			'std'     => '\'&lt;div class=&quot;thumb&quot;&gt;&lt;a href=&quot;&#039;.get_permalink($post-&gt;ID).&#039;&quot;&gt;&#039;.get_the_post_thumbnail($page-&gt;ID, evd_option(\'evd_thumbsize_archive\')).&#039;&lt;/a&gt;&lt;/div&gt;\'.&#039;&lt;div class=&quot;title&quot;&gt;&lt;a href=&quot;&#039;.get_permalink($post-&gt;ID).&#039;&quot; title=&quot;posted &#039;.get_the_date(&#039;d M,Y&#039;).&#039;&quot;&gt;&#039;.get_the_title($post-&gt;ID).&#039;&lt;/a&gt;&lt;/div&gt;&#039;'
		);
		
		$this->settings['evd_shortMeta'] = array(
			'section' => 'php',
			'title'   => __( 'Short Meta' ),
			'desc'    => __( 'Enter valid PHP code for the meta to be displayed in post lists.' ),
			'type'    => 'php',
			'std'     => 'edit_post_link(\'Edit\',\'\',\' \'); the_time(\'d M, Y\'); echo \' by \'; the_author(); echo \' in \'; the_category(\', \');'
		);
		
		$this->settings['evd_longMeta'] = array(
			'section' => 'php',
			'title'   => __( 'Long Meta' ),
			'desc'    => __( 'Enter valid PHP code for the meta to be displayed on single posts.' ),
			'type'    => 'php',
			'std'     => 'edit_post_link(\'Edit\',\'\',\' \');'."\n".' the_time(\'d M, Y\'); echo \' by \'; the_author(); echo \' in \'; the_category(\', \'); echo \'<br />\'; the_tags();'
		);
        
        /* JS
		===========================================*/
        
        $this->settings['evd_customJS'] = array(
			'section' => 'js',
			'title'   => __( 'Custom Javascript - head' ),
			'desc'    => __( 'Enter valid jquery-compatible Javascript to insert into the header.' ),
			'type'    => 'js_big',
			'std'     => ''
		);
        
        $this->settings['evd_customJS_footer'] = array(
    		'section' => 'js',
			'title'   => __( 'Custom Javascript - foot' ),
			'desc'    => __( 'Enter valid jquery-compatible Javascript to insert into the header.' ),
			'type'    => 'js_big',
			'std'     => ''
		);

		/* Sidebars
		===========================================*/
		
		$this->settings['evd_headerSidebar'] = array(
			'section' => 'sidebars',
			'title'   => __( 'Header Sidebar Mode' ),
			'desc'    => __( 'The header sidebar is full-width, flexible height, and can animate to partially hide itself on mouseout. You can also just hide it completely!' ),
			'type'    => 'radio',
			'std'     => 'animate',
			'choices' => array(
				'animate' => 'Animate',
				'visible' => 'Always Visible',
				'hidden' => 'Never Visible'
			)
		);
		
		$this->settings['evd_mainSidebar'] = array(
			'section' => 'sidebars',
			'title'   => __( 'Main Sidebar Location' ),
			'desc'    => __( 'Where should we put the main sidebar?' ),
			'type'    => 'radio',
			'std'     => 'left',
			'choices' => array(
				'left' => 'Left',
				'right' => 'Right'
			)
		);

		$this->settings['evd_sidebars-Header_Widgets'] = array(
			'section' => 'sidebars',
			'title'   => __( 'Header_Widgets' ),
			'desc'    => __( 'Topmost header on the page, above all content.' ),
			'type'    => 'checkbox',
			'std'     => 1 // Set to 1 to be checked by default, 0 to be unchecked by default.
		);

    	$this->settings['evd_sidebars-Banner_All'] = array(
			'section' => 'sidebars',
			'title'   => __( 'Banner_All' ),
			'desc'    => __( 'Full-width sidebar below Header_Widgets but above #content and Main_Sidebar on home, archive, search, post, and page views.' ),
			'type'    => 'checkbox',
			'std'     => 0 // Set to 1 to be checked by default, 0 to be unchecked by default.
		);

		$this->settings['evd_sidebars-Banner_Home'] = array(
			'section' => 'sidebars',
			'title'   => __( 'Banner_Home' ),
			'desc'    => __( 'Full-width sidebar below Header_Widgets but above #content and Main_Sidebar on is_home().' ),
			'type'    => 'checkbox',
			'std'     => 0 // Set to 1 to be checked by default, 0 to be unchecked by default.
		);
		
		$this->settings['evd_sidebars-Banner_Archive'] = array(
			'section' => 'sidebars',
			'title'   => __( 'Banner_Archive' ),
			'desc'    => __( 'Full-width sidebar below Header_Widgets but above #content and Main_Sidebar on is_archive().' ),
			'type'    => 'checkbox',
			'std'     => 0 // Set to 1 to be checked by default, 0 to be unchecked by default.
		);
		
		$this->settings['evd_sidebars-Main_Sidebar'] = array(
			'section' => 'sidebars',
			'title'   => __( 'Main_Sidebar' ),
			'desc'    => __( 'Appears at either left or right of #content.' ),
			'type'    => 'checkbox',
			'std'     => 1 // Set to 1 to be checked by default, 0 to be unchecked by default.
		);

		$this->settings['evd_sidebars-Footer_Widgets'] = array(
			'section' => 'sidebars',
			'title'   => __( 'Footer_Widgets' ),
			'desc'    => __( 'Appears at bottom of #content.' ),
			'type'    => 'checkbox',
			'std'     => 0 // Set to 1 to be checked by default, 0 to be unchecked by default.
		);		

		$this->settings['evd_sidebars-Before_Single'] = array(
			'section' => 'sidebars',
			'title'   => __( 'Before_Single' ),
			'desc'    => __( 'Appears at top of #content on is_single().' ),
			'type'    => 'checkbox',
			'std'     => 0 // Set to 1 to be checked by default, 0 to be unchecked by default.
		);

		$this->settings['evd_sidebars-After_Single'] = array(
			'section' => 'sidebars',
			'title'   => __( 'After_Single' ),
			'desc'    => __( 'Appears at bottom of #content on is_single().' ),
			'type'    => 'checkbox',
			'std'     => 0 // Set to 1 to be checked by default, 0 to be unchecked by default.
		);

		$this->settings['evd_sidebars-Before_Archive'] = array(
			'section' => 'sidebars',
			'title'   => __( 'Before_Archive' ),
			'desc'    => __( 'Appears at top of #content on is_archive().' ),
			'type'    => 'checkbox',
			'std'     => 0 // Set to 1 to be checked by default, 0 to be unchecked by default.
		);
		
		$this->settings['evd_sidebars-After_Archive'] = array(
			'section' => 'sidebars',
			'title'   => __( 'After_Archive' ),
			'desc'    => __( 'Appears at bottom of #content on is_archive().' ),
			'type'    => 'checkbox',
			'std'     => 0 // Set to 1 to be checked by default, 0 to be unchecked by default.
		);
		
		$this->settings['evd_sidebars-Before_Home'] = array(
			'section' => 'sidebars',
			'title'   => __( 'Before_Home' ),
			'desc'    => __( 'Appears at top of #content on is_home().' ),
			'type'    => 'checkbox',
			'std'     => 0 // Set to 1 to be checked by default, 0 to be unchecked by default.
		);


		/* Content
		===========================================*/
		
		$this->settings['evd_debugMode'] = array(
			'section' => 'content',
			'title'   => __( 'Debug Mode' ),
			'desc'    => __( 'Displays helpful comments explaining the HTML output.' ),
			'type'    => 'radio',
			'std'     => '1',
			'choices' => array(
				'1' => 'yes, display the comments',
				'0' => 'no, don\'t display comments'
			)
		);
		
		$this->settings['evd_displayBio'] = array(
			'section' => 'content',
			'title'   => __( 'Author Bio' ),
			'desc'    => __( 'Display the author bio (from user meta) below posts?' ),
			'type'    => 'checkbox',
			'std'     => 1 // Set to 1 to be checked by default, 0 to be unchecked by default.
		);

		$this->settings['evd_disableComments'] = array(
			'section' => 'content',
			'title'   => __( 'Disable Comments' ),
			'desc'    => __( 'Check to disable ALL comments completely (including external comment systems). Does NOT apply to admin users.' ),
			'type'    => 'checkbox',
			'std'     => 0 // Set to 1 to be checked by default, 0 to be unchecked by default.
		);
		
		 $this->settings['evd_commentsDisabledMessage'] = array(
			'title'   => __( 'Comments Disabled Message' ),
			'desc'    => __( 'HTML to display in place of comments if Disable Comments is checked.' ),
			'std'     => '',
			'type'    => 'html',
			'section' => 'content',
			'class'   => 'code'
		);
		
		$this->settings['evd_404Message'] = array(
			'section' => 'content',
			'title'   => __( '404 Message' ),
			'desc'    => __( 'The paragraph text below the bad URL in the 404 alert box. (Plain text)' ),
			'type'    => 'text',
			'std'     => 'Oops! Looks like we\'re missing something here...'
		);
		
		$this->settings['evd_noscriptMessage'] = array(
			'section' => 'content',
			'title'   => __( 'No Javascript Message' ),
			'desc'    => __( 'The paragraph text in the \'Javascript disabled.\' alert box. (Plain text; Leave blank for no alert.)' ),
			'type'    => 'text',
			'std'     => 'We use clean, safe Javascript to make our sites easier to navigate. Please consider enabling Javascript for this site.'
		);


		/* Colors
		===========================================*/

		$this->settings['evd_primaryColor'] = array(
			'title'   => __( 'Primary Color' ),
			'desc'    => __( 'Main hex color used for links, certain headings, etc.' ),
			'std'     => '#66479c',
			'type'    => 'color',
			'section' => 'colors'
		);
		
		$this->settings['evd_primaryColorFaded'] = array(
			'title'   => __( 'Primary Color Faded' ),
			'desc'    => __( 'Main hex color used for visited and active links, certain decorative elements, etc.' ),
			'std'     => '#886eb6',
			'type'    => 'color',
			'section' => 'colors'
		);
		
		$this->settings['evd_selectionColor'] = array(
			'title'   => __( 'Selection Highlight Color' ),
			'desc'    => __( 'Hex color used to highlight selected text.' ),
			'std'     => '#66479c',
			'type'    => 'color',
			'section' => 'colors'
		);
		
		$this->settings['evd_selectionTextColor'] = array(
			'title'   => __( 'Selection Text Color' ),
			'desc'    => __( 'Hex color used for selected text.' ),
			'std'     => '#fff',
			'type'    => 'color',
			'section' => 'colors'
		);
		
		$this->settings['evd_alertColor'] = array(
			'title'   => __( 'Alert Background Color' ),
			'desc'    => __( 'Main hex color used for background for alert boxes (e.g. 404).' ),
			'std'     => '#ffc',
			'type'    => 'color',
			'section' => 'colors'
		);
		
		$this->settings['evd_alertBorderColor'] = array(
			'title'   => __( 'Alert Border Color' ),
			'desc'    => __( 'Main hex color used for border around alert boxes (e.g. 404).' ),
			'std'     => '#fc0',
			'type'    => 'color',
			'section' => 'colors'
		);
		
		$this->settings['evd_alertTextColor'] = array(
			'title'   => __( 'Alert Text Color' ),
			'desc'    => __( 'Main hex color used for text in alert boxes (e.g. 404).' ),
			'std'     => '#000',
			'type'    => 'color',
			'section' => 'colors'
		);
		
		$this->settings['evd_favicon'] = array(
			'section' => 'colors',
			'title'   => __( 'Favicon' ),
			'desc'    => __( 'Enter the URL to your custom favicon. It should be 32x32, transparency is ok. PNG works well.' ),
			'type'    => 'text',
			'std'     => get_bloginfo('wpurl').'/favicon.ico'
		);
		
		$this->settings['evd_appleicon'] = array(
			'section' => 'colors',
			'title'   => __( 'Apple Favorite Icon' ),
			'desc'    => __( 'Enter the URL to your custom favicon for iOS. It should <a href="http://developer.apple.com/library/ios/#documentation/userexperience/conceptual/mobilehig/IconsImages/IconsImages.html">probably</a> be 114x114. PNG works well.' ),
			'type'    => 'text',
			'std'     => get_bloginfo('wpurl').'/appleicon.ico'
		);
		
		$this->settings['evd_jqueryui_theme'] = array(
			'section' => 'colors',
			'title'   => __( 'jquery UI theme' ),
			'desc'    => __( 'Enter the URL to your favorite jquery UI theme.' ),
			'type'    => 'text',
			'std'     => 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.23/themes/smoothness/jquery-ui.css'
		);
		
		$this->settings['evd_xman'] = array(
			'section' => 'colors',
			'title'   => __( 'Your Favorite X-Man (or woman)' ),
			'desc'    => __( 'This is really up to you. Choose wisely.' ),
			'type'    => 'select',
			'std'     => '',
			'choices' => array(
				'xavier' => 'Xavier',
				'cyclopse' => 'Cyclopse',
				'iceman' => 'Iceman',
				'angel' => 'Angel',
				'beast' => 'Beast',
				'marvelgirl' => 'Marvel Girl',
				'wolverine' => 'Wolverine',
				'storm' => 'Storm',
				'emmafrost' => 'Emma Frost',
				'colossus' => 'Colossus',
				'nightcrawler' => 'Nightcrawler',
				'shadowcat' => 'Shadowcat',
				'rogue' => 'Rogue',
				'other' => 'Other'
			)
		);


		/* SEO Settings
		===========================================*/
		
		$this->settings['evd_defaultFocus'] = array(
			'title'   => __( 'Default Focus Keyword' ),
			'desc'    => __( 'Single keyword for use as the default \'focus\' keyword.' ),
			'std'     => '',
			'type'    => 'text',
			'section' => 'seo'
		);
		
		$this->settings['evd_useMetaKeywords'] = array(
			'section' => 'seo',
			'title'   => __( 'Use Meta Keywords Tag?' ),
			'desc'    => __( ' - If you\'re not sure, <a href="http://googlewebmastercentral.blogspot.com/2009/09/google-does-not-use-keywords-meta-tag.html">probably not</a>.' ),
			'type'    => 'checkbox',
			'std'     => 0 // Set to 1 to be checked by default, 0 to be unchecked by default.
		);
		
		$this->settings['evd_globalKeywords'] = array(
			'title'   => __( 'Global Keywords' ),
			'desc'    => __( '(Only used if Use Meta Keywords Tag is enabled.) Comma-delimited keywords for use in the \'keywords\' header meta tag.' ),
			'std'     => '',
			'type'    => 'text',
			'section' => 'seo'
		);
				
		$this->settings['evd_titleSlug'] = array(
			'title'   => __( 'Title Slug' ),
			'desc'    => __( 'Programmer-friendly site title, used as a class for the <body> element on all pages.' ),
			'std'     => preg_replace("/[^a-zA-Z0-9]/", "",get_bloginfo('name')),
			'type'    => 'text',
			'section' => 'seo'
		);
		
		$this->settings['evd_authorName'] = array(
			'title'   => __( 'Author Name' ),
			'desc'    => __( 'Used for copyright and author meta tags.' ),
			'std'     => get_bloginfo('name'),
			'type'    => 'text',
			'section' => 'seo'
		);
		
		$this->settings['evd_indexSEODescription'] = array(

			'title'   => __( 'Index SEO Description' ),
			'desc'    => __( '70 chars describing the site. Will be used by default for index and archive pages. On single posts or pages, the excerpt is used.' ),
			'std'     => '',
			'type'    => 'textarea',
			'section' => 'seo'
		);
		
		$this->settings['evd_analyticsHeading'] = array(
			'section' => 'seo',
			'title'   => '', // Not used for headings.
			'desc'    => 'Performance Tracking',
			'type'    => 'heading'
		);
		
		$this->settings['evd_googleAnalytics'] = array(
			'title'   => __( 'Google Analytics' ),
			'desc'    => __( 'Paste the block of code provided by Google for performance tracking. This will be inserted in the page header.' ),
			'std'     => '',
			'type'    => 'html',
			'section' => 'seo'
		);
		
		$this->settings['evd_googleVerification'] = array(
			'title'   => __( 'Google Site Verification Code' ),
			'desc'    => __( 'Speaking of Google, don\'t forget to set your site up: http://google.com/webmasters.' ),
			'std'     => '',
			'type'    => 'text',
			'section' => 'seo'
		);





		/* Reset
		===========================================*/
		
		$this->settings['reset_theme'] = array(
			'section' => 'reset',
			'title'   => __( 'Reset theme' ),
			'type'    => 'checkbox',
			'std'     => 0,
			'class'   => 'warning', // Custom class for CSS
			'desc'    => __( 'Check this box and click "Save Changes" below to reset theme options to their defaults.' )
		);
		
	}
	
	/**
	 * Initialize settings to their default values
	 * 
	 * @since 1.0
	 */
	public function initialize_settings() {
		
		$default_settings = array();
		foreach ( $this->settings as $id => $setting ) {
			if ( $setting['type'] != 'heading' )
				$default_settings[$id] = $setting['std'];
		}
		
		update_option( 'evd_options', $default_settings );
		
	}
	
	/**
	* Register settings
	*
	* @since 1.0
	*/
	public function register_settings() {
		
		register_setting( 'evd_options', 'evd_options', array ( &$this, 'validate_settings' ) );
		
		foreach ( $this->sections as $slug => $title ) {
			if ( $slug == 'about' )
				add_settings_section( $slug, $title, array( &$this, 'display_about_section' ), 'evd-options' );
			else
				add_settings_section( $slug, $title, array( &$this, 'display_section' ), 'evd-options' );
		}
		
		$this->get_settings();
		
		foreach ( $this->settings as $id => $setting ) {
			$setting['id'] = $id;
			$this->create_setting( $setting );
		}
		
	}
	
	/**
	* jQuery Tabs
	*
	* @since 1.0
	*/
	public function scripts() {
		
		wp_print_scripts( 'jquery-ui-tabs' );
		
	}
	
	/**
	* Styling for the theme options page
	*
	* @since 1.0
	*/
	public function styles() {
		
		wp_register_style( 'evd-admin', get_bloginfo( 'stylesheet_directory' ) . '/functions/evd_adminPanel.css' );
		wp_enqueue_style( 'evd-admin' );
		
	}
	
	/**
	* Validate settings
	*
	* @since 1.0
	*/
	public function validate_settings( $input ) {
		
		if ( ! isset( $input['reset_theme'] ) ) {
			$options = get_option( 'evd_options' );
			
			foreach ( $this->checkboxes as $id ) {
				if ( isset( $options[$id] ) && ! isset( $input[$id] ) )
					unset( $options[$id] );
			}
			
			return $input;
		}
		return false;
		
	}
	
}