<?php

/**
 * Return images folder path
 */
function asset($path)
{
	echo bloginfo('template_directory') . '/images/' . $path;
}

/**
 * Disable administration bar
 */
show_admin_bar(false);

/**
 * Reformat archive title function
 */
add_filter( 'get_the_archive_title', function ($title) {
	if ( is_category() ) {
			$title = single_cat_title( '', false );
		} elseif ( is_tag() ) {
			$title = single_tag_title( '', false );
		} elseif ( is_author() ) {
			$title = '<span class="vcard">' . get_the_author() . '</span>' ;
		} elseif ( is_tax() ) { //for custom post types
			$title = sprintf( __( '%1$s' ), single_term_title( '', false ) );
		} elseif (is_post_type_archive()) {
			$title = post_type_archive_title( '', false );
		}
	return $title;
});

/**
 * Remove menus items
 */
function remove_menus(){
	remove_menu_page( 'edit.php' );
	remove_menu_page( 'upload.php' );
	remove_menu_page( 'edit-comments.php' );
	// remove_menu_page( 'themes.php' );
	// remove_menu_page( 'users.php' );
	// remove_menu_page( 'tools.php' );
	// remove_menu_page( 'options-general.php' );
	// remove_menu_page( 'edit.php?post_type=acf' );
	// remove_menu_page( 'wpcf7' );
}
add_action( 'admin_menu', 'remove_menus' );

/**
 * Filter Yoast SEO priority
 */
add_filter('wpseo_metabox_prio', function() {
    return 'low';
});

/**
 * Remove WP Emoji code & Junk code
 */
add_filter('emoji_svg_url', '__return_false');
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('wp_print_styles', 'print_emoji_styles');
remove_action('wp_head', 'wp_generator');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);
remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
remove_action('wp_head', 'start_post_rel_link', 10, 0);
remove_action( 'wp_head', 'rest_output_link_wp_head');
remove_action( 'wp_head', 'feed_links_extra', 3 );
remove_action( 'wp_head', 'feed_links', 2 );
remove_action( 'wp_head', 'wp_oembed_add_discovery_links');
remove_action( 'wp_head', 'rest_output_link_header', 11, 0);
