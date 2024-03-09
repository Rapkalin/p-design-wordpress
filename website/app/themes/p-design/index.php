<?php
get_header();

$home = new WP_Query(['pagename' => 'accueil']);

$realisationsPageId = 21
?>

<div class="page page-home">
	<?php if ($home->have_posts()) : while ($home->have_posts()) : $home->the_post(); ?>

			<section class="home-video">
				<div class="slider" id="slider-home">
					<?php if (have_rows('home_slider')) : while (have_rows('home_slider')) : the_row(); ?>
							<?php
							// Variables 
							$image_video = get_sub_field('image_video');
							$mediaURL = $image_video == 'video' ? get_sub_field('video')['url'] : get_sub_field('image')['url'];
							$hashtag = get_sub_field('hashtag');
							$title = get_sub_field('title');
							$description = get_sub_field('description');
							?>
							<div class="slide" <?= $image_video == 'image' ? 'style="background-image:url(' . $mediaURL . ');"' : ''; ?>>
								<?php if ($image_video == 'video') : ?>
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
					<?php endwhile;
					endif; ?>
				</div>
			</section>

			<!-- Onglets produits -->
			<?php
			$home_products_categories = get_field('home_products_categories');
			if ($home_products_categories) :
			?>
				<section class="home-quick-access" id="home-quick-access">
					<div class="sections-container large-container-left">
						<div class="sections-col-1">
							<?php the_sub_field('title'); ?>
						</div>
						<div class="sections-col-2 tabs-navigation">
							<?php
							$i = 0;
							foreach ($home_products_categories as $category) :
							?>
								<a href="<?= get_term_link($category->term_id, 'categories'); ?>" data-tab="tab-product-<?= $category->term_id ?>" class="tab-link <?= $i === 0 ? "active" : "" ?>">
									<?= $category->name ?>
								</a>
							<?php $i++;
							endforeach; ?>
						</div>
					</div>
				</section>

				<section class="home-refs-tabs">
					<div class="large-container">
						<div class="tabs-content">
							<?php
							$j = 0;
							foreach ($home_products_categories as $category) :
							?>
								<div class="tab <?= $j === 0 ? "active" : ""; ?>" id="tab-product-<?= $category->term_id ?>">
									<div class="content">
										<div class="full">
											<div class="title">
												<h2><?= $category->name; ?></h2>
												<div class="line line-pink"></div>
											</div>
											<div class="body">
												<div class="products">
													<?php
													$products_query = new WP_Query(['post_type' => 'produits', 'tax_query' => [
														[
															'taxonomy' => 'categories',
															'field' => 'term_id',
															'terms' => $category->term_id,
														]
													], 'posts_per_page' => 14]);
													if ($products_query->have_posts()) : while ($products_query->have_posts()) : $products_query->the_post(); ?>
															<a href="<?php the_permalink(); ?>">
																<img src="<?= get_field('product_featured_image')['url']; ?>" alt="<?= get_field('product_featured_image')['alt']; ?>">
															</a>
														<?php endwhile;
														$home->reset_postdata();
														?>
													<?php else : ?>
														<p>Aucun produit trouvé</p>
													<?php endif; ?>
													<a href="<?= get_term_link($category->term_id, 'categories'); ?>" class="more"><img src="<?= asset('plus.svg'); ?>" alt=""></a>
												</div>
											</div>
										</div>
									</div>
								</div>
							<?php $j++;
							endforeach; ?>
						</div>
					</div>
				</section>
			<?php endif; ?>
			<?php if (have_rows('home_showroom')) : while (have_rows('home_showroom')) : the_row(); ?>
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
			<?php endwhile;
			endif; ?>

			<?php if (have_rows('home_realisations')) : while (have_rows('home_realisations')) : the_row(); ?>
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
								<?php
								$last_realisation = wp_get_recent_posts(['post_type' => 'realisations', 'posts_per_page' => 1]);
								$realisations_query = new WP_Query(['post_type' => 'realisations', 'posts_per_page' => 1]);
								if ($last_realisation && $last_realisation[0]) : ?>
									<?php $realisation_url = get_field('realisation_banner', $last_realisation[0]['ID'])['url'] ?>
									<div class="center home-realisation-cover" style="background-image: url('<?= $realisation_url; ?>')">
										<img src="<?= $realisation_url; ?>" />
									</div>
								<?php endif; ?>
							</div>
						</div>
					</section>
			<?php endwhile;
			endif; ?>

			<section class="home-testimonials">
				<div class="large-container">
					<div class="slider-testimonials" id="slider-testimonials">
						<?php if (have_rows('home_testimonials')) : while (have_rows('home_testimonials')) : the_row(); ?>
								<div class="slide" style="background-image: url(<?= get_sub_field('image')['url']; ?>);">
									<div class="content">
										<div class="quote">
											<p><?php the_sub_field('quote'); ?></p>
										</div>
										<div class="author"><?php the_sub_field('author'); ?></div>
										<div class="brand"><?php the_sub_field('brand'); ?></div>
									</div>
								</div>
						<?php endwhile;
						endif; ?>
					</div>
				</div>
			</section>

			<?php if (have_rows('home_references')) : while (have_rows('home_references')) : the_row(); ?>
					<section class="home-clients">
						<div class="section-title">
							<h2><?php the_sub_field('title'); ?></h2>
						</div>
						<div class="content">
							<div class="slider-clients" id="slider-clients">
								<?php if (have_rows('logos')) : while (have_rows('logos')) : the_row(); ?>
										<div class="slide">
											<div class="logo">
												<img src="<?= get_sub_field('image')['url']; ?>" alt="">
											</div>
										</div>
								<?php endwhile;
								endif; ?>
							</div>
						</div>
					</section>
			<?php endwhile;
			endif; ?>

			<section class="home-socials">
				<div class="section-title">
					<h2>Suivez-nous</h2>
				</div>
				<div class="content">
					<div class="socials">
						<?php if (have_rows('socials', 'option')) : while (have_rows('socials', 'option')) : the_row(); ?>
								<?php $iconName = get_sub_field('social') . '.svg'; ?>
								<a href="<?= get_sub_field('link'); ?>" target="_blank">
									<img src="<?= asset($iconName); ?>" alt="<?= get_sub_field('social') ?>">
								</a>
						<?php endwhile;
						endif; ?>
					</div>
				</div>
			</section>

	<?php endwhile;
	endif; ?>
</div>

<?php
get_footer();
