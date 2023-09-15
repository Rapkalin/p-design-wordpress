<?php
global $post;
get_header();
?>

	<?php if ( have_posts() ) : ?>

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
						<a href="#"><strong>Nouveautés</strong></a>
						<span>|</span>
						<a href="#">Chaises</a>
						<span>|</span>
						<a href="#">Plastique</a>
					</div>
				</div>
			</div>

			<div class="archive-products-content">
				<div class="large-container">
					<div class="archive-filters">
						<p><strong>Trier par :</strong></p>
						<a href="#">Ordre alphabétique</a>
						<a href="#">Sur commande</a>
						<a href="#">Références</a>
						<a href="#">En stock</a>
						<a href="#">Prix</a>
					</div>
					<div class="archive-products-list">
						<div class="products">
							<?php for ($i=0; $i < 12; $i++) : ?>
								<div class="product">
									<a href="<?= home_url('produits/chaise-denver/'); ?>">
										<img src="<?= asset('chaise.jpg'); ?>">
										<div class="title">
											Fugias dem ipsa
										</div>
									</a>
								</div>
							<?php endfor; ?>
							<div class="products-pagination">
								<div class="products-navigation">
									<div class="prev"><img src="<?= asset('arrow-prev.svg') ?>" class="svg"></div>
									<div class="number-ratio">
										<span>7</span>
										<span>40</span>
									</div>
									<div class="next"><img src="<?= asset('arrow-next.svg') ?>" class="svg"></div>
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
							</div>
						</div>
					</div>
				</div>
			</div>

			<section class="archive-products-conclusion">
				<div class="large-container">
					<div class="conclusion">
						<h2>Une question ?</h2>
						<?php the_field('ask', 'option'); ?>
						<a href="#" class="button">Contactez-nous</a>
					</div>
				</div>
			</section>

			<?php while ( have_posts() ) : the_post(); ?>
			<?php endwhile; ?>
		</div>

	<?php endif; ?>

<?php
get_footer();