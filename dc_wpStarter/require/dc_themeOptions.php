<?php 

$theme_options = new dc_theme_options('Theme Options','Theme Options');

$theme_options -> add_section('Colors and Graphics','colors');
$theme_options -> add_section('Layout','layout');
$theme_options -> add_section('Sidebars','sidebars');
$theme_options -> add_section('Content','content');
$theme_options -> add_section('SEO','seo');
$theme_options -> add_section('Custom CSS','css');
$theme_options -> add_section('Custom JS','js');




/* CSS
===========================================*/

$theme_options -> set('cssOverrides', array(
    'title'   => 'CSS Overrides',
    'desc'    => 'Enter any custom CSS here to apply it to your theme. Hint: copy/paste this into a <a href="http://notepad-plus-plus.org/">CSS editor</a> for editing.',
    'std'     => '',
    'type'    => 'css_big',
    'section' => 'css',
    'class'   => 'cssOverrides code'
));




/* Layout
===========================================*/

$theme_options -> set('homeListContent', array(
    'section' => 'layout',
    'title'   => 'Home List Content',
    'desc'    => 'What type of content should be displayed on the home screen?',
    'type'    => 'radio',
    'std'     => 'content',
    'choices' => array(
        'excerpt' => 'Display a content excerpt.',
        'content' => 'Display full content (up to any \'more\' tags).',
        'none' => 'Do not display content.'
    )
));

$theme_options -> set('archiveListContent', array(
    'section' => 'layout',
    'title'   => 'Archive List Content',
    'desc'    => 'What type of content should be displayed on archives and search results?',
    'type'    => 'radio',
    'std'     => 'excerpt',
    'choices' => array(
        'excerpt' => 'Display a content excerpt.',
        'content' => 'Display full content (up to any \'more\' tags).',
        'none' => 'Do not display content.'
    )
));

$theme_options -> set('displayThumb_archive', array(
    'section' => 'layout',
    'title'   => 'Display Thumbnails in Archives',
    'desc'    => 'Show the \'Featured Image\' with posts in a list (i.e. is_archive())?',
    'type'    => 'checkbox',
    'std'     => 0 // Set to 1 to be checked by default, 0 to be unchecked by default.
));

$theme_options -> set('thumbsize_archive', array(
    'section' => 'layout',
    'title'   => 'Thumbnail Size - Archives',
    'desc'    => 'If enabled above, how big should the thumbnail be on is_archive()?',
    'type'    => 'radio',
    'std'     => '1',
    'choices' => array(
        'dc_thumbnail' => 'Tall (256x144)',
        'dc_large' => 'Grande (720x405)',
        'dc_huge' => 'Venti (960x540)'
    )
));

$theme_options -> set('displayThumb_single', array(
    'section' => 'layout',
    'title'   => 'Display Thumbnails on Posts',
    'desc'    => 'Show the \'Featured Image\' with individual posts (i.e. is_single())?',
    'type'    => 'checkbox',
    'std'     => 0 // Set to 1 to be checked by default, 0 to be unchecked by default.
));

$theme_options -> set('thumbsize_single', array(
    'section' => 'layout',
    'title'   => 'Thumbnail Size - Posts',
    'desc'    => 'If enabled above, how big should the thumbnail be on is_single()?',
    'type'    => 'radio',
    'std'     => '1',
    'choices' => array(
        'dc_thumbnail' => 'Medium (256x144)',
        'dc_large' => 'Large (720x405)',
        'dc_huge' => 'Supersize Me (960x540)'
    )
));

$theme_options -> set('pageTitles', array(
    'section' => 'layout',
    'title'   => 'Display Page Titles',
    'desc'    => 'Should we display the titles of pages?',
    'type'    => 'radio',
    'std'     => '1',
    'choices' => array(
        '1' => 'yes, show page titles',
        '0' => 'no, hide page titles'
    )
));
                                   
                                   
                                   
                                   
                                   


/* JS
===========================================*/

$theme_options -> set('customJS', array(
    'section' => 'js',
    'title'   => 'Custom Javascript - head',
    'desc'    => 'Enter valid jquery-compatible Javascript to insert into the header.',
    'type'    => 'js_big',
    'std'     => ''
));

$theme_options -> set('customJS_footer', array(
    'section' => 'js',
    'title'   => 'Custom Javascript - foot',
    'desc'    => 'Enter valid jquery-compatible Javascript to insert into the header.',
    'type'    => 'js_big',
    'std'     => ''
));






/* Sidebars
===========================================*/

$theme_options -> set('headerSidebar', array(
    'section' => 'sidebars',
    'title'   => 'Header Sidebar Mode',
    'desc'    => 'The header sidebar is full-width, flexible height, and can animate to partially hide itself on mouseout. You can also just hide it completely!',
    'type'    => 'radio',
    'std'     => 'animate',
    'choices' => array(
        'animate' => 'Animate',
        'visible' => 'Always Visible',
        'hidden' => 'Never Visible'
    )
));

$theme_options -> set('mainSidebar', array(
    'section' => 'sidebars',
    'title'   => 'Main Sidebar Location',
    'desc'    => 'Where should we put the main sidebar?',
    'type'    => 'radio',
    'std'     => 'left',
    'choices' => array(
        'left' => 'Left',
        'right' => 'Right'
    )
));

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








/* Content
===========================================*/

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

