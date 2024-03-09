<?php

/**
 * Template Name: Légal
 */
global $post;

get_header();
?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

        <div class="page page-default page-legals page-<?= $post->post_name; ?>">
            <div class="page-title">
                <div class="container">
                    <div class="page-title">
                        <div class="slide" style="background-image: url(<?= asset('home-values.jpg'); ?>)">
                            <div class="title">
                                <h1><?php the_title(); ?></h1>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="page-content">
                <div class="container">
                    <div class="content">
                        <?php the_content(); ?>
                    </div>
                </div>
            </div>

            <?php
            if (!empty(get_field('ask', 'option'))) {
                get_template_part('template-parts/content', 'contact-us');
            }
            ?>
        </div>

<?php endwhile;
endif;
?>

<?php
get_footer();
