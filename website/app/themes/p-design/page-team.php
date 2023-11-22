<?php

/**
 * Template Name: L'Équipe
 *
 */
global $post;

get_header();
?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

        <div class="page page-default page-<?= $post->post_name; ?>">
            <div class="page-title">
                <div class="large-container-left">
                    <div class="page-title-slider">
                        <div class="slide" style="background-image: url(<?= asset('home-values.jpg'); ?>)">
                            <div class="title">
                                <h1><?php the_title(); ?></h1>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="page-overlay-intro">
                <div class="large-container-left">
                    <div class="content">
                        <?php the_field('team_introduction'); ?>
                    </div>
                </div>
            </div>

            <?php if (have_rows('team_values')) : while (have_rows('team_values')) : the_row(); ?>
                    <section class="home-values">
                        <div class="large-container">
                            <div class="content">
                                <div class="text">
                                    <div class="section-title">
                                        <h2><?php the_sub_field('team_values_title'); ?></h2>
                                    </div>

                                    <?php if (have_rows('team_values_list_descriptions')) : while (have_rows('team_values_list_descriptions')) : the_row(); ?>
                                            <div class="item">
                                                <?php the_sub_field('team_values_description'); ?>
                                            </div>
                                    <?php endwhile;
                                    endif; ?>
                                </div>
                                <?php if (get_sub_field('team_values_image')) : ?>
                                    <div class="image" style="background-image: url(<?= get_sub_field('team_values_image'); ?>);"></div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </section>
            <?php endwhile;
            endif; ?>

            <?php if (have_rows('team_about')) : while (have_rows('team_about')) : the_row(); ?>
                    <section class="team-about" id="team-about">
                        <div class="container">
                            <div class="body">
                                <div class="small-container">
                                    <div class="about-mask" id="about-mask"></div>
                                    <div class="content">
                                        <?php if (have_rows('team_about_numbers')) : while (have_rows('team_about_numbers')) : the_row(); ?>
                                                <div class="item">
                                                    <div>
                                                        <?php the_sub_field('team_about_description'); ?>
                                                    </div>
                                                </div>
                                        <?php endwhile;
                                        endif; ?>
                                    </div>
                                </div>
                            </div>
                            <div class="conclusion">
                                <p><?php the_sub_field('team_about_block_description'); ?></p>
                                <?php if (get_sub_field('team_about_link')) : ?>
                                    <a href="<?= get_sub_field('team_about_block_link')['url']; ?>" class="button button-white button-image"><img src="<?= asset('plus.svg'); ?>" class="svg"></a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </section>
            <?php endwhile;
            endif; ?>

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
