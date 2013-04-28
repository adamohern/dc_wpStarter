<?php 
echo "<!DOCTYPE html>\n";

// c($comment,$mode) defined in functions.php
c('evd_option(\'evd_debugMode\') is enabled. Enjoy.',1);
c('Begin header.php',2); 

echo '<head>';

// t($atts,$tags) defined in functions.php
// t(array('tag'=>'','c'=>''),array(''=>''));
t(array('tag'=>'head','c'=>'static code'),array('profile'=>'http://gmpg.org/xfn/11'));
t(array('tag'=>'meta','c'=>'bloginfo(\'charset\')'),array('charset'=>get_bloginfo('charset')));
t(array('tag'=>'meta','c'=>'force latest IE rendering'),array('http-equiv'=>'X-UA-Compatible','content'=>'IE=edge,chrome=1'));

c('Begin SEO',2); 

$title = evd_archiveTitle();
t(array('tag'=>'title','c'=>'evd_archiveTitle()','content'=>$title,'wrap'=>true));
t(array('tag'=>'meta','c'=>'evd_archiveTitle()'),array('name'=>'DC.title','content'=>$title));

c('if present, evd_option(\'evd_indexSEODescription\'), else get_the_excerpt()):',1);
if(is_archive()||is_front_page()) { $description = (strip_tags(evd_option('evd_indexSEODescription'))); $descriptionType = 'evd_indexSEODescription'; 
} else { $description = strip_tags(get_the_excerpt()); $descriptionType = 'get_the_excerpt()'; } 
t(array('tag'=>'meta','c'=>'description type: '.$descriptionType),array('name'=>'description','content'=>$description));
t(array('tag'=>'meta','c'=>'description type: '.$descriptionType),array('name'=>'DC.subject','content'=>$description));

$evd_authorName = evd_option('evd_authorName');
t(array('tag'=>'meta','c'=>'evd_option(\'evd_authorName\'):'),array('content'=>$evd_authorName));
t(array('tag'=>'meta','c'=>'evd_option(\'evd_authorName\').\' \'.date(\'Y\'):'),array('name'=>'Copyright','content'=>"Copyright $evd_authorName ".date('Y').". All Rights Reserved."));

t(array('tag'=>'meta','c'=>'static code:'),array('name'=>'DC.creator','content'=>'EvD Media'));

$googleVerification = evd_option('evd_googleVerification');
if($googleVerification) c('evd_googleVerification:');echo ($googleVerification)."\n"; 

$googleAnalytics = evd_option('evd_googleAnalytics');
if($googleAnalytics) c('evd_googleAnalytics');echo($googleAnalytics)."\n"; 

c('End SEO',3); 

c('traditional 16x16 favicon');
t(array('tag'=>'link','c'=>'evd_option(\'evd_favicon\'):'),array('href'=>evd_option('evd_favicon'),'rel'=>'shortcut icon'));

c('iOS\'s Web Clip, 114x114, name it \'apple-touch-icon-precomposed.png\', no transparency',1);
t(array('tag'=>'link','c'=>'evd_option(\'evd_appleicon\'):'),array('rel'=>'apple-touch-icon','href'=>evd_option('evd_appleicon')));

c('CSS: screen, mobile & print are all in the same file',1);
t(array('tag'=>'link','c'=>'evd_option(\'stylesheet_url\'):'),array('rel'=>'stylesheet','href'=>get_bloginfo('stylesheet_url')));

c('PHP CSS, customize in theme options (style_header.php):',2); 
include 'style_header.php';
c('End style_header.php',3); 

c('NOTE: most js is at the bottom of the page');
t(array('tag'=>'meta','c'=>'set default <script> tag type'),array('http-equiv'=>'content-script-type','content'=>'text/javascript')); 
t(array('tag'=>'link','c'=>'bloginfo(\'pingback_url\')'),array('rel'=>'pingback','href'=>get_bloginfo('pingback_url')));

t(
	array(
		'tag'=>'script',
		'c'=>'if we have js, hide the body until everything is loaded',
		'wrap'=>true,
		'content'=>"document.write('<style>#everything { display:none }</style>');"),
	array('type'=>'text/javascript')
);


c('Begin wp_head()',2); 
wp_head();
c('End wp_head()',3); 

t(
    array(
        'tag'=>'script',
		'c'=>'evd_option(\'evd_customJS\')',
		'wrap'=>true,
		'content'=>"\n".evd_option('evd_customJS')."\n"),
	array('type'=>'text/javascript')
);

echo "</head>\n\n<body "; body_class(evd_option('evd_titleSlug')); echo ">\n<div id=\"everything\">";

$evd_headerSidebar = evd_option('evd_headerSidebar');
if ($evd_headerSidebar!='hidden' && evd_option('evd_sidebars-Header_Widgets')) { 
	c('$evd_headerSidebar!=\'hidden\' && evd_option(\'evd_sidebars-Header_Widgets\') tested true',1);
	c('Begin evd_headerSidebar',2);
	
	if (evd_is_active_sidebar('Header_Widgets')) {
		c('evd_is_active_sidebar(\'Header_Widgets\') tested true',1);
		
		if($evd_headerSidebar=='animate') $class = 'class="animate"';
		echo "<header id=\"headerWrap\"$class>\n".'<div id="headerContent" class="h-lists clearfix">';
		
		c('Begin dynamic_sidebar(\'Header_Widgets\')',1);
		if (function_exists('dynamic_sidebar') && dynamic_sidebar('Header_Widgets')) {}
		
		echo '</div>'; c('/#headerContent');
		echo '</header>';
	} else { 
		c('Add widgets to activate header'); 
	}
	c('End evd_headerSidebar',3);
} 

echo '<div id="evd-page-wrap" class="clearfix">'; br();
 
if(is_home()){ c('Begin evd_auxSidebar(\'Banner_Home\')',1); evd_auxSidebar('Banner_Home'); } 
else if(is_archive()){ c('Begin evd_auxSidebar(\'Banner_Archive\')',1); evd_auxSidebar('Banner_Archive'); } 

$fullWidth = get_post_meta($post->ID, 'fullWidth'); if($fullWidth[0]=='true' && is_singular()) $fullWidth=true; else $fullWidth=false;
if($fullWidth) c('get_post_meta($post->ID, \'fullWidth\') tested true; adding fullWidth class to #evd-content',1);
else  c('get_post_meta($post->ID, \'fullWidth\') tested false; NOT adding fullWidth class to #evd-content',1);

$class='clearfix';
if($fullWidth) $class.=' fullWidth';
echo '<div id="evd-content" class="'.$class.'">'; br();

echo '<div id="contentBody">'; br();

$evd_noscriptMessage = evd_option('evd_noscriptMessage');
if($evd_noscriptMessage) {
	t(
		array(
			'tag'=>'noscript',
			'c'=>'evd_option(\'evd_noscriptMessage\') tested true',
			'wrap'=>true,
			'content'=>'<div id="noscriptMessage" class="alert"><h2>Javascript disabled.</h2><p>'.$evd_noscriptMessage.'</p></div>'
		)
	);
}

c('End header.php',3); 

?>