<?php
global $post;
get_header();

$realisationPageId = 21;
$totalCount = wp_count_posts('realisations');
$categories = get_field('realisation_categories', $realisationPageId);
?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

		<div class="page page-default single-realisations realisation-<?= $post->post_name; ?>">
			<div class="page-title">
				<div class="large-container-left">
					<div class="page-title-slider">
						<?php if (have_rows('realisation_slider')) : while (have_rows('realisation_slider')) : the_row(); ?>
								<div class="slide" style="background-image: url(<?= get_sub_field('image')['url']; ?>)">
									<div class="title">
										<h2>Réalisations</h2>
									</div>
								</div>
						<?php endwhile;
						endif; ?>
					</div>
				</div>
			</div>

			<div class="page-overlay-intro with-right-block">
				<div class="large-container-left">
					<div class="content-with-menus">
						<div class="menus">
							<a href="<?= get_page_link($realisationPageId); ?>">Tous les projets</a>
							<?php foreach ($categories as $category) : ?>
								<?php $term = get_term($category['category'], 'categories-realisations'); ?>
								<a href="<?= get_page_link($realisationPageId); ?>#category-<?= $term->name ?>" class="<?= has_term($term, 'categories-realisations', get_the_ID()) ? "active" : "" ?>"><?= $term->name; ?></a>
							<?php endforeach; ?>
						</div>
						<div class="row">
							<div class="left">
								<h1><?php the_title(); ?></h1>
								<p><?php the_field('realisation_address'); ?></p>
							</div>
							<!-- <div class="right">
								<div class="realisations-navigation">
									<div class="prev"><img src="<?= asset('arrow-prev.svg') ?>" class="svg"></div>
									<div class="number-ratio">
										<span>7</span>
										<span>40</span>
									</div>
									<div class="next"><img src="<?= asset('arrow-next.svg') ?>" class="svg"></div>
								</div>
								<a href="#" class="all-projects">Voir tous les projets</a>
							</div> -->
						</div>
					</div>
					<div class="right-block">
						<p>Pour tout projet similaire<br>merci de nous contacter :</p>
						<p><a href="tel:+33158910526"><img src="<?= asset('phone-dark.svg'); ?>" alt="Téléphone"> 01 48 47 48 49</a></p>
						<p><a href="mailto:contact@p-design.fr"><img src="<?= asset('email.svg'); ?>" alt="Email"> Par email</a></p>
					</div>
				</div>
			</div>

			<section class="intro">
				<div class="large-container">
					<div class="content">
						<?php the_field('realisation_introduction'); ?>
					</div>
				</div>
			</section>

			<section class="main-image">
				<div class="image" style="background-image: url(<?= get_field('realisation_banner')['url']; ?>);"></div>
			</section>

			<?php if (get_field('realisation_images')) : ?>
				<section class="gallery">
					<div class="small-container">
						<div class="content">
							<?php foreach (get_field('realisation_images') as $image) : ?>
								<div class="image" style="background-image: url(<?= $image['url']; ?>);"></div>
							<?php endforeach; ?>
						</div>
					</div>
				</section>
			<?php endif; ?>


			<!-- À faire -->
			<section class="more-realisations">
				<div class="large-container-left">
					<div class="content">
						<div class="left">
							<p><strong>Quelques <br>projets</strong></p>
							<a href="<?= get_page_link($realisationPageId); ?>">Consulter tous <br>les projets</a>
						</div>
						<div class="images">
							<?php $realisations = new WP_Query(['post_type' => 'realisations', 'posts_per_page' => 4, 'post__not_in' => [get_the_ID()]]); ?>
							<?php if ($realisations->have_posts()) : while ($realisations->have_posts()) : $realisations->the_post(); ?>
									<a href="<?php the_permalink(); ?>">
										<div style="background-image: url(<?= get_field('realisation_banner')['url']; ?>);"></div>
									</a>
							<?php wp_reset_postdata();
								endwhile;
							endif; ?>
						</div>

						<div class="right"><?= $totalCount->publish; ?></div>
					</div>
				</div>
			</section>

			<?php get_template_part('template-parts/content', 'contact-us'); ?>


		</div>

<?php endwhile;
endif; ?>

<?php
get_footer();
