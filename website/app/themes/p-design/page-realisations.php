<?php
/**
 * Template Name: Réalisations
 */
global $post;

get_header();
?>

	<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

		<div class="page page-default page-<?= $post->post_name; ?>">
			<div class="page-title">
				<div class="large-container-left">
					<div class="page-title-slider">
						<div class="slide" style="background-image: url(<?= asset('home-showroom.jpg'); ?>)">
							<div class="title">
								<h1><?php the_title(); ?></h1>
							</div>
						</div>
						<div class="slide" style="background-image: url(<?= asset('home-showroom.jpg'); ?>)">
							<div class="title">
								<h1><?php the_title(); ?></h1>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="page-overlay-intro">
				<div class="large-container-left">
					<div class="content-with-menus">
						<div class="menus">
							<a href="#" class="active">Tous les projets</a>
							<a href="#category-bar">Bar</a>
							<a href="#category-brasserie">Brasserie</a>
							<a href="#category-restaurant">Restaurant</a>
							<a href="#category-hotel">Hôtel</a>
						</div>
						<div class="row">
							<div class="left">
								<p><strong>Retrouvez l’ensemble de nos projets, classés par catégorie : </strong><br>bar, restaurant, brasserie, hôtels</p>
							</div>
							<div class="right">
								<div class="big">40</div>
							</div>
						</div>
					</div>
				</div>
			</div>


			<section class="realisations-list">
				<div class="large-container">
					<div class="realisations-categories">

                        <?php
                            $categories = get_terms('categories-realisations');

                            foreach ($categories as $category) :
                                $category_slug = $category->slug; // Remplacez 'votre-terme' par le slug du terme que vous souhaitez récupérer
                                $category = get_term_by('slug', $category_slug, 'categories-realisations'); // Remplacez 'category' par le nom de la taxonomie appropriée (category ou post_tag)

                                if ($category) {
                                    $args = array(
                                        'posts_per_page' => -1, // Récupérer tous les articles liés au terme
                                        'tax_query' => array(
                                            array(
                                                'taxonomy' => $category->taxonomy,
                                                'field'    => 'id',
                                                'terms'    => $category->term_id,
                                            ),
                                        ),
                                    );
                        ?>


                                        <div class="category">
                                            <div class="category-title">
                                                <h2 id="category-<?= $category->name ?>"><?= $category->count . ' ' . $category->name ?></h2>
                                                <p>Ecus vellique quis in consequi blandit et aceri dolupta nobit facerunt as maximagnis dolorem porest facillum abores xero officitae volupitatem hit officia dolorum et velique.</p>
                                            </div>
                                            <div class="category-realisations">
                                                <?php
                                                    $query = new WP_Query($args);

                                                    if ($query->have_posts()) {
                                                        $i = 1;
                                                        while ($query->have_posts()) {
                                                        $query->the_post();
                                                        $image = get_field('realisation_banner', get_the_ID());
                                                ?>
                                                        <div class="realisation">
                                                            <div class="number-ratio">
                                                                <span class="big"><?= $i; ?></span>
                                                                <span class="big"><?= $category->count ?> </span>
                                                            </div>
                                                                <a href="<?= the_permalink(); ?>">
                                                                    <div class="image-container">
                                                                        <div class="image" style="background-image: url(<?= $image['url'] ?>);">
                                                                            <div class="hover"><img src="<?= asset('plus.svg'); ?>" alt=""></div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="name"><?= the_title() . ' | Paris'; ?> </div>
                                                                </a>
                                                        </div>
                                                <?php
                                                            $i++;
                                                        }
                                                        wp_reset_postdata(); // Réinitialisez les données de l'article après la boucle
                                                    } else {
                                                        echo 'Aucun article trouvé pour ce terme.';
                                                    }
                                                ?>
                                                <div class="realisation">
                                                    <div class="more">
                                                        <a href="#" class="button button-white button-image">
                                                            <img src="<?= asset('plus.svg') ?>" class="svg">
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                            <?php

                                } else {
                                        echo 'Terme introuvable.';
                                }
                            endforeach;
                        ?>


