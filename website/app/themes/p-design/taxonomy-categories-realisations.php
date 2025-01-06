<?php

global $post;

get_header();

$term_id = get_queried_object()->term_id;
$posts_taxonomy = new WP_Query(['post_type' => 'realisations', 'tax_query' => [['taxonomy' => 'categories-realisations', 'field' => 'id', 'terms' => $term_id]]]);
$count = $posts_taxonomy->found_posts;
?>

<div class="page page-default page-<?= $post->post_name; ?>">
    <div class="page-title">
        <div class="large-container-left">
            <div class="page-title-slider">
                <div class="slide" style="background-image: url(<?= asset('home-showroom.jpg'); ?>)">
                    <div class="title">
                        <h1><?php single_cat_title(); ?></h1>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section class="realisations-list realisations-taxonomy-content">
        <div class="large-container">
            <div class="realisations-categories">
                <div class="category">
                    <div class="category-realisations">
                        <?php
                        $i = 1;
                        if (have_posts()) : while (have_posts()) : the_post();
                                $image = get_field('realisation_banner', get_the_ID());
                        ?>
                                <div class="realisation">
                                    <div class="number-ratio">
                                        <span class="big"><?php echo $i; ?></span>
                                        <span class="big"><?= $count; ?></span>
                                    </div>
                                    <a href="<?php echo the_permalink(); ?>">
                                        <div class="image-container">
                                            <div class="image" style="background-image: url(<?= $image['url'] ?>);">
                                                <div class="hover"><img src="<?= asset('plus.svg'); ?>" alt=""></div>
                                            </div>
                                        </div>
                                        <div class="name"><?php echo the_title(); ?> </div>
                                    </a>
                                </div>
                        <?php
                                $i++;
                                wp_reset_postdata();
                            endwhile;
                        endif;
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php
    get_template_part('template-parts/content', 'contact-us');
    ?>

</div>


<?php
get_footer();
