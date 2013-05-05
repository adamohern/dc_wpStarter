<?php 

$theme_options = new dc_theme_options('Theme Options','Theme Options');

$theme_options -> add_section('Custom CSS','css');
$theme_options -> add_section('SEO','seo');
$theme_options -> add_section('Icons','icons');
$theme_options -> add_section('Sidebars','sidebars');
$theme_options -> add_section('Custom JS','js');
$theme_options -> add_section('Content','content');
$theme_options -> add_section('Reset to Defaults','reset');


$instructions = '(add special characters using supported wordpress template tags in [shortcode] form, e.g. [the_date])';

/* CSS
===========================================*/

$theme_options -> set('cssOverrides', array(
    'title'   => 'CSS Overrides',
    'desc'    => 'Enter any custom CSS here to apply it to your theme. Hint: copy/paste this into a <a href="http://notepad-plus-plus.org/">CSS editor</a> for editing.',
    'std'     => htmlspecialchars(

'#sidebar{float:left;}'."\n\n".

'a {color: #ff3c00;}'."\n".
'a:hover {color: #f13a09;}'."\n".
'.primaryColor {color: #ff3c00;}'."\n\n".

'::-moz-selection{background: #ffa200;color: #351a00;}'."\n".
'::selection {background: #ffa200;color: #351a00;}'."\n".
'a:link {-webkit-tap-highlight-color: #ffa200;}'."\n".
'ins {background-color: #ffa200;}'."\n".
'mark {background-color: #ffa200;}'."\n\n".

'ol.commentlist li.comment ul.children li.depth-2 {border-color:#555;}'."\n".
'ol.commentlist li.comment ul.children li.depth-3 {border-color:#999;}'."\n".
'ol.commentlist li.comment ul.children li.depth-4 {border-color:#bbb;}'
    
    ),
    
    'type'    => 'css_big',
    'section' => 'css',
    'class'   => 'cssOverrides code'
));   



/* Content
===========================================*/

$theme_options -> set('postFormatSingle', array(
    'title'   => 'post format for single.php',
    'desc'    => 'HTML to display individual posts<br />'.$instructions,
    'std'     => htmlspecialchars(
        '<article class="[get_post_class]" id="post-[the_ID]">'."\n".
        "\t".'<div class="entry-content">'."\n".
        "\t\t".'<h1>[the_title]</h1>'."\n".
        "\t\t".'[dc_sidebar handle="Before_Single"]'."\n".
        "\t\t".'<p>[the_content]</p>'."\n".
        "\t\t".'[dc_sidebar handle="After_Single"]'."\n".
        "\t".'</div><!--/.entry-content-->'."\n".
        '</article>'."\n\n".
        '[comments_template]'
    ),
    'type'    => 'html',
    'section' => 'content',
    'class'   => 'code'
));                      
                                   
                                   
$theme_options -> set('debugMode', array(
    'section' => 'content',
    'title'   => 'Debug Mode',
    'desc'    => 'Displays helpful comments explaining the HTML output.',
    'type'    => 'radio',
    'std'     => '1',
    'choices' => array(
        '1' => 'yes, display the comments',
        '0' => 'no, don\'t display comments'
    )
));                       
                                   


/* JS
===========================================*/

$theme_options -> set('customJS', array(
    'section' => 'js',
    'title'   => 'Custom Javascript - head',
    'desc'    => 'Enter valid jquery-compatible Javascript to insert into the header.',
    'type'    => 'js',
    'std'     => ''
));


$theme_options -> set('headerJS', array(

    'title'   => 'External Javascript URLs - header',
    'desc'    => 'URLs of external javascript files to be enqueued in wp_header, separated by line break',
    'std'     => '',
    'type'    => 'textarea',
    'section' => 'js'
));


$theme_options -> set('customJS_footer', array(
    'section' => 'js',
    'title'   => 'Custom Javascript - foot',
    'desc'    => 'Enter valid jquery-compatible Javascript to insert into the header.',
    'type'    => 'js',
    'std'     => ''
));


$theme_options -> set('footerJS', array(
    'title'   => 'External Javascript URLs - footer',
    'desc'    => 'URLs of external javascript files to be enqueued in wp_footer, separated by line break',
    'std'     => '',
    'type'    => 'textarea',
    'section' => 'js'
));





/* Sidebars
===========================================*/


$theme_options -> set('sidebars-Header_Widgets', array(
    'section' => 'sidebars',
    'title'   => 'Header_Widgets',
    'desc'    => 'Topmost header on the page, above all content.',
    'type'    => 'checkbox',
    'std'     => 1 // Set to 1 to be checked by default, 0 to be unchecked by default.
));

$theme_options -> set('sidebars-Banner_All', array(
    'section' => 'sidebars',
    'title'   => 'Banner_All',
    'desc'    => 'Full-width sidebar below Header_Widgets but above #content and Main_Sidebar on home, archive, search, post, and page views.',
    'type'    => 'checkbox',
    'std'     => 0 // Set to 1 to be checked by default, 0 to be unchecked by default.
));

$theme_options -> set('sidebars-Banner_Home', array(
    'section' => 'sidebars',
    'title'   => 'Banner_Home',
    'desc'    => 'Full-width sidebar below Header_Widgets but above #content and Main_Sidebar on is_home().',
    'type'    => 'checkbox',
    'std'     => 0 // Set to 1 to be checked by default, 0 to be unchecked by default.
));

$theme_options -> set('sidebars-Banner_Archive', array(
    'section' => 'sidebars',
    'title'   => 'Banner_Archive',
    'desc'    => 'Full-width sidebar below Header_Widgets but above #content and Main_Sidebar on is_archive().',
    'type'    => 'checkbox',
    'std'     => 0 // Set to 1 to be checked by default, 0 to be unchecked by default.
));

