<?php

add_action('init', 'brigit_realisations_init');

function brigit_realisations_init()
{
    register_post_type(
        'realisations',
        array(
            'label' => 'Réalisations',
            'labels' => array(
                'name' => 'Réalisations',
                'singular_name' => 'Réalisation',
                'all_items' => 'Toutes les réalisations',
                'add_new_item' => 'Ajouter une réalisation',
                'edit_item' => 'Éditer la réalisation',
                'new_item' => 'Nouvelle réalisation',
                'view_item' => 'Voir la réalisation',
                'search_items' => 'Rechercher parmi les réalisations',
                'not_found' => 'Aucune réalisation trouvée',
                'not_found_in_trash'=> 'Aucune réalisation dans la corbeille'
              ),
            'public' => true,
            'capability_type' => 'post',
            'hierarchical' => false,
            'menu_icon' => 'dashicons-camera-alt',
            'supports' => array(
                'page-attributes',
                'title',
                'editor',
                'thumbnail'
            ),
            'has_archive' => true,
        )
    );

    register_taxonomy(
        'categories-realisations',
        'realisations',
        array(
            'label' => 'Catégories',
            'labels' => array(
                'name' => 'Catégories',
                'singular_name' => 'Catégorie',
                'all_items' => 'Toutes les catégories',
                'edit_item' => 'Éditer la catégorie',
                'view_item' => 'Voir la catégorie',
                'update_item' => 'Mettre à jour la catégorie',
                'add_new_item' => 'Ajouter une catégorie',
                'new_item_name' => 'Nouvelle catégorie',
                'search_items' => 'Rechercher parmi les catégories',
				'popular_items' => 'Catégories les plus utilisées',
				'rewrite' => array('slug' => 'categories-realisations'),
            ),
            'hierarchical' => true,
        )
    );
    register_taxonomy_for_object_type('categories-realisations', 'realisations');
}