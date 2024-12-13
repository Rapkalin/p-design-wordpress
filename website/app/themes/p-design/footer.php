
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
									<li><a href="#">Projets</a></li>
									<li><a href="#">Produits</a></li>
									<li><a href="#">Showroom</a></li>
									<li><a href="#">Contact</a></li>
								</ul>
							</nav>
							<nav class="item secondary-nav">
								<ul>
									<li>
										<a href="#">Produits intérieur</a>
										<ul>
											<li><a href="#">Chaises</a></li>
											<li><a href="#">Fauteuils</a></li>
											<li><a href="#">Tabourets</a></li>
											<li><a href="#">Banquette salons</a></li>
											<li><a href="#">Piettements de table</a></li>
											<li><a href="#">Plateau table</a></li>
											<li><a href="#">Compléments</a></li>
										</ul>
									</li>
								</ul>
							</nav>
							<nav class="item secondary-nav">
								<ul>
									<li>
										<a href="#">Produits extérieur</a>
										<ul>
											<li><a href="#">Chaises</a></li>
											<li><a href="#">Fauteuils</a></li>
											<li><a href="#">Tabourets</a></li>
											<li><a href="#">Banquette salons</a></li>
											<li><a href="#">Piettements de table</a></li>
											<li><a href="#">Plateau table</a></li>
											<li><a href="#">Compléments</a></li>
										</ul>
									</li>
								</ul>
							</nav>
						</div>
						<div class="legal">
							©2020 PDESIGN Tous droits réservés
							<span>|</span>
							Design Agence 426c
							<span>|</span>
                            <a href="<?= home_url('/mentions-legales') ?>">Mentions légales</a>						</div>
					</div>
				</div>
			</div>
		</footer>

		<!-- <?php if (! isset($_COOKIE['rgpd']) || $_COOKIE['rgpd'] != 1) : ?>
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
