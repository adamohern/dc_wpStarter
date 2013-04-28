<?php
/*
Plugin Name: evd_Dressup
Plugin URI: http://evd1.tv
Description: Adds most of the yummy goodness to EvD sites over and above the EvD_HTML5_Reset theme. It is exclusively designed for use with said theme, and will not work properly with any other.
Version: 120401
Author: A-Dawg
License: Ain't nobody but EvD can has it.
*/

// WooCommerce hacks
add_action('wp', create_function("", "if (is_archive(array('product'))) remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10);") );
add_action('wp', create_function("", "if (is_singular(array('product'))) remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10);") );
remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10);

function login_be_gone() {
	echo '<style type="text/css">
		#nav {display:none;}
		#loginform {opacity:.2;}
		#getoffmylawn {margin-bottom:20px;padding:20px;-webkit-border-radius:5px;background-color:#FFE0EA;border:1px solid #d74877;margin-left:8px;}
		#getoffmylawn p {text-align:center;margin-bottom:15px;}
	</style>';
}
add_action('login_head','login_be_gone',01);

function get_off_my_lawn($message) {
	return '
	<div id="getoffmylawn">
	<h3 style="text-align:center;margin-bottom:20px;font-size:24px;text-transform:uppercase;">We\'ve moved!</h3>
	<p style="font-size:18px;"><a href="http://members.evd1.tv/login">members.evd1.tv/login</a></p>
	<p>This login screen is for <em>admins only</em>.</p>
	<p>Please update your bookmarks to the above URL. Mahalo!</p>
	</div>
	';
}
add_filter('login_message','get_off_my_lawn',01);

// Adds a simple loginout using the loginout_redirect() function (for amember integration)
//class evd_LoginOut extends WP_Widget {
//	function __construct() {
//		parent::WP_Widget( 
//            'evd_LoginOut', // Base ID
//            'evd_LoginOut', // Name
//            array( 'description' => 'Simple Login/Logout button for amember.' ) );
//	}
//	function widget() {
//		loginout_redirect();
//	}
//}
//register_widget("evd_LoginOut");


$flowplayer_ids = array();

// make all galleries 4 columns
function gallery_columns($content){
	$columns = 4;
	$pattern = array(
		'/(\[gallery(.*?)columns="([0-9])"(.*?)\])/ie',
		'/(\[gallery\])/ie',
		'/(\[gallery(.*?)\])/ie'
	);
	$replace = 'stripslashes(strstr("\1", "columns=\"$columns\"") ? "\1" : "[gallery \2 \4 columns=\"$columns\"]")';
	$content = preg_replace($pattern, $replace, $content);
	
	$thumbsize = 'evd_thumbnail';
	$pattern = array(
		'/(\[gallery(.*?)size="(.*?)"(.*?)\])/ie',
		'/(\[gallery\])/ie',
		'/(\[gallery(.*?)\])/ie'
	);
	$replace = 'stripslashes(strstr("\1", "size=\"$thumbsize\"") ? "\1" : "[gallery \2 \4 size=\"$thumbsize\"]")';
	$content = preg_replace($pattern, $replace, $content);
	
	return $content;
}
add_filter('the_content', 'gallery_columns');

function cj_videoTagTitle($title='') {
	if(has_post_format('video')) {$title = '<div class="video">'.$title.'</div>';}
	return $title;
}
if(strpos(get_bloginfo('siteurl'),'cadjunkie')!==false){add_filter('evd_postTitle','cj_videoTagTitle');}

function cj_thumbOverlay($overlay){
	if(has_tag('pro')){ $overlay .= '<div class="cj_thumbOverlay pro" style="pointer-events: none;"></div>'; }
	else if(has_tag('premium')){ $overlay .= '<div class="cj_thumbOverlay premium" style="pointer-events: none;"></div>'; }
	else if(has_category('tutorials')){ $overlay .= '<div class="cj_thumbOverlay free" style="pointer-events: none;"></div>'; }
	return $overlay;
}
add_filter('evd_thumbOverlay','cj_thumbOverlay');

