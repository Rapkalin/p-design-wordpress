<?php

add_action('init', 'brigit_produits_init');

function brigit_produits_init()
{
    register_post_type(
        'produits',
        array(
            'label' => 'Produits',
            'labels' => array(
                'name' => 'Produits',
                'singular_name' => 'Produit',
                'all_items' => 'Tous les produits',
                'add_new_item' => 'Ajouter un produit',
                'edit_item' => 'Éditer le produit',
                'new_item' => 'Nouveau produit',
                'view_item' => 'Voir le produit',
                'search_items' => 'Rechercher parmi les produits',
                'not_found' => 'Aucun produit trouvé',
                'not_found_in_trash'=> 'Aucun produit dans la corbeille'
              ),
            'public' => true,
            'capability_type' => 'post',
            'hierarchical' => false,
            'menu_icon' => 'dashicons-cart',
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
        'categories',
        'produits',
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
				'rewrite' => array('slug' => 'categories'),
            ),
            'hierarchical' => true,
        )
    );
    register_taxonomy_for_object_type('categories', 'produits');
}