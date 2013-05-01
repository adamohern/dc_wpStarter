<?php 

// e($string) defined in require/dc_utilities.php
e("<!DOCTYPE html>\n");

// c($comment,$mode) defined in require/dc_utilities.php
c('dc_option(\'debugMode\') is enabled. Enjoy.',1);
c('Begin header.php',2); 

c('static code',1);
e('<head profile="http://gmpg.org/xfn/11">'); 

c('bloginfo(\'charset\')',1); 
e('<meta charset="'.get_bloginfo('charset').'" />'); 

c('force latest IE rendering',1); 
e('<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />'); 

br(3);

$title = dc_archiveTitle();
c('dc_archiveTitle()',1);
e('<title>'.$title.'</title>'); 

c('dc_archiveTitle()',1); 
e('<meta name="DC.title" content="'.$title.'" />'); 

br();

c('if present, dc_option(\'indexSEODescription\'), else get_the_excerpt())',1);

if(is_archive()||is_front_page()) { 
    $description = (strip_tags(dc_option('indexSEODescription'))); $descriptionType = 'dc_indexSEODescription'; 
} else { 
    $description = strip_tags(get_the_excerpt()); $descriptionType = 'get_the_excerpt()'; 
} 

c('description type: '.$descriptionType,1); 
e('<meta name="description" content="'.$description.'" />'); 

c('description type: '.$descriptionType,1); 
e('<meta name="DC.subject" content="'.$description.'" />'); 

br();

$dc_authorName = dc_option('authorName');

c('dc_option(\'authorName\')',1); 
e('<meta content="'.$dc_authorName.'" />'); 

c('dc_option(\'authorName\').\' \'.date(\'Y\')',1); 
e('<meta name="Copyright" content="Copyright '.$dc_authorName.' '.date('Y').'. All rights reserved." />'); 

br();

c('static code',1); 
e('<meta name="DC.creator" content="destructive-creative" />');

$googleVerification = dc_option('googleVerification');
if($googleVerification) c('dc_googleVerification:');echo ($googleVerification)."\n"; 

$googleAnalytics = dc_option('googleAnalytics');
if($googleAnalytics) c('dc_googleAnalytics');echo($googleAnalytics); 

br(3);

c('traditional 16x16 favicon - dc_option(\'favicon\')',1);
e('<link href="'.dc_option('favicon').'" rel="shortcut icon" />');

c('iOS\'s Web Clip, 114x114, name it \'apple-touch-icon-precomposed.png\', no transparency - dc_option(\'appleicon\')',1);
e('<link href="'.dc_option('appleicon').'" rel="apple-touch-icon" />');

c('CSS: screen, mobile & print are all in the same file - dc_option(\'stylesheet_url\')',1);
e('<link href="'.get_bloginfo('stylesheet_url').'" rel="stylesheet" />');

br(3);

include 'style_header.php';

br(3);

c('set default <script> tag type (NOTE: most js is in footer.php)',1);
e('<meta http-equiv="content-script-type" content="text/javascript" />');

c('bloginfo(\'pingback_url\')',1);
e('<link href="'.get_bloginfo('pingback_url').'" rel="pingback" />');

c('if we have js, hide the body until everything is loaded',1);
e('<script type="text/javascript">document.write(\'<style>#everything { display:none }</style>\');</script>');

br(3);

c('Begin wp_head()',1); 
wp_head(); br();
c('End wp_head()');

br(3);

c('dc_option(\'customJS\')',1);
e('<script type="text/javascript">'."\n".dc_option('customJS')."\n".'</script>');

e("</head>");

br(3);

c('get_body_class() wordpress function',1);
e('<body class="'.implode(' ',get_body_class(dc_option('titleSlug'))).'">');

c('static code',1);
e('<div id="everything">');

$dc_headerSidebar = dc_option('headerSidebar');
if ($dc_headerSidebar!='hidden' && dc_option('sidebars-Header_Widgets')) { 
    
    br(3);
    
	c('$dc_headerSidebar!=\'hidden\' && dc_option(\'sidebars-Header_Widgets\') tested true',1);
	c('Begin dc_headerSidebar',1);
	
	if (dc_is_active_sidebar('Header_Widgets')) {
		c('dc_is_active_sidebar(\'Header_Widgets\') tested true',1);
		
		if($dc_headerSidebar=='animate') $class = 'class="animate"';
		echo "<header id=\"headerWrap\"$class>\n".'<div id="headerContent" class="h-lists clearfix">';
		
		c('Begin dynamic_sidebar(\'Header_Widgets\')',1);
		if (function_exists('dynamic_sidebar') && dynamic_sidebar('Header_Widgets')) {}
		
		echo '</div>'; c('/#headerContent');
		echo '</header>';
	} else { 
		c('Add widgets to activate header'); 
	}
    
	br(3);
} 

echo '<div id="dc-page-wrap" class="clearfix">'; br();
 
if(is_home()){ 
    
    c('Begin dc_auxSidebar(\'Banner_Home\')',1); dc_auxSidebar('Banner_Home'); 
    
} else if(is_archive()){ 
    
    c('Begin dc_auxSidebar(\'Banner_Archive\')',1); dc_auxSidebar('Banner_Archive'); 
    
} 

$fullWidth = get_post_meta($post->ID, 'fullWidth'); 

if($fullWidth[0]=='true' && is_singular()) $fullWidth=true; 
else $fullWidth=false;

if($fullWidth) c('get_post_meta($post->ID, \'fullWidth\') tested true; adding fullWidth class to #dc-content',1);
else c('get_post_meta($post->ID, \'fullWidth\') tested false; NOT adding fullWidth class to #dc-content',1);

$class='clearfix';

if($fullWidth) $class.=' fullWidth';

e('<div id="dc-content" class="'.$class.'">');
e('<div id="contentBody">');

$dc_noscriptMessage = dc_option('noscriptMessage');

if($dc_noscriptMessage) {
    
    c('dc_option(\'noscriptMessage\') tested true',1);
    e('<noscript><div id="noscriptMessage" class="alert"><h2>Javascript disabled.</h2><p>'.$dc_noscriptMessage.'</p></div></noscript>');
    
}

c('End header.php',3); 
?>