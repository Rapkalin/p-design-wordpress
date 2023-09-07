<?php
/**
 * Template Name: Réalisations
 */
global $post;

get_header();
?>

	<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

		<div class="page page-default page-<?= $post->post_name; ?>">

			<div class="page-contact-grid">
				<div class="image" style="background-image: url(<?= asset('home-showroom.jpg'); ?>);">

				</div>
				<div class="content">
					<div class="title">
						<h1><?php the_title(); ?></h1>
					</div>
					<div class="form">
						<?php the_content(); ?>
					</div>
				</div>
			</div>

		</div>

	<?php endwhile; endif; ?>

<?php
get_footer();