<?php
/**
 * Template Name: Showroom
 */
global $post;

get_header();
?>

	<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

		<div class="page page-default page-<?= $post->post_name; ?>">
			<div class="page-title">
				<div class="large-container-left">
					<div class="page-title-slider">
						<?php if( have_rows('showroom_slider') ): while( have_rows('showroom_slider') ) : the_row(); ?>
							<div class="slide" style="background-image: url(<?= get_sub_field('image')['url']; ?>)">
								<div class="title">
									<h1><?php the_title(); ?></h1>
								</div>
							</div>
						<?php endwhile; endif; ?>
					</div>
				</div>
			</div>

			<div class="page-overlay-intro">
				<div class="large-container-left">
					<div class="content">
						<?php the_field('showroom_introduction'); ?>
					</div>
				</div>
			</div>

			<?php if( have_rows('showroom_steps') ): while( have_rows('showroom_steps') ) : the_row(); ?>
				<section class="showroom-milestones">
					<div class="large-container">
						<div class="page-subtitle">
							<h2><?php the_sub_field('title'); ?></h2>
						</div>
						<div class="milestones">
							<?php $i = 1; if( have_rows('steps') ): while( have_rows('steps') ) : the_row(); ?>
								<div class="item">
									<div class="image" style="background-image: url(<?= get_sub_field('image')['url']; ?>);">
										<div class="number-ratio">
											<span class="big"><?= $i; ?></span>
											<span>4</span>
										</div>
									</div>
									<div class="content">
										<?php the_sub_field('description'); ?>
									</div>
								</div>
							<?php $i++; endwhile; endif; ?>
						</div>
					</div>
				</section>
			<?php endwhile; endif; ?>

			<?php if( have_rows('showroom_team') ): while( have_rows('showroom_team') ) : the_row(); ?>
				<section class="showroom-team">
					<div class="large-container-left">
						<div class="page-subtitle">
							<h2><?php the_sub_field('title'); ?></h2>
						</div>
						<div class="team">
							<div class="image" style="background-image: url(<?= get_sub_field('image')['url']; ?>);"></div>
							<div class="content">
								<?php the_sub_field('description') ?>
							</div>
						</div>
					</div>
				</section>
			<?php endwhile; endif; ?>

			<?php if( have_rows('shoowroom_location') ): while( have_rows('shoowroom_location') ) : the_row(); ?>
				<section class="showroom-location">
					<div class="large-container-left">
						<div class="page-subtitle">
							<h2><?php the_sub_field('title'); ?></h2>
						</div>
						<div class="location">
							<div class="content">
								<?php the_sub_field('description'); ?>
							</div>
							<div class="map" id="map" data-lat="<?= get_sub_field('address')['lat']; ?>" data-lng="<?= get_sub_field('address')['lng']; ?>"></div>
						</div>
					</div>
				</section>
			<?php endwhile; endif; ?>


			<?php if( have_rows('shoowroom_meeting') ): while( have_rows('shoowroom_meeting') ) : the_row(); ?>
				<section class="showroom-agenda">
					<div class="large-container">
						<div class="page-subtitle">
							<h2><?php the_sub_field('title'); ?></h2>
						</div>
						<div class="agenda">
							<div class="content">
								<?php the_sub_field('description'); ?>
							</div>
							<div class="agenda-calendly">
								<?php the_sub_field('calendly_widget'); ?>
							</div>
						</div>
					</div>
				</section>
			<?php endwhile; endif; ?>

		</div>

	<?php endwhile; endif; ?>

<?php
get_footer();