<?php

/**
 * P-Design functions and definitions
 */
if (!function_exists('pdesign_setup')) :
	function pdesign_setup()
	{
		add_theme_support('title-tag');
		add_theme_support('post-thumbnails');
		add_theme_support('align-wide');
		add_theme_support('customize-selective-refresh-widgets');
		add_theme_support('post-formats', array(
			'aside', 'chat', 'gallery', 'image', 'link', 'quote', 'status', 'video', 'audio'
		));
		add_theme_support('editor-color-palette', array(
			array('name' => 'Rose', 'slug'  => 'pink', 'color' => '#ff8da8'),
			array('name' => 'Noir', 'slug'  => 'dark-text', 'color' => '#5f5e5e')
		));

		register_nav_menus([
			'header-menu' => esc_html__('Header Menu', 'pdesign'),
			'products-menu' => esc_html__('Products Menu', 'pdesign'),
			'footer-menu' => esc_html__('Footer Menu', 'pdesign'),
			'footer-products-menu' => esc_html__('Footer Products Menu', 'pdesign'),
		]);
	}
endif;
add_action('after_setup_theme', 'pdesign_setup');


/**
 * Enqueue scripts and styles.
 */
function pdesign_scripts()
{
	wp_enqueue_style('pdesign', get_stylesheet_uri());
	wp_dequeue_style('wp-block-library');
	wp_deregister_script('wp-embed');
	wp_deregister_script('jquery');
	wp_enqueue_script('pdesign-js', get_template_directory_uri() . '/app.js', array(), '1.0', true);
}
add_action('wp_enqueue_scripts', 'pdesign_scripts', 100);


/**
 * Reformat default Wordpress configuration
 */
require 'inc/wp-reformat.php';

/**
 * Custom Post Types
 */
require 'inc/produits.php';

require 'inc/realisations.php';


/**
 * ACF add options page
 */
if (function_exists('acf_add_options_page')) {
	acf_add_options_page();
}
