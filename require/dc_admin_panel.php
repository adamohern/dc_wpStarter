<?php 
// instantiated in dc_themeOptions.php


// class for constructing settings pages
class dc_theme_options {
    
	private $sections, $checkboxes, $pageTitle, $menuTitle, $handle, $settings, $options;
    
    
	public function __construct( $pt='Theme Options', $mt='Theme Options', $h='dc_options' ) {
		        
		$this->checkboxes = array();
		$this->settings = array();
		$this->get_settings();
        
        $this->pageTitle = $pt;
        $this->menuTitle = $mt;
        $this->handle = $h;
        
		add_action( 'admin_menu', array( &$this, 'add_pages' ) );
		add_action( 'admin_init', array( &$this, 'register_settings' ) );
		add_action( 'after_setup_theme', array( &$this, 'initialize_settings' ) );
		
        $this -> add_section('Reset "'.$this->pageTitle.'" to Defaults','reset');

	}
	
	    
    // method for adding sections
    public function add_section($title,$slug){
        $this->sections[$slug] = $title;
    }
    
    
    // method for adding settings
    public function set($setting,$args=array()){
        $this->settings[$setting] = $args;
    }
    
    // method for grabbing a setting
    public function get($h){
        $options = get_option($this->handle);
        return $options[$h];
    }
    
    // method for grabbing all settings
    public function get_all() {
        return get_option($this->handle);
    }
	
    
	public function add_pages() {
        
		$admin_page = add_theme_page( 
			$this->pageTitle, 
			$this->menuTitle, 
			'manage_options', 
			$this->handle, 
			array( &$this, 'display_page' ) 
		);
		
	}
    
    
	public function register_settings() {
		
		register_setting( $this->handle, $this->handle, array ( &$this, 'validate_settings' ) );
		
		foreach ( $this->sections as $slug => $title ) {
            add_settings_section( $slug, $title, array( &$this, 'display_section' ), $this->handle );
		}
		
		$this->get_settings();
		
		foreach ( $this->settings as $id => $setting ) {
			$setting['id'] = $id;
			$this->create_setting( $setting );
		}
		
	}
    

	public function initialize_settings() {
	
		if (!get_option($this->handle)) { 
		
			$default_settings = array();
			
			foreach ( $this->settings as $id => $setting ) {
				if ( $setting['type'] != 'heading' )
					$default_settings[$id] = $setting['std'];
			}
		
			update_option( $this->handle, $default_settings );
			
		}
		
	}
    

