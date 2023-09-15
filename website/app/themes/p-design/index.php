<?php
get_header();

$home = new WP_Query(['pagename' => 'accueil']);
?>

	<div class="page page-home">
		<?php if ( $home->have_posts() ) : while ( $home->have_posts() ) : $home->the_post(); ?>

			<section class="home-video">
				<div class="slider" id="slider-home">
					<?php if( have_rows('home_slider') ): while( have_rows('home_slider') ) : the_row(); ?>
						<?php 
							// Variables 
							$image_video = get_sub_field('image_video');
							$mediaURL = $image_video == 'video' ? get_sub_field('video')['url'] : get_sub_field('image')['url'];
							$hashtag = get_sub_field('hashtag');
							$title = get_sub_field('title');
							$description = get_sub_field('description');
						?>
						<div class="slide" <?= $image_video == 'image' ? 'style="background-image:url(' . $mediaURL . ');"' : ''; ?>>
							<?php if($image_video == 'video'): ?>
								<div class="video">
									<video autoplay preload muted loop poster="<?= asset('home-showroom.jpg'); ?>">
										<source src="<?= $mediaURL; ?>" type="video/mp4">
									</video>
								</div>
							<?php endif; ?>
							<div class="slide-content">
								<div class="sections-container large-container-left">
									<div class="sections-col-1">
										<div class="content">
											<?php if ($hashtag) : ?>
												<div class="hashtag"><?= $hashtag; ?></div>
											<?php endif; ?>
											<div class="title">
												<?= $title; ?>
											</div>
										</div>
									</div>
									<div class="sections-col-2">
										<div class="content">
											<?= $description; ?>
										</div>
									</div>
									<div class="sections-col-3">
										<div class="content slider-nav">
											<img src="<?= asset('white-arrow-prev.svg'); ?>" alt="Slide précédente" class="slider-arrow-prev">
											<img src="<?= asset('white-arrow-next.svg'); ?>" alt="Slide suivante" class="slider-arrow-next">
										</div>
									</div>
									<div class="sections-col-4">
										<div class="go-down">
											<img src="<?= asset('white-arrow-down.svg'); ?>" alt="Scoller" class="slider-arrow-down">
										</div>
									</div>
								</div>
							</div>
						</div>
					<?php endwhile; endif; ?>
				</div>
			</section>

			<!-- À faire -->
			<section class="home-quick-access" id="home-quick-access">
				<div class="sections-container large-container-left">
					<div class="sections-col-1">
						<?php the_sub_field('title'); ?>
					</div>
					<div class="sections-col-2 tabs-navigation">
						<a href="#" data-tab="tab-bar" class="tab-link active">Bar</a>
						<a href="#" data-tab="tab-brasserie" class="tab-link">Brasserie</a>
						<a href="#" data-tab="tab-restaurant" class="tab-link">Restaurant</a>
						<a href="#" data-tab="tab-hotel" class="tab-link">Hôtel</a>
					</div>
				</div>
			</section>
			
			<!-- À faire -->
			<section class="home-refs-tabs">
				<div class="large-container">
					<div class="tabs-content">
						<div class="tab active" id="tab-bar">
							<div class="content">
								<div class="left">
									<div class="title">
										<h2>Intérieur</h2>
										<div class="line line-pink"></div>
									</div>
									<div class="body">
										<div class="products">
											<a href="#"><img src="<?= asset('chaise-1.jpg') ?>" alt=""></a>
											<a href="#"><img src="<?= asset('chaise-2.jpg') ?>" alt=""></a>
											<a href="#"><img src="<?= asset('chaise-1.jpg') ?>" alt=""></a>
											<a href="#"><img src="<?= asset('chaise-2.jpg') ?>" alt=""></a>
											<a href="#"><img src="<?= asset('chaise-1.jpg') ?>" alt=""></a>
											<a href="#"><img src="<?= asset('chaise-2.jpg') ?>" alt=""></a>
											<a href="#" class="more"><img src="<?= asset('plus.svg'); ?>" alt=""></a>
										</div>
									</div>
								</div>
								<div class="right">
									<div class="title">
										<h2>Extérieur</h2>
										<div class="line line-blue"></div>
									</div>
									<div class="body">
										<div class="products">
											<a href="#"><img src="<?= asset('chaise-1.jpg') ?>" alt=""></a>
											<a href="#"><img src="<?= asset('chaise-2.jpg') ?>" alt=""></a>
											<a href="#"><img src="<?= asset('chaise-1.jpg') ?>" alt=""></a>
											<a href="#"><img src="<?= asset('chaise-2.jpg') ?>" alt=""></a>
											<a href="#"><img src="<?= asset('chaise-1.jpg') ?>" alt=""></a>
											<a href="#"><img src="<?= asset('chaise-2.jpg') ?>" alt=""></a>
											<a href="#" class="more"><img src="<?= asset('plus.svg'); ?>" alt=""></a>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="tab" id="tab-brasserie">
							<div class="content">
								<div class="left">
									<div class="title">
										<h2>Intérieur</h2>
										<div class="line line-pink"></div>
									</div>
									<div class="body">
										<div class="products">
											<a href="#"><img src="<?= asset('chaise-2.jpg') ?>" alt=""></a>
											<a href="#"><img src="<?= asset('chaise-1.jpg') ?>" alt=""></a>
											<a href="#"><img src="<?= asset('chaise-2.jpg') ?>" alt=""></a>
											<a href="#"><img src="<?= asset('chaise-1.jpg') ?>" alt=""></a>
											<a href="#"><img src="<?= asset('chaise-2.jpg') ?>" alt=""></a>
											<a href="#"><img src="<?= asset('chaise-1.jpg') ?>" alt=""></a>
											<a href="#" class="more"><img src="<?= asset('plus.svg'); ?>" alt=""></a>
										</div>
									</div>
								</div>
								<div class="right">
									<div class="title">
										<h2>Extérieur</h2>
										<div class="line line-blue"></div>
									</div>
									<div class="body">
										<div class="products">
											<a href="#"><img src="<?= asset('chaise-2.jpg') ?>" alt=""></a>
											<a href="#"><img src="<?= asset('chaise-1.jpg') ?>" alt=""></a>
											<a href="#"><img src="<?= asset('chaise-2.jpg') ?>" alt=""></a>
											<a href="#"><img src="<?= asset('chaise-1.jpg') ?>" alt=""></a>
											<a href="#"><img src="<?= asset('chaise-2.jpg') ?>" alt=""></a>
											<a href="#"><img src="<?= asset('chaise-1.jpg') ?>" alt=""></a>
											<a href="#" class="more"><img src="<?= asset('plus.svg'); ?>" alt=""></a>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</section>

			<?php if( have_rows('home_showroom') ): while( have_rows('home_showroom') ): the_row(); ?>
				<section class="home-showroom">
					<div class="large-container-left">
						<div class="banner" style="background-image: url(<?= get_sub_field('image')['url']; ?>);">
							<div class="content">
								<div class="section-title">
									<h2><?php the_sub_field('title'); ?></h2>
								</div>
								<p><?php the_sub_field('description'); ?></p>
								<?php if (get_sub_field('button')) : ?>
									<a href="<?= get_sub_field('button')['url']; ?>" class="button"><?= get_sub_field('button')['title']; ?></a>
								<?php endif; ?>
							</div>
						</div>
					</div>
				</section>
			<?php endwhile; endif; ?>

			<?php if( have_rows('home_values') ): while( have_rows('home_values') ): the_row(); ?>
				<section class="home-values">
					<div class="large-container">
						<div class="content">
							<div class="text">
								<div class="section-title">
									<h2><?php the_sub_field('title'); ?></h2>
								</div>
								
								<?php if( have_rows('values') ): while( have_rows('values') ): the_row(); ?>
									<div class="item">
										<?php the_sub_field('description'); ?>
									</div>
								<?php endwhile; endif; ?>
							</div>
							<div class="image" style="background-image: url(<?= get_sub_field('image')['url']; ?>);"></div>
						</div>
					</div>
				</section>
			<?php endwhile; endif; ?>

			<?php if( have_rows('home_about') ): while( have_rows('home_about') ): the_row(); ?>
				<section class="home-about" id="home-about">
					<div class="container">
						<div class="body">
							<div class="small-container">
								<div class="about-mask" id="about-mask"></div>
								<div class="content">
									<?php if( have_rows('numbers') ): while( have_rows('numbers') ): the_row(); ?>
										<div class="item">
											<div>
												<?php the_sub_field('description'); ?>
											</div>
										</div>
									<?php endwhile; endif; ?>
								</div>
							</div>
						</div>
						<div class="conclusion">
							<p><?php the_sub_field('description'); ?></p>
							<?php if (get_sub_field('link')) : ?>
								<a href="<?= get_sub_field('link')['url']; ?>" class="button button-white button-image"><img src="<?= asset('plus.svg'); ?>" class="svg"></a>
							<?php endif; ?>
						</div>
					</div>
				</section>
			<?php endwhile; endif; ?>

			<!-- À finir - Lier les réalisations (3 dernières - juste image à la une + title) -->
			<?php if( have_rows('home_realisations') ): while( have_rows('home_realisations') ): the_row(); ?>
				<section class="home-works">
					<div class="large-container-left">
						<div class="content">
							<div class="left">
								<div class="section-title">
									<h2><?php the_sub_field('title'); ?></h2>
								</div>
								<p><?php the_sub_field('description'); ?></p>
								<?php if (get_sub_field('link')) : ?>
									<a href="<?= get_sub_field('link')['url']; ?>" class="button button-white button-image"><img src="<?= asset('plus.svg'); ?>" class="svg"></a>
								<?php endif; ?>
							</div>
							<div class="center" style="background-image: url(<?= asset('home-works.jpg'); ?>);"></div>
							<div class="right">
								<div class="works-slider-dots">
									<div class="dot active"><span></span> Le café de la presse</div>
									<div class="dot"><span></span> Le Choupinet</div>
									<div class="dot"><span></span> La Maison Margaux</div>
									<div class="more"><img src="<?= asset('plus.svg'); ?>" alt="En voir plus"></div>
								</div>
							</div>
						</div>
					</div>
				</section>
			<?php endwhile; endif; ?>

			<section class="home-testimonials">
				<div class="large-container">
					<div class="slider-testimonials" id="slider-testimonials">
						<?php if( have_rows('home_testimonials') ): while( have_rows('home_testimonials') ): the_row(); ?>
							<div class="slide" style="background-image: url(<?= get_sub_field('image')['url']; ?>);">
								<div class="content">
									<div class="quote">
										<p><?php the_sub_field('quote'); ?></p>
									</div>
									<div class="author"><?php the_sub_field('author'); ?></div>
									<div class="brand"><?php the_sub_field('brand'); ?></div>
								</div>
							</div>
						<?php endwhile; endif; ?>
					</div>
				</div>
			</section>

			<?php if( have_rows('home_references') ): while( have_rows('home_references') ): the_row(); ?>
				<section class="home-clients">
					<div class="section-title">
						<h2><?php the_sub_field('title'); ?></h2>
					</div>
					<div class="content">
						<div class="slider-clients" id="slider-clients">
							<?php if( have_rows('logos') ): while( have_rows('logos') ): the_row(); ?>
								<div class="slide">
									<div class="logo">
										<img src="<?= get_sub_field('image')['url']; ?>" alt="">
									</div>
								</div>
							<?php endwhile; endif; ?>
						</div>
					</div>
				</section>
			<?php endwhile; endif; ?>

		<section class="home-socials">
			<div class="section-title">
				<h2>Suivez-nous</h2>
			</div>
			<div class="content">
				<div class="socials">
					<?php if( have_rows('socials', 'option') ): while( have_rows('socials', 'option') ): the_row(); ?>
						<?php $iconName = get_sub_field('social') . '.svg'; ?>
						<a href="<?= get_sub_field('link'); ?>" target="_blank">
							<img src="<?= asset($iconName); ?>" alt="<?= get_sub_field('social') ?>">
						</a>
					<?php endwhile; endif; ?>
				</div>
			</div>
		</section>

		<?php endwhile; endif; ?>
	</div>

<?php
get_footer();
