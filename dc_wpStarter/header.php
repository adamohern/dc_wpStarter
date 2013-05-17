<?php 

// e($string) defined in require/dc_utilities.php
e("<!DOCTYPE html>\n");

// c($comment,$mode) defined in require/dc_utilities.php
c('o(\'debugMode\') is enabled. Enjoy.',1);
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

c('if present, o(\'indexSEODescription\'), else get_the_excerpt())',1);

if(is_archive()||is_front_page()) { 
    $description = (strip_tags(o('indexSEODescription'))); $descriptionType = 'o(indexSEODescription)'; 
} else { 
    $description = strip_tags(get_the_excerpt()); $descriptionType = 'get_the_excerpt()'; 
} 

c('description type: '.$descriptionType,1); 
e('<meta name="description" content="'.$description.'" />'); 

c('description type: '.$descriptionType,1); 
e('<meta name="DC.subject" content="'.$description.'" />'); 

br();

c('o(\'authorName\')',1); 
e('<meta content="'.o('authorName').'" />'); 

c('o(\'authorName\').\' \'.date(\'Y\')',1); 
e('<meta name="Copyright" content="Copyright '.o('authorName').' '.date('Y').'. All rights reserved." />'); 

br();

c('static code',1); 
e('<meta name="DC.creator" content="destructive-creative.com" />');

br(3);

c('traditional 16x16 favicon - o(\'favicon\')',1);
e('<link href="'.o('favicon').'" rel="shortcut icon" />');

c('iOS\'s Web Clip, 114x114, name it \'apple-touch-icon-precomposed.png\', no transparency - o(\'appleicon\')',1);
e('<link href="'.o('appleicon').'" rel="apple-touch-icon" />');

c('CSS: screen, mobile & print are all in the same file - o(\'stylesheet_url\')',1);
e('<link href="'.get_bloginfo('stylesheet_url').'" rel="stylesheet" />');

br(3);

e("apply_filters('dc_cssOverrides',o('cssOverrides'))",1);
e('<style type="text/css">');
e(apply_filters('dc_cssOverrides',o('cssOverrides')));
e('</style>');

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

e('<script type="text/javascript">');
c("apply_filters('dc_customJS',o('customJS'))",1);
e(apply_filters('dc_customJS',o('customJS')));
e('</script>');

e("</head>");

br(3);

c("class = implode(' ',get_body_class(o('titleSlug')))",1);
e('<body class="'.implode(' ',get_body_class(o('titleSlug'))).'">');

c('static code',1);
e('<div id="everything">');


e('<header id="headerWrap" class="clearfix">');
	c("dynamic_sidebar('Header_Widgets')",1);
	dc_sidebar('Header_Widgets');
e('</header>');

    
echo '<div id="dc-page-wrap" class="clearfix">'; br();
 
if(is_home()){ 
    
    c('Begin dc_sidebar(\'Banner_Home\')',1); dc_sidebar('Banner_Home'); 
    
} else if(is_archive()){ 
    
    c('Begin dc_sidebar(\'Banner_Archive\')',1); dc_sidebar('Banner_Archive'); 
    
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

$dc_noscriptMessage = o('noscriptMessage');

if($dc_noscriptMessage) {
    
    c('o(\'noscriptMessage\') tested true',1);
    e('<noscript><div id="noscriptMessage" class="alert"><h2>Javascript disabled.</h2><p>'.$dc_noscriptMessage.'</p></div></noscript>');
    
}

c('End header.php',3); 
?>