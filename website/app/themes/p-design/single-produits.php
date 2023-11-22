<?php
global $post;
get_header();

$term = get_the_terms($post->ID, 'categories')[0];

$parent_term_id = wp_get_term_taxonomy_parent_id($term->term_id, 'categories');
$parent_taxonomy = get_term($parent_term_id);

?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

		<div class="page page-default single-products product-<?= $post->post_name; ?>">

			<div class="page-banner">
				<div class="large-container-left">
					<div class="content" style="background-image: url(<?= get_field('product_banner')['url']; ?>);">
					</div>
				</div>
			</div>

			<div class="product-breadcrumbs">
				<div class="large-container">
					<div class="breadcrumbs">
						<a href="<?= home_url('/categories/nouveautes'); ?>"><strong>Nouveautés</strong></a>
						<?php if ($parent_term_id) : ?>
							<span>|</span>
							<a href="<?= get_term_link($parent_term_id); ?>"><?= $parent_taxonomy->name; ?></a>
						<?php endif; ?>
						<?php if ($term->name !== "Nouveautés") : ?>
							<span>|</span>
							<a href="<?= get_term_link($term->term_id); ?>"><?= $term->name; ?></a>
						<?php endif; ?>
						<span>|</span>
						<a href="<?php the_permalink(); ?>" class="current"><?php the_title(); ?></a>
					</div>
				</div>
			</div>

			<section class="single-product-content">
				<div class="large-container">
					<div class="product-main">
						<div class="product-primary">
							<div class="image">
								<img src="<?= get_field('product_featured_image')['url']; ?>" alt="<?= get_field('product_featured_image')['alt']; ?>">
							</div>
							<div class="infos">
								<h1><?php the_title(); ?></h1>
								<?php the_field('product_description'); ?>
								<?php if (get_field('product_price')) : ?>
									<div class="price"><?= get_field('product_price'); ?> € HT</div>
								<?php endif; ?>

								<?php if (get_field('product_colors')) : ?>
									<div class="colors">
										<p>Disponible dans les couleurs</p>
										<div class="colors-list">
											<?php foreach (get_field('product_colors') as $color) : ?>
												<span class="<?= $color; ?>"></span>
											<?php endforeach; ?>
										</div>
									</div>
								<?php endif; ?>

								<?php if (get_field('product_images')) : ?>
									<div class="images">
										<?php foreach (get_field('product_images') as $photo) : ?>
											<img src="<?= $photo['url']; ?>" alt="<?= $photo['alt']; ?>">
										<?php endforeach; ?>
									</div>
								<?php endif; ?>

								<!-- <div class="add-selection">
									<a href="#" class="button">Ajouter à ma sélection</a>
								</div> -->
								<!-- <div class="actions">
									<a href="#"><img src="<?= asset('print.svg'); ?>"> Imprimer</a>
									<a href="#"><img src="<?= asset('email.svg'); ?>"> Envoyer à un ami</a>
								</div> -->
							</div>
						</div>
						<div class="product-secondary">
							<div class="notice">
								<h2>Fiche technique</h2>
								<table>
									<?php if (have_rows('product_details')) : while (have_rows('product_details')) : the_row(); ?>
											<tr>
												<td><?php the_sub_field('key'); ?></td>
												<td><?php the_sub_field('value'); ?></td>
											</tr>
									<?php endwhile;
									endif; ?>
								</table>
							</div>
							<div class="more">
								<?php the_field('product_more'); ?>
							</div>
						</div>
					</div>

					<!-- À faire -->
					<!-- <div class="history">
						<div class="title">
							<img src="<?= asset('logo-short.svg'); ?>" alt="Pdesign">
							<span>Déja consulté</span>
						</div>
						<div class="history-content with-arrows" id="history-content">
							<div class="item">
								<a href="#"><img src="<?= asset('chaise.jpg'); ?>" alt=""></a>
							</div>
							<div class="item">
								<a href="#"><img src="<?= asset('chaise.jpg'); ?>" alt=""></a>
							</div>
							<div class="item">
								<a href="#"><img src="<?= asset('chaise.jpg'); ?>" alt=""></a>
							</div>
							<div class="item">
								<a href="#"><img src="<?= asset('chaise.jpg'); ?>" alt=""></a>
							</div>
							<div class="item">
								<a href="#"><img src="<?= asset('chaise.jpg'); ?>" alt=""></a>
							</div>
							<div class="item">
								<a href="#"><img src="<?= asset('chaise.jpg'); ?>" alt=""></a>
							</div>
							<div class="item">
								<a href="#"><img src="<?= asset('chaise.jpg'); ?>" alt=""></a>
							</div>
							<div class="history-arrow">
								<div class="arrow-up" id="history-arrow-up"><img src="<?= asset('arrow-up.svg'); ?>" alt=""></div>
								<div class="arrow-up" id="history-arrow-down"><img src="<?= asset('arrow-down.svg'); ?>" alt=""></div>
							</div>
						</div>
					</div> -->
				</div>
			</section>

			<!-- À faire -->
			<?php $otherProducts = new WP_Query(['post_type' => 'produits', 'tax_query' => [['taxonomy' => 'categories', 'field' => 'term_id', 'terms' => $term->term_id,]], 'posts_per_page' => 4, 'post__not_in' => [get_the_ID()]]) ?>
			<?php if ($otherProducts->have_posts()) : ?>
				<section class="products-more">
					<div class="large-container">
						<div class="content">
							<div class="text">
								<h2>Vous <br>aimerez <br>aussi !</h2>
								<p>Quelques produits <br>connexes</p>
							</div>
							<div class="products">
								<?php while ($otherProducts->have_posts()) : $otherProducts->the_post(); ?>
									<div class="product">
										<a href="<?php the_permalink(); ?>">
											<img src="<?= get_field('product_featured_image')['url']; ?>" alt="<?= get_field('product_featured_image')['alt']; ?>">
											<div class="name">
												<p><?php the_title(); ?></p>
											</div>
										</a>
									</div>
								<?php wp_reset_postdata();
								endwhile; ?>
							</div>
						</div>
					</div>
				</section>
			<?php endif; ?>
		</div>

<?php endwhile;
endif; ?>

<?php
get_footer();
