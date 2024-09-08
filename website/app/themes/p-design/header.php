<!doctype html>
<html <?php language_attributes(); ?>>

<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">

	<link rel="icon" type="image/png" sizes="96x96" href="<?= asset('favicon/favicon-96x96.png'); ?>">
	<link rel="icon" type="image/png" sizes="32x32" href="<?= asset('favicon/favicon-32x32.png'); ?>">
	<link rel="icon" type="image/png" sizes="16x16" href="<?= asset('favicon/favicon-16x16.png'); ?>">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

	<div id="app" class="app">

		<header class="header">
			<div class="header-container">
				<div class="header-left">
					<div class="logo">
						<a href="<?= home_url('/'); ?>"><img src="<?= asset('logo.svg') ?>" alt="Paris Design"></a>
					</div>
					<nav class="navigation">
						<div class="menu">
							<div class="menu-mobile"><img src="<?= asset('menu.svg'); ?>" alt=""></div>
							<ul class="menu-content">
								<?php wp_nav_menu([
									'theme_location' => 'header-menu',
									'menu_id' => 'header-menu',
									'items_wrap' => '%3$s',
									'container' => false
								]); ?>
								<li class="menu-actions">
									<div class="actions">
										<!-- <a href="#" data-popup="popup-search" class="popup-link"><img src="<?= asset('search.svg'); ?>" class="svg" alt="Rechercher"></a>
										<?php if (is_user_logged_in()) : ?>
											<a href="<?= home_url('ma-selection') ?>" class="account"><img src="<?= asset('account.svg'); ?>" class="svg" alt="Compte"> Ma sélection</a>
										<?php else : ?>
											<a href="#" data-popup="popup-auth" class="popup-link account"><img src="<?= asset('account.svg'); ?>" class="svg" alt="Compte"> Connexion</a>
										<?php endif; ?> -->
										<a href="tel:+33148474849" class="number"><img src="<?= asset('phone.svg'); ?>" class="svg" alt="Téléphone"> 01 48 47 48 49</a>
									</div>
								</li>
							</ul>
						</div>
					</nav>
				</div>
				<div class="header-right">
					<div class="actions">
						<!-- <a href="#" data-popup="popup-search" class="popup-link"><img src="<?= asset('search.svg'); ?>" class="svg" alt="Rechercher"></a> -->
						<!-- <?php if (is_user_logged_in()) : ?>
							<a href="<?= home_url('ma-selection') ?>" class="account"><img src="<?= asset('account.svg'); ?>" class="svg" alt="Compte"> Ma sélection</a>
						<?php else : ?>
							<a href="#" data-popup="popup-auth" class="popup-link account"><img src="<?= asset('account.svg'); ?>" class="svg" alt="Compte"> Connexion</a>
						<?php endif; ?> -->
						<a href="tel:+33158910526" class="number"><img src="<?= asset('phone.svg'); ?>" class="svg" alt="Téléphone"> 01 58 91 05 26</a>
					</div>
				</div>
				<div class="header-products-nav">
					<div class="header-products-nav-close"><img src="<?= asset('close.svg') ?>" alt=""></div>
					<div class="new">
						<a href="<?= home_url('/categories/nouveautes'); ?>">Nouveautés</a>
					</div>
					<div class="products">
						<ul>
							<?php wp_nav_menu([
								'theme_location' => 'products-menu',
								'menu_id' => 'products-menu',
								'items_wrap' => '%3$s',
								'container' => false
							]); ?>
						</ul>
					</div>
				</div>
			</div>
		</header>

		<div class="popup popup-search" id="popup-search">
			<div class="popup-close">
				<img src="<?= asset('close.svg') ?>" alt="Fermer">
			</div>
			<div class="popup-main">
				<div class="popup-content">
					<form action="/">
						<div class="search-group">
							<input type="text" name="s" placeholder="Rechercher...">
							<button type="submit"><img src="<?= asset('search.svg'); ?>" alt="Rechercher"></button>
						</div>
					</form>
				</div>
			</div>
		</div>

		<?php if (!is_user_logged_in()) : ?>
			<div class="popup popup-auth" id="popup-auth">
				<div class="popup-close">
					<img src="<?= asset('close.svg'); ?>" alt="Fermer">
				</div>
				<div class="popup-main">
					<div class="popup-content">
						<?= do_shortcode('[login-with-ajax registration=1 remember=1]'); ?>
					</div>
				</div>
			</div>
		<?php endif; ?>