<?php

/**
 * Template Name: Réalisations
 */
global $post;
// We retrieve all the details for each realisation
$menu_categories = get_field('realisation_categories', get_the_ID());

// We retrieve all the details for each realisation
$content_categories = get_terms('categories-realisations');

/*
 * We put all content category names in an array
 * This array will be used to check if we can display the category name in the menu
*/
$content_categories_names = [];
foreach ($content_categories as $content_category) {
    $content_categories_names[] = $content_category->name;
}

get_header();
?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

        <div class="page page-default page-<?= $post->post_name; ?>">
            <div class="page-title">
                <div class="large-container-left">
                    <div class="page-title-slider">
                        <div class="slide" style="background-image: url(<?= asset('home-showroom.jpg'); ?>)">
                            <div class="title">
                                <h1><?php the_title(); ?></h1>
                            </div>
                        </div>
                        <div class="slide" style="background-image: url(<?= asset('home-showroom.jpg'); ?>)">
                            <div class="title">
                                <h1><?php the_title(); ?></h1>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php
            if (!empty($menu_categories)) {
            ?>
                <div class="page-overlay-intro">
                    <div class="large-container-left">
                        <div class="content-with-menus">
                            <div class="menus">
                                <a href="#" class="scrollto active">Tous les projets</a>
                                <?php
                                $menu_categories_names = [];
                                $menu_categories_name_availables = [];
                                $menu_categories_details = [];

                                foreach ($menu_categories as $menu_category) {

                                    $menu_category_details = get_term($menu_category["category"]);
                                    $menu_categories_names[] = $menu_category_details->name;

                                    /*
                                     * We display the category name in the menu only if we have content for it
                                     *  And we create an array with only the category names we can use
                                     *  And we create another array with the matching name in index and description for later use
                                    */
                                    if (in_array($menu_category_details->name, $content_categories_names)) {
                                        $menu_categories_name_availables[] = $menu_category_details->name;
                                        $menu_categories_details[$menu_category_details->name] = $menu_category['description'];
                                ?>
                                        <a href="<?php echo "#category-" . $menu_category_details->name ?>" class="scrollto"><?php echo $menu_category_details->name ?></a>
                                <?php
                                    }
                                }

                                $menu_categories_list = implode(", ", $menu_categories_name_availables);
                                ?>
                            </div>
                            <div class="row">
                                <div class="left">
                                    <p><strong>Retrouvez l’ensemble de nos projets, classés par catégorie : </strong><br><?php echo $menu_categories_list ?></p>
                                </div>
                                <div class="right">
                                    <?php $count = wp_count_posts('realisations'); ?>
                                    <div class="big"><?= $count->publish; ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            <?php
            }
            ?>

            <section class="realisations-list">
                <div class="large-container">
                    <div class="realisations-categories">

                        <?php
                        foreach ($content_categories as $content_category) :
                            $content_category_slug = $content_category->slug;
                            $content_category_object = get_term_by('slug', $content_category_slug, 'categories-realisations');

                            // If the menu name for the category is not set then we don't display the content
                            if ($content_category_object && in_array($content_category->name, $menu_categories_names)) {
                                $args = array(
                                    'posts_per_page' => -1, // Récupérer tous les articles liés au terme
                                    'tax_query' => array(
                                        array(
                                            'taxonomy' => $content_category_object->taxonomy,
                                            'field'    => 'id',
                                            'terms'    => $content_category_object->term_id,
                                        ),
                                    ),
                                );
                        ?>
                                <div class="category">
                                    <div class="category-title">
                                        <h2 id="category-<?php echo $content_category_object->name ?>"><?php echo $content_category_object->name ?></h2>
                                        <p><?php echo $menu_categories_details[$content_category_object->name] ?></p>
                                    </div>
                                    <div class="category-realisations">
                                        <?php
                                        $query = new WP_Query($args);

                                        if ($query->have_posts()) {
                                            $i = 1;
                                            while ($query->have_posts()) {
                                                $query->the_post();
                                                $image = get_field('realisation_banner', get_the_ID());
                                        ?>
                                                <div class="realisation">
                                                    <div class="number-ratio">
                                                        <span class="big"><?php echo $i; ?></span>
                                                        <span class="big"><?php echo $content_category_object->count ?> </span>
                                                    </div>
                                                    <a href="<?php echo the_permalink(); ?>">
                                                        <div class="image-container">
                                                            <div class="image" style="background-image: url(<?= $image['url'] ?>);">
                                                                <div class="hover"><img src="<?= asset('plus.svg'); ?>" alt=""></div>
                                                            </div>
                                                        </div>
                                                        <div class="name"><?php echo the_title() . ' | Paris'; ?> </div>
                                                    </a>
                                                </div>
                                        <?php
                                                $i++;
                                            }
                                            wp_reset_postdata(); // Réinitialise les données de l'article après la boucle
                                        } else {
                                            echo 'Aucun article trouvé pour ce terme.';
                                        }
                                        ?>
                                        <div class="realisation">
                                            <div class="more">
                                                <a href="<?= get_term_link($content_category_object, 'categories-realisations'); ?>" class="button button-white button-image">
                                                    <img src="<?php echo asset('plus.svg') ?>" class="svg">
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                        <?php
                            } else {
                                // echo 'Aucun article trouvé.';
                            }
                        endforeach;
                        ?>

                    </div>
                </div>
            </section>

            <?php
            if (!empty(get_field('ask', 'option'))) {
                get_template_part('template-parts/content', 'contact-us');
            }
            ?>

        </div>

<?php endwhile;
endif; ?>

<?php
get_footer();
