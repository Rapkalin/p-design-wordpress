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

// Remplacez 'produits' par le slug de votre custom post type.
add_filter('manage_edit-produits_columns', 'add_product_columns');
function add_product_columns($columns) {
    $columns['product_categories'] = __('Catégories'); // Titre de la colonne
    return $columns;
}

add_action('manage_produits_posts_custom_column', 'display_product_categories_column', 10, 2);
function display_product_categories_column($column, $post_id) {
    if ($column == 'product_categories') {
        $terms = get_the_terms($post_id, 'product_categories');
        if ($terms && !is_wp_error($terms)) {
            $terms_list = [];
            foreach ($terms as $term) {
                $terms_list[] = $term->name; // Vous pouvez également utiliser $term->term_id ou d'autres propriétés si nécessaire
            }
            echo implode(', ', $terms_list);
        } else {
            echo 'Aucune catégorie';
        }
    }
}

add_action('restrict_manage_posts', 'filter_products_by_categories');
function filter_products_by_categories() {
    global $typenow; // Type de post actuel
    if ($typenow == 'produits') { // Remplacez 'produits' par le slug de votre custom post type
        $terms = get_terms([
            'taxonomy' => 'product_categories', // Taxonomie à filtrer
            'hide_empty' => false, // Affiche toutes les catégories
        ]);

        if ($terms) {
            echo '<select name="product_categories_filter">';
            echo '<option value="">' . __('Toutes les catégories') . '</option>';
            foreach ($terms as $term) {
                $selected = (isset($_GET['product_categories_filter']) && $_GET['product_categories_filter'] == $term->term_id) ? ' selected="selected"' : '';
                echo '<option value="' . $term->term_id . '"' . $selected . '>' . $term->name . '</option>';
            }
            echo '</select>';
        }
    }
}

add_filter('pre_get_posts', 'filter_products_by_selected_category');
function filter_products_by_selected_category($query) {
    global $pagenow;
    $typenow = 'produits'; // Remplacez 'produits' par le slug de votre custom post type

    if ($pagenow == 'edit.php' && $query->is_admin && $query->get('post_type') === $typenow) {
        if (isset($_GET['product_categories_filter']) && $_GET['product_categories_filter'] != '') {
            // Vérifiez si tax_query est déjà défini
            $tax_query = (array) $query->get('tax_query');

            // Ajoutez la nouvelle requête de taxonomie
            $tax_query[] = [
                'taxonomy' => 'product_categories',
                'field' => 'id',
                'terms' => intval($_GET['product_categories_filter']),
            ];

            // Mettez à jour tax_query avec les nouvelles conditions
            $query->set('tax_query', $tax_query);
        }
    }
}