<?php
global $post;
get_header();
?>

	<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

		<div class="page page-default single-realisations realisation-<?= $post->post_name; ?>">
			<div class="page-title">
				<div class="large-container-left">
					<div class="page-title-slider">
						<?php if( have_rows('realisation_slider') ): while( have_rows('realisation_slider') ) : the_row(); ?>
							<div class="slide" style="background-image: url(<?= get_sub_field('image')['url']; ?>)">
								<div class="title">
									<h2>Réalisations</h2>
								</div>
							</div>
						<?php endwhile; endif; ?>
					</div>
				</div>
			</div>

			<div class="page-overlay-intro with-right-block">
				<div class="large-container-left">
					<div class="content-with-menus">
						<div class="menus">
							<a href="#">Tous les projets</a>
							<a href="#" class="active">Bar</a>
							<a href="#">Brasserie</a>
							<a href="#">Restaurant</a>
							<a href="#">Hôtel</a>
						</div>
						<div class="row">
							<div class="left">
								<h1><?php the_title(); ?></h1>
								<p><?php the_field('realisation_address'); ?></p>
							</div>
							<div class="right">
								<div class="realisations-navigation">
									<div class="prev"><img src="<?= asset('arrow-prev.svg') ?>" class="svg"></div>
									<div class="number-ratio">
										<span>7</span>
										<span>40</span>
									</div>
									<div class="next"><img src="<?= asset('arrow-next.svg') ?>" class="svg"></div>
								</div>
								<a href="#" class="all-projects">Voir tous les projets</a>
							</div>
						</div>
					</div>
					<div class="right-block">
						<p>Pour tout projet similaire<br>merci de nous contacter :</p>
						<p><a href="#"><img src="<?= asset('phone-dark.svg'); ?>" alt="Téléphone"> 01 48 47 48 49</a></p>
						<p><a href="#"><img src="<?= asset('email.svg'); ?>" alt="Email"> Par email</a></p>
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

			<section class="gallery">
				<div class="small-container">
					<div class="content">
						<?php foreach (get_field('realisation_images') as $image) : ?>
							<div class="image" style="background-image: url(<?= $image['url']; ?>);"></div>
						<?php endforeach; ?>
					</div>
				</div>
			</section>

			<!-- À faire -->
			<section class="more-realisations">
				<div class="large-container-left">
					<div class="content">
						<div class="left">
							<p><strong>Quelques <br>projets</strong></p>
							<a href="#">Consulter tous <br>les projets</a>
						</div>
						<div class="images">
							<div style="background-image: url(<?= asset('home-showroom.jpg'); ?>);"></div>
							<div style="background-image: url(<?= asset('home-showroom.jpg'); ?>);"></div>
							<div style="background-image: url(<?= asset('home-showroom.jpg'); ?>);"></div>
							<div style="background-image: url(<?= asset('home-showroom.jpg'); ?>);"></div>
						</div>
						<div class="right">40</div>
					</div>
				</div>
			</section>

			<section class="realisations-conclusion">
				<div class="large-container">
					<div class="conclusion">
						<h2>Une question ?</h2>
						<?php the_field('ask', 'option'); ?>
						<a href="#" class="button">Contactez-nous</a>
					</div>
				</div>
			</section>


		</div>

	<?php endwhile; endif; ?>

<?php
get_footer();