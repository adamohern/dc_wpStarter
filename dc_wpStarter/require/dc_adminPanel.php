<?php 

// Based on Alison Barrett's excellent tutorial:
// http://alisothegeek.com/2011/01/wordpress-settings-api-tutorial-2/

// Usage example: $favicon = get_option('favicon');

// instantiated in dc_themeOptions.php

function dc_option( $option ) {
	$options = get_option( 'dc_options' );
	if ( isset( $options[$option] ) )
		return $options[$option];
	else
		return false;
}

class dc_theme_options {
    
	private $sections;
	private $checkboxes;
	private $settings;
    private $pageTitle;
    private $menuTitle;
	
    
    
    
    /*
    // runs on instantiation
    */
	public function __construct( $pt='Theme Options', $mt='Theme Options' ) {
		
		$this->checkboxes = array();
		$this->settings = array();
		$this->get_settings();
        
        $this->pageTitle = $pt;
        $this->menuTitle = $mt;
		
        $this->sections['reset'] = 'Reset to Defaults';
        
		add_action( 'admin_menu', array( &$this, 'add_pages' ) );
		add_action( 'admin_init', array( &$this, 'register_settings' ) );
        		
		if ( ! get_option( 'dc_options' ) )
			$this->initialize_settings();
		
	}
    
	
    
    
    /*
    // method for adding sections
    */
    public function add_section($title,$slug){
        $this->sections[$slug] = $title;
    }
    
    
    
    
    /*
    // method for adding settings
    */
    public function set($handle,$args=array()){
        $this->settings[$handle] = $args;   
    }
    
    
    
    
	/*
    // add options page to admin UI
    */
	public function add_pages() {
        
		$admin_page = add_theme_page( $this->pageTitle, $this->menuTitle, 'manage_options', 'dc-options', array( &$this, 'display_page' ) );
		
		add_action( 'admin_print_scripts-' . $admin_page, array( &$this, 'scripts' ) );
		add_action( 'admin_print_styles-' . $admin_page, array( &$this, 'styles' ) );
		
	}
    
    
    
	
	/*
    // create settings field
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
		
		add_settings_field( $id, $title, array( $this, 'display_setting' ), 'dc-options', $section, $field_args );
	}
    
    
    
	
	/*
    // display options page
    */
	public function display_page() {
        
        // loads the Ace js code editor
		add_action('admin_enqueue_scripts','enqueue_ace');
		
		echo '<div class="wrap">'."\n".'<div class="icon32" id="icon-options-general"></div>'."\n".'<h2>' . $this->pageTitle . '</h2>';
	
		if ( isset( $_GET['settings-updated'] ) && $_GET['settings-updated'] == true )
			echo '<div class="updated fade"><p>' . __( 'Theme options updated.' ) . '</p></div>';
		
		echo '<form action="options.php" method="post">';
	
		settings_fields( 'dc_options' );
		echo '<div class="ui-tabs">
			<ul class="ui-tabs-nav">';
		
		foreach ( $this->sections as $section_slug => $section )
			echo '<li><a href="#' . $section_slug . '">' . $section . '</a></li>';
		
		echo '</ul>';
		do_settings_sections( $_GET['page'] );
		
		echo '</div>
		<p class="submit"><input name="Submit" type="submit" class="button-primary" value="Save Changes" /></p>
		
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
	
    
    
    
    
    /*
    // HTML snippets for display_setting() functions below
    */
    private function description($desc){
        if ( $desc != '' )
            echo '<br /><span class="description">' . $desc . '</span>';
    }
    
    private function acebox($options,$id,$height,$mode,$field_class,$std){
        echo '<div id="'.$id.'_editor" style="border:1px solid #eee;width:95%;height:'.$height.'px;position:relative;display:block;">'.esc_attr($options[$id]).'</div>'; br();
        echo '<script>'."\n";
        echo 'var '.$id.' = ace.edit("'.$id.'_editor"); '.$id.'.setTheme("ace/theme/textmate"); '.$id.'.getSession().setMode("ace/mode/'.$mode.'");'."\n";
        echo $id.'.getSession().on(\'change\', function(e) { document.getElementById(\''.$id.'\').value = '.$id.'.getSession().getValue(); });'."\n";
        echo '</script>'."\n";
        echo '<textarea style="display:none;" class="' . $field_class . '" id="' . $id . '" name="dc_options[' . $id . ']" placeholder="' . $std . '" rows="5" cols="30">' . wp_htmledit_pre( $options[$id] ) . '</textarea>';
    }
    
    
    
    
	
	/*
    // HTML output for fields
    */
	public function display_setting( $args = array() ) {
		
		extract( $args );
		
		$options = get_option( 'dc_options' );
		
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
				
				echo '<input class="checkbox' . $field_class . '" type="checkbox" id="' . $id . '" name="dc_options[' . $id . ']" value="1" ' . checked( $options[$id], 1, false ) . ' /> <label for="' . $id . '">' . $desc . '</label>';
				
				br(1);
				break;
			
			case 'select':
				echo '<select class="select' . $field_class . '" name="dc_options[' . $id . ']">';
				
				foreach ( $choices as $value => $label )
					echo '<option value="' . esc_attr( $value ) . '"' . selected( $options[$id], $value, false ) . '>' . $label . '</option>';
				
				echo '</select>';
				
				$this->description($desc);
				
				br(1);
				break;
			
			case 'radio':
				$i = 0;
				foreach ( $choices as $value => $label ) {
					echo '<input class="radio' . $field_class . '" type="radio" name="dc_options[' . $id . ']" id="' . $id . $i . '" value="' . esc_attr( $value ) . '" ' . checked( $options[$id], $value, false ) . '> <label for="' . $id . $i . '">' . $label . '</label>';
					if ( $i < count( $options ) - 1 )
						echo '<br />';
					$i++;
				}
				
				$this->description($desc);
				
				br(1);
				break;
			
			case 'textarea':
				echo '<textarea class="' . $field_class . '" id="' . $id . '" name="dc_options[' . $id . ']" placeholder="' . $std . '" rows="5" cols="30">' . wp_htmledit_pre( $options[$id] ) . '</textarea>';
				
				$this->description($desc);
				
				br(1);
				break;
			
			case 'password':
				echo '<input class="regular-text' . $field_class . '" type="password" id="' . $id . '" name="dc_options[' . $id . ']" value="' . esc_attr( $options[$id] ) . '" />';
				
				$this->description($desc);
				
				br(1);
				break;
			
			case 'text':
			default:
		 		echo '<input class="regular-text' . $field_class . '" type="text" id="' . $id . '" name="dc_options[' . $id . ']" placeholder="' . $std . '" value="' . esc_attr( $options[$id] ) . '" />';
				
		 		$this->description($desc);
		 		
				br(1);
		 		break;
				
			case 'color':
			default:
		 		echo '<div id="'.$id.'_swatch" class="swatch" style="width:20px; height:20px; float:left; margin-top:3px; margin-right:10px; background-color:'.esc_attr($options[$id]).';"></div><input class="regular-text' . $field_class . '" type="text" id="' . $id . '" name="dc_options[' . $id . ']" placeholder="' . $std . '" value="' . esc_attr( $options[$id] ) . '" />';
				echo '<script>document.getElementById("'.$id.'").onchange=function(){ document.getElementById("'.$id.'_swatch").style.backgroundColor=this.value; } ;</script>';

		 		$this->description($desc);
		 		
				br(1);
		 		break;
				
			case 'css':
			default:
				$this->acebox($options,$id,200,'css',$field_class,$std);
		 		$this->description($desc);
					
		 		br(1);
		 		break;
                 
    		case 'css_big':
			default:
				$this->acebox($options,$id,600,'css',$field_class,$std);
		 		$this->description($desc);
					
		 		br(1);
		 		break;
				
			case 'php':
			default:
		 		$this->acebox($options,$id,200,'php',$field_class,$std);
                $this->description($desc);
		 		
				br(1);
		 		break;
                 
    		case 'php_big':
			default:
		 		$this->acebox($options,$id,600,'php',$field_class,$std);
                $this->description($desc);
		 		
				br(1);
		 		break;
				
			case 'html':
			default:
		 		$this->acebox($options,$id,200,'html',$field_class,$std);
		 		$this->description($desc);
		 		
				br(1);
		 		break;
            
            case 'js':
			default:
		 		$this->acebox($options,$id,200,'javascript',$field_class,$std);
                $this->description($desc);
            
				br(1);
		 		break;
                 
            case 'js_big':
			default:
		 		$this->acebox($options,$id,600,'javascript',$field_class,$std);
                $this->description($desc);
            
				br(1);
		 		break;
		 	
		}
		
	}
	
	
    
    
    
    
	/*
    // settings and defaults (mostly defined in dc_themeOptions.php)
    */
	public function get_settings() {
        
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
    
    
    
    
	
	/*
    // initialize settings to their default values
    */
	public function initialize_settings() {
		
		$default_settings = array();
		foreach ( $this->settings as $id => $setting ) {
			if ( $setting['type'] != 'heading' )
				$default_settings[$id] = $setting['std'];
		}
		
		update_option( 'dc_options', $default_settings );
		
	}
    
    
    
    
	
	/*
	// register settings
	*/
	public function register_settings() {
		
		register_setting( 'dc_options', 'dc_options', array ( &$this, 'validate_settings' ) );
		
		foreach ( $this->sections as $slug => $title ) {
			if ( $slug == 'about' )
				add_settings_section( $slug, $title, array( &$this, 'display_about_section' ), 'dc-options' );
			else
				add_settings_section( $slug, $title, array( &$this, 'display_section' ), 'dc-options' );
		}
		
		$this->get_settings();
		
		foreach ( $this->settings as $id => $setting ) {
			$setting['id'] = $id;
			$this->create_setting( $setting );
		}
		
	}
	
    
    
    
    
	/*
	// jQuery Tabs
	*/
	public function scripts() {
		
		wp_print_scripts( 'jquery-ui-tabs' );
		
	}
	
    
    
    
    
	/*
	// Styling for the theme options page
	*/
	public function styles() {
		
		wp_register_style( 'dc-admin', get_bloginfo( 'stylesheet_directory' ) . '/require/dc_adminPanel.css' );
		wp_enqueue_style( 'dc-admin' );
		
	}
	
    
    
    
    
	/*
	// Validate settings
	*/
	public function validate_settings( $input ) {
		
		if ( ! isset( $input['reset_theme'] ) ) {
			$options = get_option( 'dc_options' );
			
			foreach ( $this->checkboxes as $id ) {
				if ( isset( $options[$id] ) && ! isset( $input[$id] ) )
					unset( $options[$id] );
			}
			
			return $input;
		}
		return false;
		
	}
	
}