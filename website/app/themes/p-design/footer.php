<footer class="footer">
	<div class="large-container-left">
		<div class="footer-content">
			<div class="logo">
				<img src="<?= asset('logo-footer.svg'); ?>" alt="">
			</div>
			<div class="main">
				<div class="content">
					<div class="item">
						<?php the_field('footer_address', 'option'); ?>
					</div>
					<nav class="item main-nav">
						<ul>
							<?php wp_nav_menu([
								'theme_location' => 'footer-menu',
								'menu_id' => 'footer-menu',
								'items_wrap' => '%3$s',
								'container' => false
							]); ?>
						</ul>
					</nav>
					<nav class="item secondary-nav">
						<ul>
							<?php wp_nav_menu([
								'theme_location' => 'products-menu',
								'menu_id' => 'products-menu',
								'items_wrap' => '%3$s',
								'container' => false
							]); ?>
						</ul>
					</nav>
				</div>
				<div class="legal">
					©2020 PDESIGN Tous droits réservés
					<span>|</span>
					Design Agence 426c
					<span>|</span>
					<a href="#">Mentions légales</a>
				</div>
			</div>
		</div>
	</div>
</footer>

<!-- <?php if (!isset($_COOKIE['rgpd']) || $_COOKIE['rgpd'] != 1) : ?>
			<div class="rgpd" id="rgpd">
				<div class="container">
					<p>En poursuivant votre navigation, vous acceptez l’utilisation de cookies. <a href="https://www.agencebrigit.com/politique-de-confidentialite">En savoir plus</a></p>
					<div class="rgpd-button" id="rgpd-button">Ok</div>
				</div>
			</div>
		<?php endif; ?> -->

</div>

<?php wp_footer(); ?>

</body>

</html>