// display the skill level next to the title of a post
function cj_skillTag(){
	$tag = "";
	if(has_tag('level 3')){ $tag = '<div class="cj_skillTag"><img src="'.WP_PLUGIN_URL.'/evd_Dressup/images/evd_tags-level3.png" class="level3" /></div>'; }
	else if(has_tag('level 2')){ $tag = '<div class="cj_skillTag"><img src="'.WP_PLUGIN_URL.'/evd_Dressup/images/evd_tags-level2.png" class="level2" /></div>'; }
	else if(has_tag('level 1')){ $tag = '<div class="cj_skillTag"><img src="'.WP_PLUGIN_URL.'/evd_Dressup/images/evd_tags-level1.png" class="level1" /></div>'; }
	else if(has_category('questions')){ $tag = '<div class="cj_skillTag"><img src="'.WP_PLUGIN_URL.'/evd_Dressup/images/evd_tags-qna.png" class="questions" /></div>'; }
	else if(has_category('workinprogress')){ $tag = '<div class="cj_skillTag"><img src="'.WP_PLUGIN_URL.'/evd_Dressup/images/evd_tags-wip.png" class="workinprogress" /></div>'; }
	else if(has_category('discussion')){ $tag = '<div class="cj_skillTag"><img src="'.WP_PLUGIN_URL.'/evd_Dressup/images/evd_tags-discussion.png" class="discussion" /></div>'; }
	else { $tag = '<div class="cj_skillTag"><img src="'.WP_PLUGIN_URL.'/evd_Dressup/images/evd_tags-blank.png" class="blank" /></div>'; }
	return $tag;
}

function cj_accessTag() {
	$tag = "";
	if(has_tag('pro')){ $tag = '<div class="cj_accessTag"><img src="'.WP_PLUGIN_URL.'/evd_Dressup/images/evd_tags-pro.png" class="pro" /></div>'; }
	else if(has_tag('premium')){ $tag = '<div class="cj_accessTag"><img src="'.WP_PLUGIN_URL.'/evd_Dressup/images/evd_tags-premium.png" class="premium" /></div>'; }
	else if(has_category('tutorials')){ $tag = '<div class="cj_accessTag"><img src="'.WP_PLUGIN_URL.'/evd_Dressup/images/evd_tags-free.png" class="free" /></div>'; }
	else { $tag = '<div class="cj_skillTag"><img src="'.WP_PLUGIN_URL.'/evd_Dressup/images/evd_tags-free.png" class="free" /></div>'; }
	return $tag;
}

function cj_videoTag() {
	$tag = "";
	if(has_tag('video')){ $tag = '<div class="cj_videoTag"><img src="'.WP_PLUGIN_URL.'/evd_Dressup/images/evd_tags-video-blue.png" /></div>'; }
	return $tag;
}

//Styles for Syntax Highlighter plugin in head section  
function evd_Dressup()  
{ ?>
<!-- evd_Dressup patches -->
<link rel="stylesheet" type="text/css" href="<?php echo WP_PLUGIN_URL; ?>/evd_Dressup/evd_Dressup-fonts.css" media="screen" />  
<link rel="stylesheet" type="text/css" href="<?php echo WP_PLUGIN_URL; ?>/evd_Dressup/evd_Dressup.css" media="screen" />  
<script type="text/javascript" src="<?php echo WP_PLUGIN_URL; ?>/evd_Dressup/flowplayer/flowplayer-3.2.6.min.js"></script>
<style>
.EngineerVsDesigner #menu-item-637 a,
.cadjunkie #menu-item-5517 a {color:<?php echo (evd_option('evd_primaryColor')); ?>;}
.EngineerVsDesigner #menu-item-637 a:hover,
.cadjunkie #menu-item-5517 a:hover {color:<?php echo (evd_option('evd_primaryColorFaded')); ?>;}
</style>
<!-- /evd_Dressup patches -->
<?php }
add_action('wp_head', 'evd_Dressup', 1);