$theme_options -> set('displayBio', array(
    'section' => 'content',
    'title'   => 'Author Bio',
    'desc'    => 'Display the author bio (from user meta) below posts?',
    'type'    => 'checkbox',
    'std'     => 1 // Set to 1 to be checked by default, 0 to be unchecked by default.
));

$theme_options -> set('disableComments', array(
    'section' => 'content',
    'title'   => 'Disable Comments',
    'desc'    => 'Check to disable ALL comments completely (including external comment systems). Does NOT apply to admin users.',
    'type'    => 'checkbox',
    'std'     => 0 // Set to 1 to be checked by default, 0 to be unchecked by default.
));

 $theme_options -> set('commentsDisabledMessage', array(
    'title'   => 'Comments Disabled Message',
    'desc'    => 'HTML to display in place of comments if Disable Comments is checked.',
    'std'     => '',
    'type'    => 'html',
    'section' => 'content',
    'class'   => 'code'
));

$theme_options -> set('404Message', array(
    'section' => 'content',
    'title'   => '404 Message',
    'desc'    => 'The paragraph text below the bad URL in the 404 alert box. (Plain text)',
    'type'    => 'text',
    'std'     => 'Oops! Looks like we\'re missing something here...'
));

$theme_options -> set('noscriptMessage', array(
    'section' => 'content',
    'title'   => 'No Javascript Message',
    'desc'    => 'The paragraph text in the \'Javascript disabled.\' alert box. (Plain text; Leave blank for no alert.)',
    'type'    => 'text',
    'std'     => 'We use clean, safe Javascript to make our sites easier to navigate. Please consider enabling Javascript for this site.'
));








/* Colors
===========================================*/

$theme_options -> set('primaryColor', array(
    'title'   => 'Primary Color',
    'desc'    => 'Main hex color used for links, certain headings, etc.',
    'std'     => '#66479c',
    'type'    => 'color',
    'section' => 'colors'
));

$theme_options -> set('primaryColorFaded', array(
    'title'   => 'Primary Color Faded',
    'desc'    => 'Main hex color used for visited and active links, certain decorative elements, etc.',
    'std'     => '#886eb6',
    'type'    => 'color',
    'section' => 'colors'
));

$theme_options -> set('selectionColor', array(
    'title'   => 'Selection Highlight Color',
    'desc'    => 'Hex color used to highlight selected text.',
    'std'     => '#66479c',
    'type'    => 'color',
    'section' => 'colors'
));

$theme_options -> set('selectionTextColor', array(
    'title'   => 'Selection Text Color',
    'desc'    => 'Hex color used for selected text.',
    'std'     => '#fff',
    'type'    => 'color',
    'section' => 'colors'
));

$theme_options -> set('alertColor', array(
    'title'   => 'Alert Background Color',
    'desc'    => 'Main hex color used for background for alert boxes (e.g. 404).',
    'std'     => '#ffc',
    'type'    => 'color',
    'section' => 'colors'
));

$theme_options -> set('alertBorderColor', array(
    'title'   => 'Alert Border Color',
    'desc'    => 'Main hex color used for border around alert boxes (e.g. 404).',
    'std'     => '#fc0',
    'type'    => 'color',
    'section' => 'colors'
));

$theme_options -> set('alertTextColor', array(
    'title'   => 'Alert Text Color',
    'desc'    => 'Main hex color used for text in alert boxes (e.g. 404).',
    'std'     => '#000',
    'type'    => 'color',
    'section' => 'colors'
));

$theme_options -> set('favicon', array(
    'section' => 'colors',
    'title'   => 'Favicon',
    'desc'    => 'Enter the URL to your custom favicon. It should be 32x32, transparency is ok. PNG works well.',
    'type'    => 'text',
    'std'     => get_bloginfo('wpurl').'/favicon.ico'
));

$theme_options -> set('appleicon', array(
    'section' => 'colors',
    'title'   => 'Apple Favorite Icon',
    'desc'    => 'Enter the URL to your custom favicon for iOS. It should <a href="http://developer.apple.com/library/ios/#documentation/userexperience/conceptual/mobilehig/IconsImages/IconsImages.html">probably</a> be 114x114. PNG works well.',
    'type'    => 'text',
    'std'     => get_bloginfo('wpurl').'/appleicon.ico'
));

$theme_options -> set('jqueryui_theme', array(
    'section' => 'colors',
    'title'   => 'jquery UI theme',
    'desc'    => 'Enter the URL to your favorite jquery UI theme.',
    'type'    => 'text',
    'std'     => 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.23/themes/smoothness/jquery-ui.css'
));

$theme_options -> set('xman', array(
    'section' => 'colors',
    'title'   => 'Your Favorite X-Man (or woman)',
    'desc'    => 'This is really up to you. Choose wisely.',
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

$theme_options -> set('analyticsHeading', array(
    'section' => 'seo',
    'title'   => '', // Not used for headings.
    'desc'    => 'Performance Tracking',
    'type'    => 'heading'
));

$theme_options -> set('googleAnalytics', array(
    'title'   => 'Google Analytics',
    'desc'    => 'Paste the block of code provided by Google for performance tracking. This will be inserted in the page header.',
    'std'     => '',
    'type'    => 'html',
    'section' => 'seo'
));

$theme_options -> set('googleVerification', array(
    'title'   => 'Google Site Verification Code',
    'desc'    => 'Speaking of Google, don\'t forget to set your site up: http://google.com/webmasters.',
    'std'     => '',
    'type'    => 'text',
    'section' => 'seo'
));

?>