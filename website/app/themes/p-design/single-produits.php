<?php
global $post;
get_header();
?>

	<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

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
						<a href="#"><strong>Nouveautés</strong></a>
						<span>|</span>
						<a href="#">Chaises</a>
						<span>|</span>
						<a href="#">Plastique</a>
						<span>|</span>
						<a href="#" class="current">Chaise Denver</a>
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
								<!-- <div class="price">52,25 € HT</div> -->

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
								
								<div class="images">
									<?php foreach(get_field('product_images') as $photo) : ?>
										<img src="<?= $photo['url']; ?>" alt="<?= $photo['alt']; ?>">
									<?php endforeach; ?>
								</div>
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
									<?php if( have_rows('product_details') ): while( have_rows('product_details') ): the_row(); ?>
										<tr>
											<td><?php the_sub_field('key'); ?></td>
											<td><?php the_sub_field('value'); ?></td>
										</tr>
									<?php endwhile; endif; ?>
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
			<section class="products-more">
				<div class="large-container">
					<div class="content">
						<div class="text">
							<h2>Vous <br>aimerez <br>aussi !</h2>
							<p>Quelques produits <br>connexes</p>
						</div>
						<div class="products">
							<?php for ($i=0; $i < 4; $i++) : ?>
								<div class="product">
									<img src="<?= asset('chaise.jpg') ?>" alt="">
									<div class="name">
										<p>Pompei sapiens<br><span style="display: none;">54,50 € HT</span></p>
									</div>
								</div>
							<?php endfor; ?>
						</div>
					</div>
				</div>
			</section>


		</div>

	<?php endwhile; endif; ?>

<?php
get_footer();