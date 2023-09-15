<?php
/*
 * This is the page users will see logged out.
 * You can edit this, but for upgrade safety you should copy and modify this file into your template folder.
 * The location from within your template folder is plugins/login-with-ajax/ (create these directories if they don't exist)
*/
?>
	<div class="lwa lwa-default">
		<h2>Connexion</h2>
        <form class="lwa-form" action="<?php echo esc_url(LoginWithAjax::$url_login); ?>" method="post">
        	<div>
				<span class="lwa-status"></span>
				<div class="form-content">
					<input type="text" name="log" placeholder="Email*" />
					<input type="password" name="pwd" placeholder="Mot de passe*" />
					<?php do_action('login_form'); ?>
					<div class="lwa-submit">
						<div class="lwa-submit-button">
							<input type="submit" name="wp-submit" id="lwa_wp-submit" value="Connexion" tabindex="100" />
							<input type="hidden" name="lwa_profile_link" value="<?php echo esc_attr($lwa_data['profile_link']); ?>" />
							<input type="hidden" name="login-with-ajax" value="login" />
							<?php if( !empty($lwa_data['redirect']) ): ?>
								<input type="hidden" name="redirect_to" value="<?php echo esc_url($lwa_data['redirect']); ?>" />
							<?php endif; ?>
						</div>
					</div>
					<div class="lwa-submit-links">
						<div class="checkbox-btn">
							<input name="rememberme" type="checkbox" class="lwa-rememberme" value="forever" id="rememberme">
							<label for="rememberme">Se souvenir de moi</label>
						</div>
						<br />
						<?php if( !empty($lwa_data['remember']) ): ?>
							<a class="lwa-links-remember" href="<?php echo esc_attr(LoginWithAjax::$url_remember); ?>" title="<?php esc_attr_e('Password Lost and Found','login-with-ajax') ?>">
								<?php esc_attr_e('Lost your password?','login-with-ajax') ?>
							</a>
						<?php endif; ?>
						<?php if ( get_option('users_can_register') && !empty($lwa_data['registration']) ) : ?>
							<a href="<?php echo esc_attr(LoginWithAjax::$url_register); ?>" class="lwa-links-register lwa-links-modal">
								<?php esc_html_e('Register','login-with-ajax') ?>
							</a>
						<?php endif; ?>
					</div>
				</div>
            </div>
		</form>
        <?php if( !empty($lwa_data['remember']) && $lwa_data['remember'] == 1 ): ?>
			<form class="lwa-remember" action="<?php echo esc_attr(LoginWithAjax::$url_remember) ?>" method="post" style="display:none;">
				<div>
					<span class="lwa-status"></span>
					<div class="form-content">
						<p><strong><?php esc_html_e("Forgotten Password", 'login-with-ajax'); ?></strong></p>
						<?php $msg = __("Enter username or email", 'login-with-ajax'); ?>
						<input type="text" name="user_login" class="lwa-user-remember" value="<?php echo esc_attr($msg); ?>" onfocus="if(this.value == '<?php echo esc_attr($msg); ?>'){this.value = '';}" onblur="if(this.value == ''){this.value = '<?php echo esc_attr($msg); ?>'}" />
						<?php do_action('lostpassword_form'); ?>
						<div class="lwa-remember-buttons">
							<input type="submit" value="<?php esc_attr_e("Get New Password", 'login-with-ajax'); ?>" class="lwa-button-remember" />
							<a href="#" class="lwa-links-remember-cancel"><?php esc_html_e("Cancel", 'login-with-ajax'); ?></a>
							<input type="hidden" name="login-with-ajax" value="remember" />
						</div>
					</div>
				</div>
			</form>
        <?php endif; ?>
		<?php if( get_option('users_can_register') && !empty($lwa_data['registration']) && $lwa_data['registration'] == 1 ): ?>
			<div class="lwa-register lwa-register-default lwa-modal">
				<div class="popup-close">
					<img src="<?= asset('close.svg'); ?>" alt="Fermer">
				</div>
				<div class="popup-main">
					<div class="popup-content">
						<h2>Inscription</h2>
						<form class="lwa-register-form" action="<?php echo esc_attr(LoginWithAjax::$url_register); ?>" method="post">
							<div>
								<span class="lwa-status"></span>
								<input type="text" name="user_email" id="user_email" class="user_register_email_field input" size="25" placeholder="Email*" />
								<input type="hidden" name="user_login" id="user_login" class="input user_register_login_field" />
								<?php do_action('register_form'); ?>
								<?php do_action('lwa_register_form'); ?>
								<input type="submit" name="wp-submit" id="wp-submit" class="button-primary" value="Inscription" />
								<input type="hidden" name="login-with-ajax" value="register" />
							</div>
						</form>
					</div>
				</div>
			</div>
		<?php endif; ?>
	</div>