$theme_options -> set('sidebars-Main_Sidebar', array(
    'section' => 'sidebars',
    'title'   => 'Main_Sidebar',
    'desc'    => 'Appears at either left or right of #content.',
    'type'    => 'checkbox',
    'std'     => 1 // Set to 1 to be checked by default, 0 to be unchecked by default.
));

$theme_options -> set('sidebars-Footer_Widgets', array(
    'section' => 'sidebars',
    'title'   => 'Footer_Widgets',
    'desc'    => 'Appears at bottom of #content.',
    'type'    => 'checkbox',
    'std'     => 0 // Set to 1 to be checked by default, 0 to be unchecked by default.
));		

$theme_options -> set('sidebars-Before_Single', array(
    'section' => 'sidebars',
    'title'   => 'Before_Single',
    'desc'    => 'Appears at top of #content on is_single().',
    'type'    => 'checkbox',
    'std'     => 0 // Set to 1 to be checked by default, 0 to be unchecked by default.
));

$theme_options -> set('sidebars-After_Single', array(
    'section' => 'sidebars',
    'title'   => 'After_Single',
    'desc'    => 'Appears at bottom of #content on is_single().',
    'type'    => 'checkbox',
    'std'     => 0 // Set to 1 to be checked by default, 0 to be unchecked by default.
));

$theme_options -> set('sidebars-Before_Archive', array(
    'section' => 'sidebars',
    'title'   => 'Before_Archive',
    'desc'    => 'Appears at top of #content on is_archive().',
    'type'    => 'checkbox',
    'std'     => 0 // Set to 1 to be checked by default, 0 to be unchecked by default.
));

$theme_options -> set('sidebars-After_Archive', array(
    'section' => 'sidebars',
    'title'   => 'After_Archive',
    'desc'    => 'Appears at bottom of #content on is_archive().',
    'type'    => 'checkbox',
    'std'     => 0 // Set to 1 to be checked by default, 0 to be unchecked by default.
));

$theme_options -> set('sidebars-Before_Home', array(
    'section' => 'sidebars',
    'title'   => 'Before_Home',
    'desc'    => 'Appears at top of #content on is_home().',
    'type'    => 'checkbox',
    'std'     => 0 // Set to 1 to be checked by default, 0 to be unchecked by default.
));








/* Icons
===========================================*/

$theme_options -> set('favicon', array(
    'section' => 'icons',
    'title'   => 'Favicon',
    'desc'    => 'Enter the URL to your custom favicon. It should be 32x32, transparency is ok. PNG works well.',
    'type'    => 'text',
    'std'     => get_bloginfo('wpurl').'/favicon.ico'
));

$theme_options -> set('appleicon', array(
    'section' => 'icons',
    'title'   => 'Apple Favorite Icon',
    'desc'    => 'Enter the URL to your custom favicon for iOS. It should <a href="http://developer.apple.com/library/ios/#documentation/userexperience/conceptual/mobilehig/IconsImages/IconsImages.html">probably</a> be 114x114. PNG works well.',
    'type'    => 'text',
    'std'     => get_bloginfo('wpurl').'/appleicon.ico'
));

$theme_options -> set('jqueryui_theme', array(
    'section' => 'icons',
    'title'   => 'jquery UI theme',
    'desc'    => 'Enter the URL to your favorite jquery UI theme.',
    'type'    => 'text',
    'std'     => 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.23/themes/smoothness/jquery-ui.css'
));







/* SEO Settings
===========================================*/

$theme_options -> set('defaultFocus', array(
    'title'   => 'Default Focus Keyword',
    'desc'    => 'Single keyword for use as the default \'focus\' keyword.',
    'std'     => '',
    'type'    => 'text',
    'section' => 'seo'
));

$theme_options -> set('useMetaKeywords', array(
    'section' => 'seo',
    'title'   => 'Use Meta Keywords Tag?',
    'desc'    => ' - If you\'re not sure, <a href="http://googlewebmastercentral.blogspot.com/2009/09/google-does-not-use-keywords-meta-tag.html">probably not</a>.',
    'type'    => 'checkbox',
    'std'     => 0 // Set to 1 to be checked by default, 0 to be unchecked by default.
));

$theme_options -> set('globalKeywords', array(
    'title'   => 'Global Keywords',
    'desc'    => '(Only used if Use Meta Keywords Tag is enabled.) Comma-delimited keywords for use in the \'keywords\' header meta tag.',
    'std'     => '',
    'type'    => 'text',
    'section' => 'seo'
));
        
$theme_options -> set('titleSlug', array(
    'title'   => 'Title Slug',
    'desc'    => 'Programmer-friendly site title, used as a class for the <body> element on all pages.',
    'std'     => preg_replace("/[^a-zA-Z0-9]/", "",get_bloginfo('name')),
    'type'    => 'text',
    'section' => 'seo'
));

$theme_options -> set('authorName', array(
    'title'   => 'Author Name',
    'desc'    => 'Used for copyright and author meta tags.',
    'std'     => get_bloginfo('name'),
    'type'    => 'text',
    'section' => 'seo'
));

$theme_options -> set('indexSEODescription', array(

    'title'   => 'Index SEO Description',
    'desc'    => '70 chars describing the site. Will be used by default for index and archive pages. On single posts or pages, the excerpt is used.',
    'std'     => '',
    'type'    => 'textarea',
    'section' => 'seo'
));





/* Reset
===========================================*/

$theme_options -> set('reset_theme', array(
    'section' => 'reset',
    'title'   => 'Reset theme',
    'type'    => 'checkbox',
    'std'     => 0,
    'class'   => 'warning', // Custom class for CSS
    'desc'    => 'Check this box and click "Save Changes" below to reset theme options to their defaults.'
));

?>