dc_wpStarter
============


++++++++++
HOOKS
++++++++++

FILTERS:
content_missing				404 page content
dc_css_overrides			outputs CSS from theme options panel
dc_custom_head				outputs custom head html from theme options
dc_custom_js				outputs custom header js from theme options
dc_search_form				outputs search form from theme content options
custom_js_footer			outputs custom footer js from theme options


dc_query_posts_titleBar		output for dc_query_posts title block
post_format_dc_query_posts	output for dc_query_posts individual post blocks
dc_query_posts				output for entire dc_query_posts block

dc_get_render_markup		output for anything that renders using the theme content options
dc_get_the_post_thumbnail
dc_get_request_uri
dc_get_search_form
dc_get_the_author_posts_link
dc_get_the_title
dc_get_the_date
dc_get_the_content
dc_get_the_tags
dc_get_the_category
dc_get_bloginfo
dc_get_the_author_meta
dc_get_the_terms
dc_get_the_permalink
dc_get_the_shortlink
dc_get_the_time
dc_get_comments_template
dc_get_post_meta
dc_get_post_class
dc_get_google_authorship
dc_google_authorship
dc_get_the_loop				output for every loop in the dc theme
dc_get_the_loop $format		output for specific format (e.g. 'post_format_index')
dc_get_post_nav
dc_get_author_bio
dc_search_title
dc_archive_title

dc_get_sidebar

dc_user_option_form			output for user options form; feel free to add more user meta
	// note: there is a custom php-generated hook for each separate instance of the dc_user_option_form (see code comments)
	
ACTIONS:
dc_comment_form				($post->ID int) executes at end of comment form
dc_display_user_options		($user object)
dc_postmeta					($dc_postmeta array) executes when adding custom post meta boxes (add more if you like)
dc_options					($dc_options array) executes when adding theme options (add more options if you want)