// enable [evdAd] shortcode
function evdAd($atts, $content="") {
extract(shortcode_atts(array(
		'url' => '#'
	), $atts));
$code = '
<p class="evdAd">
	<a title="EvD Sponsor" target="_blank" href="'.$url.'"><img src="'.$content.'"/></a>
</p>
';
return $code;
}
add_shortcode('evdAd', 'evdAd');


// enable [evdDownload] shortcode
function evdDownload($atts, $content="") {
extract(shortcode_atts(array(
		'filesize' => '',
		'hasitunes' => 'true'
	), $atts));
$code = '
<div class="evdDownload">
	<h3>Download</h3>
	<a title="EvD Download" href="'.$content.'">'.basename($content).'</a> <span class="filesize">('.$filesize.')</span>';
	
	if($hasitunes!='false'){ $code .= '<h3 style="margin-top:1em;">iTunes</h3>
	<a title="EvD on iTunes" href="http://itunes.apple.com/us/podcast/engineervsdesigner/id454034646">EvD on iTunes</a>'; }

$code .= '</div>
';
return $code;
}
add_shortcode('evdDownload', 'evdDownload');


// enable [evdSocial] shortcode
function evdSocial($atts, $content="") {
$code = '
<p class="evdSocial">
'.$content.'
</p>
';
return $code;
}
add_shortcode('evdSocial', 'evdSocial');


// enable [evdSocial] shortcode
function evd_iTunes($atts, $content="") {
$code = '
<div class="evd_iTunes">
<h3><a href="http://itunes.apple.com/us/podcast/engineervsdesigner/id454034646">EvD on iTunes</a></h3>'.$content.'
</div>
';
return $code;
}
add_shortcode('evd_iTunes', 'evd_iTunes');


// enable [evdPlayer] shortcode
function evdPlayer($atts) {
global $flowplayer_ids;
extract(shortcode_atts(array(
		'thumb' => WP_PLUGIN_URL.'/evd_Dressup/images/evd_defaultVideoThumb.png',
		'video' => '#'
	), $atts));
$id = basename($video); $id = preg_replace('[\W]', "", $id);

if(strstr($thumb,'defaultVideoThumb.png')){
	if ( has_post_thumbnail() ) {
		$image_id = get_post_thumbnail_id();  
		$image_url = wp_get_attachment_image_src($image_id,'evd_huge');  
		$image_url = $image_url[0]; 
		$thumb = $image_url;
	}
} 

$code = '
<a  
	 href="'.$video.'"
	 style="display:block;"  
	 class="evdPlayer"
	 id="'.$id.'"><img src="'.$thumb.'">
</a> 
';
$flowplayer_ids[] = $id;

return $code;
}
add_shortcode('evdPlayer', 'evdPlayer');


function evd_flowplayers(){
global $flowplayer_ids;
foreach ($flowplayer_ids as $id){
	echo '
<script>flowplayer("'.$id.'", "'.WP_PLUGIN_URL.'/evd_Dressup/flowplayer/flowplayer-3.2.7.swf");</script>'."\n";
}
}
add_action('wp_footer','evd_flowplayers');


// enable [evdButton] shortcode
function evdButton($atts, $content="") {
$id = basename($content); $id = preg_replace('[\W]', "", $id);
$html = '';
$html .= '<div id="'.$id.'" class="evdButton middle"'; if($atts['width'])$html .= 'style="width:'.$atts['width'].';"'; $html .= '>';
$html .= '<div class="evdButton left"></div>';
$html .= '<a href="'; if($atts['url']) $html .= $atts['url']; else $html .= '#'; $html .= '"'; if($atts['color']) $html .= ' style="color:'.$atts['color'].';"'; $html .= '>'.$content.'</a>'."\n";
$html .= '<div class="evdButton right"></div></div>'."\n";
return $html;
}
add_shortcode('evdButton', 'evdButton');

function evd_Dressup_js() {
		wp_register_script('evd_Dressup',
			 WP_PLUGIN_URL . '/evd_Dressup/evd_Dressup_120401.js'
			 );
		wp_enqueue_script('evd_Dressup');
}
add_action('wp_enqueue_scripts', 'evd_Dressup_js');

?>