	public function create_setting( $args = array() ) {
        		        
		$defaults = array(
			'id'      => 'default_field',
			'title'   => 'Default Field',
			'desc'    => 'This is a default description.',
			'std'     => '',
			'type'    => 'text',
			'section' => 'general',
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
        
		add_settings_field( $id, $title, array( $this, 'display_setting' ), $this->handle, $section, $field_args );
	
    }
    
    
	public function display_page() {
		
		e('<div class="wrap">');
        e('<div class="icon32" id="icon-options-general"></div>');
        e('<h2>' . $this->pageTitle . '</h2>');
	
		if ( isset( $_GET['settings-updated'] ) && $_GET['settings-updated'] == true )
            e('<div class="updated fade"><p>' . 'Theme options updated.' . '</p></div>');
		
		e('<form action="options.php" method="post">');
	
		settings_fields($this->handle);
        
		do_settings_sections( $_GET['page'] );
        
		e('<p class="submit"><input name="Submit" type="submit" class="button-primary" value="Save Changes" /></p>');
        
		e('</form>');

        e('</div><!--/#icon-options-general-->');
		
	}
	
    

	public function display_section() {
		//
	}
    
    
    // HTML snippet for display_setting() functions below
    private function description($desc){
        if ( $desc != '' ) e('<br /><span class="description">' . $desc . '</span>');
    }
    
    // HTML snippet for display_setting() functions below
    private function acebox($options,$id,$height,$mode,$field_class,$std){
        e('<div id="'.$id.'_editor" style="border:1px solid #eee;width:95%;height:'.$height.'px;position:relative;display:block;">'.esc_attr($options[$id]).'</div>');
        e('<script>'."\n");
        e('var '.$id.' = ace.edit("'.$id.'_editor"); '.$id.'.setTheme("ace/theme/textmate"); '.$id.'.getSession().setMode("ace/mode/'.$mode.'");');
        e($id.'.getSession().on(\'change\', function(e) { document.getElementById(\''.$id.'\').value = '.$id.'.getSession().getValue(); });');
        e('</script>');
        e('<textarea style="display:none;" class="' . $field_class . '" id="' . $id . '" name="'.$this->handle.'[' . $id . ']" placeholder="' . $std . '" rows="5" cols="30">' . wp_htmledit_pre( $options[$id] ) . '</textarea>');
    }
    

    // HTML output for fields
	public function display_setting( $args = array() ) {
		
		extract( $args );
		
		$options = get_option( $this->handle );
		
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
				
			case 'readme':
				echo $std;
				br(1);
				break;
			
			case 'checkbox':
				
				echo '<input class="checkbox' . $field_class . '" type="checkbox" id="' . $id . '" name="'.$this->handle.'[' . $id . ']" value="1" ' . checked( $options[$id], 1, false ) . ' /> <label for="' . $id . '">' . $desc . '</label>';
				
				br(1);
				break;
			
			case 'select':
				echo '<select class="select' . $field_class . '" name="'.$this->handle.'[' . $id . ']">';
				
				foreach ( $choices as $value => $label )
					echo '<option value="' . esc_attr( $value ) . '"' . selected( $options[$id], $value, false ) . '>' . $label . '</option>';
				
				echo '</select>';
				
				$this->description($desc);
				
				br(1);
				break;
			
			case 'radio':
				$i = 0;
				foreach ( $choices as $value => $label ) {
					echo '<input class="radio' . $field_class . '" type="radio" name="'.$this->handle.'[' . $id . ']" id="' . $id . $i . '" value="' . esc_attr( $value ) . '" ' . checked( $options[$id], $value, false ) . '> <label for="' . $id . $i . '">' . $label . '</label>';
					if ( $i < count( $options ) - 1 )
						echo '<br />';
					$i++;
				}
				
				$this->description($desc);
				
				br(1);
				break;
			
			case 'textarea':
				echo '<textarea style="width:95%" class="' . $field_class . '" id="' . $id . '" name="'.$this->handle.'[' . $id . ']" placeholder="' . $std . '" rows="5" cols="200">' . wp_htmledit_pre( $options[$id] ) . '</textarea>';
				
				$this->description($desc);
				
				br(1);
				break;
			
			case 'password':
				echo '<input class="regular-text' . $field_class . '" type="password" id="' . $id . '" name="'.$this->handle.'[' . $id . ']" value="' . esc_attr( $options[$id] ) . '" />';
				
				$this->description($desc);
				
				br(1);
				break;
			
			case 'text':
			default:
		 		echo '<input class="regular-text' . $field_class . '" style="width:95%;" type="text" id="' . $id . '" name="'.$this->handle.'[' . $id . ']" placeholder="' . $std . '" value="' . esc_attr( $options[$id] ) . '" />';
				
		 		$this->description($desc);
		 		
				br(1);
		 		break;
				
			case 'color':
			default:
		 		echo '<div id="'.$id.'_swatch" class="swatch" style="width:20px; height:20px; float:left; margin-top:3px; margin-right:10px; background-color:'.esc_attr($options[$id]).';"></div><input class="regular-text' . $field_class . '" type="text" id="' . $id . '" name="'.$this->handle.'[' . $id . ']" placeholder="' . $std . '" value="' . esc_attr( $options[$id] ) . '" />';
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
            
			case 'html_big':
			default:
		 		$this->acebox($options,$id,600,'html',$field_class,$std);
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
	
	
    // settings and defaults (mostly defined in dc_themeOptions.php)
	public function get_settings() {
        
        /* Reset
        ===========================================*/
        
        $this -> set('reset_theme', array(
            'section' => 'reset',
            'title'   => 'Reset '.$this->pageTitle,
            'type'    => 'checkbox',
            'std'     => 0,
            'class'   => 'warning', // Custom class for CSS
            'desc'    => 'Check this box and click "Save Changes" below to reset the "'.$this->pageTitle.'" settings to their default values.'
        ));
        
        
        $this->options = get_option( $this->handle );
		
	}


	public function validate_settings( $input ) {
		
		if ( ! isset( $input['reset_theme'] ) ) {
			$options = get_option( $this->handle );
			
			foreach ( $this->checkboxes as $id ) {
				if ( isset( $options[$id] ) && ! isset( $input[$id] ) )
					unset( $options[$id] );
			}
			
			return $input;
		}
		return false;
		
	}
    
}