<!--						<div class="category">
							<div class="category-title">
								<h2 id="category-brasserie">6 Brasseries</h2>
								<p>Ecus vellique quis in consequi blandit et aceri dolupta nobit facerunt as maximagnis dolorem porest facillum abores xero officitae volupitatem hit officia dolorum et velique.</p>
							</div>
							<div class="category-realisations">
								<?php /*for ($i=1; $i <= 7; $i++) : */?>
									<div class="realisation">
										<div class="number-ratio">
											<span class="big"><?php /*= $i; */?></span>
											<span class="big">15</span>
										</div>
										<a href="<?php /*= home_url('realisations/le-choupinet/'); */?>">
											<div class="image-container">
												<div class="image" style="background-image: url(<?php /*= asset('home-showroom.jpg') */?>);">
													<div class="hover"><img src="<?php /*= asset('plus.svg'); */?>" alt=""></div>
												</div>
											</div>
											<div class="name">Ismael | Paris</div>
										</a>
									</div>
								<?php /*endfor; */?>
								<div class="realisation">
									<div class="more">
										<a href="#" class="button button-white button-image">
											<img src="<?php /*= asset('plus.svg') */?>" class="svg">
										</a>
									</div>
								</div>
							</div>
						</div>

                        <div class="category">
                            <div class="category-title">
                                <h2 id="category-restaurant">6 Restaurants</h2>
                                <p>Ecus vellique quis in consequi blandit et aceri dolupta nobit facerunt as maximagnis dolorem porest facillum abores xero officitae volupitatem hit officia dolorum et velique.</p>
                            </div>
                            <div class="category-realisations">
                                <?php /*for ($i=1; $i <= 7; $i++) : */?>
                                    <div class="realisation">
                                        <div class="number-ratio">
                                            <span class="big"><?php /*= $i; */?></span>
                                            <span class="big">15</span>
                                        </div>
                                        <a href="<?php /*= home_url('realisations/le-choupinet/'); */?>">
                                            <div class="image-container">
                                                <div class="image" style="background-image: url(<?php /*= asset('home-showroom.jpg') */?>);">
                                                    <div class="hover"><img src="<?php /*= asset('plus.svg'); */?>" alt=""></div>
                                                </div>
                                            </div>
                                            <div class="name">Ismael | Paris</div>
                                        </a>
                                    </div>
                                <?php /*endfor; */?>
                                <div class="realisation">
                                    <div class="more">
                                        <a href="#" class="button button-white button-image">
                                            <img src="<?php /*= asset('plus.svg') */?>" class="svg">
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="category">
                            <div class="category-title">
                                <h2 id="category-hotel">6 Hotels</h2>
                                <p>Ecus vellique quis in consequi blandit et aceri dolupta nobit facerunt as maximagnis dolorem porest facillum abores xero officitae volupitatem hit officia dolorum et velique.</p>
                            </div>
                            <div class="category-realisations">
                                <?php /*for ($i=1; $i <= 7; $i++) : */?>
                                    <div class="realisation">
                                        <div class="number-ratio">
                                            <span class="big"><?php /*= $i; */?></span>
                                            <span class="big">15</span>
                                        </div>
                                        <a href="<?php /*= home_url('realisations/le-choupinet/'); */?>">
                                            <div class="image-container">
                                                <div class="image" style="background-image: url(<?php /*= asset('home-showroom.jpg') */?>);">
                                                    <div class="hover"><img src="<?php /*= asset('plus.svg'); */?>" alt=""></div>
                                                </div>
                                            </div>
                                            <div class="name">Ismael | Paris</div>
                                        </a>
                                    </div>
                                <?php /*endfor; */?>
                                <div class="realisation">
                                    <div class="more">
                                        <a href="#" class="button button-white button-image">
                                            <img src="<?php /*= asset('plus.svg') */?>" class="svg">
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>-->

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