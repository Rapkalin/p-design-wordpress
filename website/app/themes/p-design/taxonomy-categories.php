<?php
global $post;
get_header();


$term_id = get_queried_object()->term_id;
$taxonomy = get_term($term_id);

$parent_term_id = wp_get_term_taxonomy_parent_id($term_id, 'categories');
$parent_taxonomy = get_term($parent_term_id);

$order = isset($_GET['ordre']) ? $_GET['ordre'] : "";

$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
$args = ['post_type' => 'produits', 'tax_query' => [['taxonomy' => 'categories', 'field' => 'term_id', 'terms' => $term_id,]], 'posts_per_page' => -1, 'paged' => $paged];
if ($order) {
	switch ($order) {
		case 'nom':
			$args += ['orderby' => 'title', 'order' => 'asc'];
			break;
		case 'sur-commande':
			$args += ['orderby' => 'meta_value', 'meta_key' => 'product_on_order', 'order' => 'desc'];
			break;
		case 'references':
			$args += ['orderby' => 'meta_value', 'meta_key' => 'product_reference', 'order' => 'asc'];
			break;
		case 'en-stock':
			$args += ['orderby' => 'meta_value', 'meta_key' => 'product_stock', 'order' => 'desc'];
			break;
		case 'prix':
			$args += ['orderby' => 'meta_value', 'meta_key' => 'product_price', 'order' => 'asc'];
			break;
		default:
			break;
	}
}

$products = new WP_Query($args);
?>

<?php if (have_posts()) : ?>

	<div class="page page-default archive-products archive-products-<?= $post->post_name; ?>">

		<div class="page-banner">
			<div class="large-container-left">
				<div class="content" style="background-image: url(<?= asset('home-showroom.jpg'); ?>);"></div>
			</div>
		</div>

		<div class="page-title">
			<div class="large-container">
				<h1><?php the_archive_title(); ?></h1>
				<div class="breadcrumbs">
					<a href="<?= home_url('/categories/nouveautes'); ?>"><strong>Nouveautés</strong></a>
					<?php if ($parent_term_id) : ?>
						<span>|</span>
						<a href="<?= get_term_link($parent_term_id); ?>"><?= $parent_taxonomy->name; ?></a>
					<?php endif; ?>
					<?php if ($taxonomy->name !== "Nouveautés") : ?>
						<span>|</span>
						<a href="<?= get_term_link($term_id); ?>"><?= $taxonomy->name; ?></a>
					<?php endif; ?>
				</div>
			</div>
		</div>

		<?php if ($products->have_posts()) : ?>
			<div class="archive-products-content">
				<div class="large-container">
					<div class="archive-filters">
						<p><strong>Trier par :</strong></p>
						<a href="<?= get_term_link($term_id); ?>/?ordre=nom">Ordre alphabétique</a>
						<a href="<?= get_term_link($term_id); ?>/?ordre=sur-commande">Sur commande</a>
						<a href="<?= get_term_link($term_id); ?>/?ordre=references">Références</a>
						<a href="<?= get_term_link($term_id); ?>/?ordre=en-stock">En stock</a>
						<a href="<?= get_term_link($term_id); ?>/?ordre=prix">Prix</a>
					</div>
					<div class="archive-products-list">
						<div class="products">
							<?php while ($products->have_posts()) : $products->the_post(); ?>
								<div class="product">
									<a href="<?php the_permalink() ?>">
										<img src="<?= get_field('product_featured_image')['url']; ?>" alt="<?= get_field('product_featured_image')['alt']; ?>">
										<div class="title">
											<?php the_title(); ?>
										</div>
									</a>
								</div>
							<?php endwhile; ?>
						</div>

						<!-- <div class="products-pagination">
							<div class="products-navigation">
								<?php if (get_previous_posts_link(null, $products->max_num_pages)) : ?>
									<div class="prev">
										<?php $previous = "<img src=" . get_asset('arrow-prev.svg') . " class='svg'>"; ?>
										<?= get_previous_posts_link($next, $products->max_num_pages) ?>
									</div>
								<?php endif; ?>
								<div class="number-ratio">
									<span>7</span>
									<span>40</span>
								</div>
								<?php if (get_next_posts_link(null, $products->max_num_pages)) : ?>
									<div class="next">
										<?php $next = "<img src=" . get_asset('arrow-next.svg') . " class='svg'>"; ?>
										<?= get_next_posts_link($next, $products->max_num_pages) ?>
									</div>
								<?php endif; ?>

							</div>
							<div class="posts-per-page">
								<a href="#">10</a>
								<span>|</span>
								<a href="#" class="active">25</a>
								<span>|</span>
								<a href="#">50</a>
								<span>|</span>
								<a href="#">100</a>
								<span>|</span>
								<a href="#">Tout</a>
							</div>
						</div> -->
					</div>
				</div>
			</div>
		<?php
		endif; ?>

		<?php get_template_part('template-parts/content', 'contact-us'); ?>
	</div>

<?php endif; ?>

<?php
get_footer();
