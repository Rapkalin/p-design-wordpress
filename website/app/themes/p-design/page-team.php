<?php
/**
 * Template Name: L'Équipe
 *
 */
global $post;

get_header();
?>

	<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

		<div class="page page-default page-<?= $post->post_name; ?>">
			<div class="page-title">
				<div class="large-container-left">
					<div class="page-title-slider">
						<div class="slide" style="background-image: url(<?= asset('home-values.jpg'); ?>)">
							<div class="title">
								<h1><?php the_title(); ?></h1>
							</div>
						</div>
					</div>
				</div>
			</div>

            <div class="page-overlay-intro">
                <div class="large-container-left">
                    <div class="content">
                        <?php the_field('team_introduction'); ?>
                    </div>
                </div>
            </div>

            <?php if( have_rows('team_values') ): while( have_rows('team_values') ): the_row(); ?>
                <section class="home-values">
                    <div class="large-container">
                        <div class="content">
                            <div class="text">
                                <div class="section-title">
                                    <h2><?php the_sub_field('team_values_title'); ?></h2>
                                </div>

                                <?php if( have_rows('team_values_list_descriptions') ): while( have_rows('team_values_list_descriptions') ): the_row(); ?>
                                    <div class="item">
                                        <?php the_sub_field('team_values_description'); ?>
                                    </div>
                                <?php endwhile; endif; ?>
                            </div>
                            <div class="image" style="background-image: url(<?= the_sub_field('team_values_image')['url']; ?>);"></div>
                        </div>
                    </div>
                </section>
            <?php endwhile; endif; ?>

			<!--<section class="realisations-list">
				<div class="large-container">
					<div class="realisations-categories">

						<div class="category">
							<div class="category-title">
								<h2>15 Bars</h2>
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
								<h2>6 Brasseries</h2>
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
					</div>
				</div>
			</section>-->

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