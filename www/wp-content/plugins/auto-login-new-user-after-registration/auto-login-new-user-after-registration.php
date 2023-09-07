<?php
/*
 * Plugin Name: Auto Login New User After Registration
 * Version: 1.9.6
 * Description: Automatically login new user right after they have registered. Add Password field to registration form. Disable admin notification emails of new user registrations and user password resets. Allow redirect of new user to any page right after they have registered. Add Firstname and Lastname field to registration form.
 * Author: Jeff Sherk
 * Author URI: http://www.iwebss.com/contact
 * Text Domain: auto-login-new-user-after-registration
 * Domain Path: /languages/
 * Plugin URI: http://www.iwebss.com/wordpress/1300-auto-login-new-user-registration-wordpress-plugin
 * Donate link: https://www.paypal.me/jsherk/10usd
 * License: GPLv3
 * License URI: http://www.gnu.org/licenses/gpl-3.0
 */


/* ****************************************************************************** */
function alnuar_add_links_to_admin_plugins_page($links) {

	$donate_url = 'https://www.paypal.me/jsherk/10usd';
	$donate_url = esc_url($donate_url);
	$donate_link = '<a href="'.$donate_url.'">'.__('DONATE','auto-login-new-user-after-registration').'</a>'; //DONATE

	array_unshift( $links, $donate_link ); //DONATE

	$url = get_admin_url() . 'options-general.php?page=auto-login-new-user-after-registration';
	$url = esc_url($url);
	$settings_link = '<a href="'.$url.'">'.__('Settings','auto-login-new-user-after-registration').'</a>';

	array_unshift( $links, $settings_link );

	return $links;
}
add_filter( 'plugin_action_links_'.plugin_basename(__FILE__), 'alnuar_add_links_to_admin_plugins_page' );


/* ****************************************************************************** */
function alnuar_add_meta_to_admin_plugins_page( $links, $file ) {

	if ( strpos( $file, plugin_basename(__FILE__) ) !== false ) {

		$donate_url = 'https://www.paypal.me/jsherk/10usd';
		$donate_url = esc_url($donate_url);

		$review_url = "https://wordpress.org/support/plugin/auto-login-new-user-after-registration/reviews/#new-post";

		$url = get_admin_url() . 'options-general.php?page=auto-login-new-user-after-registration';
		$url = esc_url($url);

		//$pluginbut = plugins_url( 'dbut.png', __FILE__ );
		//$new_links = array('<a href="'.$url.'">'.__('Settings','auto-login-new-user-after-registration').'</a>', '<a href="'.$donate_url.'">Thanks for supporting me! <img style="vertical-align:bottom;" height="30" src="'.$pluginbut.'"></a>'); //DONATE
		$donate_button = plugins_url( 'dbut-small.png', __FILE__ );
		$review_stars = plugins_url( 'stars-small.png', __FILE__ );
		$new_links = array('<a href="'.$url.'">'.__('Settings','auto-login-new-user-after-registration').'</a>', '<a href="'.$review_url.'">'.__('Leave a REVIEW','auto-login-new-user-after-registration').' <img style="vertical-align:text-top;" height="12" src="'.$review_stars.'"></a>', '<a href="'.$donate_url.'">'.__('Thanks for supporting me!','auto-login-new-user-after-registration').' <img style="vertical-align:bottom;" height="20" src="'.$donate_button.'"></a>'); //REVIEW & DONATE


		$links = array_merge( $links, $new_links );

	}

	return $links;
}
add_filter( 'plugin_row_meta', 'alnuar_add_meta_to_admin_plugins_page', 10, 2 );


/* ****************************************************************************** */
function alnuar_add_admin_settings_menu() {
	add_options_page( __('Auto Login New User After Registration by Jeff Sherk','auto-login-new-user-after-registration'), __('Auto Login New User After Registration','auto-login-new-user-after-registration'), 'activate_plugins', 'auto-login-new-user-after-registration', 'alnuar_auto_login_new_user_after_registration_options' );
}
add_action( 'admin_menu', 'alnuar_add_admin_settings_menu' );


/* ****************************************************************************** */
function alnuar_auto_login_new_user_after_registration( $user_id ) {

	if (get_option("alnuar_add_first_name_field") == true) {
		if (isset($_POST['first_name'])) {
			update_user_meta( $user_id, 'first_name', trim( $_POST['first_name'] ) );
		}
	}

	if (get_option("alnuar_add_last_name_field") == true) {
		if (isset($_POST['last_name'])) {
			update_user_meta( $user_id, 'last_name', trim( $_POST['last_name'] ) );
		}
	}

	if (get_option("alnuar_new_user_email_text_change") == true) {
		if (isset($_POST['alnuar_new_user_email_text_change'])) {
			update_user_meta( $user_id, 'alnuar_new_user_email_text_change', trim( $_POST['alnuar_new_user_email_text_change'] ) );
		}
	}

	if (get_option("alnuar_add_password_fields") == true) {
		if (isset($_POST['password1'])) {
			wp_set_password( $_POST['password1'], $user_id ); //Password previously checked in add_filter > registration_errors
		}
	}

	if (get_option("alnuar_auto_login_new_user_after_registration_enabled")) {
		
		if ( isset($_POST['alnuar']) && $_POST['alnuar'] == "yes" ) { // Need to verify that new user registration came from this plugin
		
			wp_set_current_user($user_id);
			wp_set_auth_cookie($user_id);
	
			$redirect = get_option("alnuar_auto_login_new_user_after_registration_redirect");
	
			if ($redirect == "") {
				global $_POST;
				if (isset($_POST['redirect_to']) && $_POST['redirect_to'] != "") {
					$redirect = $_POST['redirect_to'];
				} else {
					$redirect = get_home_url();
					$redirect .= "/wp-login.php?checkemail=registered";
				}
			}
			
			// This does the redirection if we are on default registration page. If we are on any other page, then do not redirect. This fixes WooCommerce bug.
			if (isset($_POST['wp-submit']) && $_POST['wp-submit'] == "Register") {
				wp_redirect($redirect);
				wp_new_user_notification($user_id, null, 'both'); //'admin' or blank sends admin notification email only. Anything else will send admin email and user email
				exit;
				
			} else {
				// do nothing and SKIP REDIRECTION (fixes WooCommerce bug)
				
			}
			
		}
		
	}
}
add_action( 'user_register', 'alnuar_auto_login_new_user_after_registration' );


/* ****************************************************************************** */
function alnuar_new_item_register_form() {
	if (get_option("alnuar_add_first_name_field") == true || get_option("alnuar_add_last_name_field") == true) {
		?>
		<p>
		<?php
	}

	if (get_option("alnuar_add_first_name_field") == true) {
		$first_name = ( ! empty( $_POST['first_name'] ) ) ? trim( $_POST['first_name'] ) : '';
	    ?>
			<label for="first_name"><?php _e( 'First Name:','auto-login-new-user-after-registration') ?>
			<input type="text" name="first_name" id="first_name" class="input" value="<?php echo esc_attr( wp_unslash( $first_name ) ); ?>" size="25" /></label><br>
		<?php
	}

	if (get_option("alnuar_add_last_name_field") == true) {
		$lastname = ( ! empty( $_POST['last_name'] ) ) ? trim( $_POST['last_name'] ) : '';
	    ?>
			<label for="last_name"><?php _e( 'Last Name:','auto-login-new-user-after-registration') ?>
			<input type="text" name="last_name" id="last_name" class="input" value="<?php echo esc_attr( wp_unslash( $lastname ) ); ?>" size="25" /></label><br>
		<?php
	}

	if (get_option("alnuar_add_first_name_field") == true || get_option("alnuar_add_last_name_field") == true) {
		?>
		</p>
		<?php
	}

	if (get_option("alnuar_add_password_fields") == true) {
		$password1 = ( ! empty( $_POST['password1'] ) ) ? trim( $_POST['password1'] ) : '';
		$password2 = ( ! empty( $_POST['password2'] ) ) ? trim( $_POST['password2'] ) : '';
	    ?>
		<p>
			<label for="password1"><?php _e( 'Password:','auto-login-new-user-after-registration') ?>
			<input type="password" name="password1" id="password1" class="input" value="<?php echo esc_attr( wp_unslash( $password1 ) ); ?>" size="25" /></label><br>
			<label for="password2"><?php _e( 'Confirm Password:','auto-login-new-user-after-registration') ?>
			<input type="password" name="password2" id="password2" class="input" value="<?php echo esc_attr( wp_unslash( $password2 ) ); ?>" size="25" /></label><br>
		</p>
		<?php
	}
	
	if (get_option("alnuar_add_first_name_field") == true || get_option("alnuar_add_last_name_field") == true || get_option("alnuar_add_password_fields") == true) {
		?>
			<input type="hidden" name="alnuar" id="alnuar" value="yes">
		<?php
	}

}
add_action('register_form', 'alnuar_new_item_register_form');


/* ****************************************************************************** */
function alnuar_register_form_errors( $errors, $sanitized_user_login, $user_email ) {
	if (get_option("alnuar_add_first_name_field") == true) {
		if ( empty( $_POST['first_name'] ) || ! empty( $_POST['first_name'] ) && trim( $_POST['first_name'] ) == '' ) {
			$errors->add( 'first_name_error', __( '<strong>ERROR</strong>: First Name field is required.','auto-login-new-user-after-registration') );
		}
	}

	if (get_option("alnuar_add_last_name_field") == true) {
		if ( empty( $_POST['last_name'] ) || ! empty( $_POST['last_name'] ) && trim( $_POST['last_name'] ) == '' ) {
			$errors->add( 'last_name_error', __( '<strong>ERROR</strong>: Last Name field is required.','auto-login-new-user-after-registration') );
		}
	}

	if (get_option("alnuar_add_password_fields") == true) {
		if ( empty( $_POST['password1'] ) || ! empty( $_POST['password1'] ) && trim( $_POST['password1'] ) == '' ) {
			$errors->add( 'password1_error', __( '<strong>ERROR</strong>: Password field is required.','auto-login-new-user-after-registration') );
		}
		if ( empty( $_POST['password2'] ) || ! empty( $_POST['password2'] ) && trim( $_POST['password2'] ) == '' ) {
			$errors->add( 'password2_error', __( '<strong>ERROR</strong>: Confirm Password field is required.','auto-login-new-user-after-registration') );
		}
		if ( $_POST['password1'] != $_POST['password2'] ) {
			$errors->add( 'password12_error', __( '<strong>ERROR</strong>: Password field and Confirm Password field do not match.','auto-login-new-user-after-registration') );
		}
	}

    return $errors;
}
add_filter( 'registration_errors', 'alnuar_register_form_errors', 10, 3 );


/* ****************************************************************************** */
function alnuar_disable_admin_new_user_notification_email($result = '') {
	extract($result); //Array KEY name becomes variable name and KEY value becomes variable value. Should create $to, $subject, $message, $headers, $attachments
	$blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
	$admin_email = get_option('admin_email');

	if (get_option("alnuar_auto_login_new_user_after_registration_admin_email_enabled") == false) {
		// WORKAROUND: $to needs to be a string (not array). Why would it be an array though? Had an issue with that so this is workaround.
		if ( ! isset($to) ) {
			$to = "";
			error_log("PLUGIN Auto Login New User After Registration: Notice #1A: The string TO was not set.", 0);
		} else if ( ! is_string($to) ) {
			$to = "";
			error_log("PLUGIN Auto Login New User After Registration: Notice #2A: The string TO was not a string.", 0);
		} // end WORKAROUND
		if (strpos($to, $admin_email) !== false) {
			if (strstr(sprintf(__('[%s] New User Registration','auto-login-new-user-after-registration'), $blogname), $subject)) {
				$to = '';
				$subject = '';
				$message = '';
				$headers = '';
				$attachments = array ();
				return compact('to', 'subject', 'message', 'headers', 'attachments');
			}
		}
	}

	if (get_option("alnuar_user_password_lost_changed_admin_email_enabled") == false) {
		// WORKAROUND: $to needs to be a string (not array). Why would it be an array though? Had an issue with that so this is workaround.
		if ( ! isset($to) ) {
			$to = "";
			error_log("PLUGIN Auto Login New User After Registration: Notice #1A: The string TO was not set.", 0);
		} else if ( ! is_string($to) ) {
			$to = "";
			error_log("PLUGIN Auto Login New User After Registration: Notice #2A: The string TO was not a string.", 0);
		} // end WORKAROUND
		if (strpos($to, $admin_email) !== false) {
			if ( strstr(sprintf(__('[%s] Password Lost/Changed','auto-login-new-user-after-registration'), $blogname), $subject) || strstr(sprintf(__('[%s] Password Changed','auto-login-new-user-after-registration'), $blogname), $subject) ) {
				$to = '';
				$subject = '';
				$message = '';
				$headers = '';
				$attachments = array ();
				return compact('to', 'subject', 'message', 'headers', 'attachments');
			}
		}
	}

	//Change the message the user receives from "set your password" to "change your password"
	if (get_option("alnuar_new_user_email_text_change") == true) {
		if (strpos($subject, "Your username and password info") !== false || strpos($subject, "Login Details") !== false) {
			if (strpos($message, "To set your password, visit the following address") !== false) {
				$message = str_replace("To set your password, visit the following address", "To change your password, visit the following address", $message); //Change the message
				return compact('to', 'subject', 'message', 'headers', 'attachments');
			}
		}
	}

	return $result;

}
add_filter('wp_mail', 'alnuar_disable_admin_new_user_notification_email');


/* ****************************************************************************** */
function alnuar_auto_login_new_user_after_registration_options() {

		$settings_saved = false;

		if ( isset( $_POST[ 'save' ] ) ) {

			if ( isset($_POST[ 'alnuar_auto_login_new_user_after_registration_enabled' ]) ) {
				$post_data = sanitize_text_field($_POST[ 'alnuar_auto_login_new_user_after_registration_enabled' ]);
			} else {
				$post_data = "";
			}
			update_option( 'alnuar_auto_login_new_user_after_registration_enabled', $post_data, true );

			if ( isset($_POST[ 'alnuar_auto_login_new_user_after_registration_redirect' ]) ) {
				$post_data = sanitize_text_field($_POST[ 'alnuar_auto_login_new_user_after_registration_redirect' ]);
			} else {
				$post_data = "";
			}
			update_option( 'alnuar_auto_login_new_user_after_registration_redirect', $post_data, true );

			if ( isset($_POST[ 'alnuar_auto_login_new_user_after_registration_admin_email_enabled' ]) ) {
				$post_data = sanitize_text_field($_POST[ 'alnuar_auto_login_new_user_after_registration_admin_email_enabled' ]);
			} else {
				$post_data = "";
			}
			update_option( 'alnuar_auto_login_new_user_after_registration_admin_email_enabled', $post_data, true );

			if ( isset($_POST[ 'alnuar_user_password_lost_changed_admin_email_enabled' ]) ) {
				$post_data = sanitize_text_field($_POST[ 'alnuar_user_password_lost_changed_admin_email_enabled' ]);
			} else {
				$post_data = "";
			}
			update_option( 'alnuar_user_password_lost_changed_admin_email_enabled', $post_data, true );

			if ( isset($_POST[ 'alnuar_add_password_fields' ]) ) {
				$post_data = sanitize_text_field($_POST[ 'alnuar_add_password_fields' ]);
			} else {
				$post_data = "";
			}
			update_option( 'alnuar_add_password_fields', $post_data, true );

			if ( isset($_POST[ 'alnuar_add_first_name_field' ]) ) {
				$post_data = sanitize_text_field($_POST[ 'alnuar_add_first_name_field' ]);
			} else {
				$post_data = "";
			}
			update_option( 'alnuar_add_first_name_field', $post_data, true );

			if ( isset($_POST[ 'alnuar_add_last_name_field' ]) ) {
				$post_data = sanitize_text_field($_POST[ 'alnuar_add_last_name_field' ]);
			} else {
				$post_data = "";
			}
			update_option( 'alnuar_add_last_name_field', $post_data, true );

			if ( isset($_POST[ 'alnuar_new_user_email_text_change' ]) ) {
				$post_data = sanitize_text_field($_POST[ 'alnuar_new_user_email_text_change' ]);
			} else {
				$post_data = "";
			}
			update_option( 'alnuar_new_user_email_text_change', $post_data, true );

			$settings_saved = true;

		}
		?>

		<script>
			jQuery(document).ready(function($){
				$('#buttonSubmit').click(function(){ $(this).prop('value', '.....  '.__('SAVING','auto-login-new-user-after-registration').'  .....'); }); //Change Save Changes button text and disable button when clicked
			});
		</script>
		<?php if ( $settings_saved ) : ?>
			<script>
				jQuery(document).ready(function($){
					$('.fadeout').click(function(){$(this).fadeOut('fast');}); //fadeout on click
					setTimeout(function(){$('.fadeout').fadeOut("slow");},2000); //or fadeout after 5 seconds
				});
			</script>
			<div id="message" class="updated fadeout">
				<p><strong><?php _e( 'Changes saved','auto-login-new-user-after-registration') ?></strong></p>
			</div>
		<?php endif ?>

		<form method="post" action="">

		<h1><?php _e( 'AUTO LOGIN NEW USER AFTER REGISTRATION', 'alnuar'); ?></h1>
		<p>
			<?php
			$checked = "";
			if (get_option("alnuar_auto_login_new_user_after_registration_enabled")) {
				$checked = 'checked="checked"';
			}
			?>
			<input type="checkbox" id="alnuar_auto_login_new_user_after_registration_enabled" name="alnuar_auto_login_new_user_after_registration_enabled" <?php echo $checked; ?> >
			<label for="alnuar_auto_login_new_user_after_registration_enabled"><?php _e( 'Check to ENABLE auto-login for new user after registration','auto-login-new-user-after-registration') ?></label>
			<br><span style="font-size: 85%; font-style: italic;"><?php _e('When enabled, a new user will automatically be logged in when they register.<br>They will also receive an email telling them how to set/change their password.','auto-login-new-user-after-registration'); ?></span>
		</p>

		<p>
			<?php
			$redirect = get_option("alnuar_auto_login_new_user_after_registration_redirect");
			?>
			<label for="alnuar_auto_login_new_user_after_registration_redirect"><?php _e( 'Enter web page to redirect after registration, or leave blank for default behavior:','auto-login-new-user-after-registration') ?></label>
			<input type="textbox" id="alnuar_auto_login_new_user_after_registration_redirect" name="alnuar_auto_login_new_user_after_registration_redirect" value="<?php echo $redirect; ?>" size="50">
			<br><span style="font-size: 85%; font-style: italic;"><?php _e('User will be redirected to the specified web page when they are auto-logged in.<br>Redirect is only enabled when auto-login option above is also enabled.<br>If left blank, default behavior is to redirect to:','auto-login-new-user-after-registration'); ?> <b>/wp-login.php?checkemail=registered</b><br><?php _e('You can also set redirect from the url itself by adding the redirect_to like this:','auto-login-new-user-after-registration'); ?> <b>/wp-login.php?action=register&amp;redirect_to=http%3A%2F%2Fmydomain.com%2Fsome-page</b><br><?php _e('Note that redirect will only work if you have added the Password field below to the registration form.','auto-login-new-user-after-registration'); ?></span>
		</p>

		<br>
		<h1><?php _e( 'ADD FIELDS TO NEW USER REGISTRATION FORM','auto-login-new-user-after-registration'); ?></h1>
		<b><?php _e('NOTE: These fields only work if you are using the default WordPress Registration form at','auto-login-new-user-after-registration'); ?> /wp-login.php?action=register<br><?php _e('If you are using a custom Registration form different from the default WordPress Registration form then it is recommended you leave these options unchecked/disabled.','auto-login-new-user-after-registration'); ?></b>
		<p>
			<?php
			$checked = "";
			if (get_option("alnuar_add_first_name_field")) {
				$checked = 'checked="checked"';
			}
			?>
			<input type="checkbox" id="alnuar_add_first_name_field" name="alnuar_add_first_name_field" <?php echo $checked; ?> >
			<label for="alnuar_add_first_name_field"><?php _e( 'Check to ENABLE First Name required field on new user registration form','alnuar' ); ?></label>
			<br><span style="font-size: 85%; font-style: italic;"><?php _e('This option is independent of the auto-login option, and will work regardless of the auto-login setting.','auto-login-new-user-after-registration'); ?></span>
		</p>
		<p>
			<?php
			$checked = "";
			if (get_option("alnuar_add_last_name_field")) {
				$checked = 'checked="checked"';
			}
			?>
			<input type="checkbox" id="alnuar_add_last_name_field" name="alnuar_add_last_name_field" <?php echo $checked; ?> >
			<label for="alnuar_add_last_name_field"><?php _e( 'Check to ENABLE Last Name required field on new user registration form','alnuar' ) ?></label>
			<br><span style="font-size: 85%; font-style: italic;"><?php _e('This option is independent of the auto-login option, and will work regardless of the auto-login setting.','auto-login-new-user-after-registration'); ?></span>
		</p>
		<p>
			<?php
			$checked = "";
			if (get_option("alnuar_add_password_fields")) {
				$checked = 'checked="checked"';
			}
			?>
			<input type="checkbox" id="alnuar_add_password_fields" name="alnuar_add_password_fields" <?php echo $checked; ?> >
			<label for="alnuar_add_password_fields"><?php _e( 'Check to ENABLE Password required fields on new user registration form','auto-login-new-user-after-registration'); ?></label>
			<br><span style="font-size: 85%; font-style: italic;"><?php _e('Uncheck for WordPress default behavior, which is to send new user an email with a Reset Password link they must click in order to set their password.<br>Currently this option does NOT check for strong passwords.<br>This option is independent of the auto-login option, and will work regardless of the auto-login setting.','auto-login-new-user-after-registration'); ?></span>
		</p>

		<br>
		<h1><?php _e( 'OTHER INDEPENDENT OPTIONS','auto-login-new-user-after-registration'); ?></h1>
		<p>
			<?php
			$checked = "";
			if (get_option("alnuar_new_user_email_text_change")) {
				$checked = 'checked="checked"';
			}
			?>
			<input type="checkbox" id="alnuar_new_user_email_text_change" name="alnuar_new_user_email_text_change" <?php echo $checked; ?> >
			<label for="alnuar_new_user_email_text_change"><?php _e( 'Check to change the text in new users welcome email from "set your password" to "change your password" ','auto-login-new-user-after-registration'); ?></label>
			<br><span style="font-size: 85%; font-style: italic;"><?php _e('This option only works for English language.<br>For greater customization of message (for any language) I recommend you use a plugin that allows you to customize the new user welcome email.','auto-login-new-user-after-registration'); ?></span>
		</p>
		<p>
			<?php
			$checked = "";
			if (get_option("alnuar_auto_login_new_user_after_registration_admin_email_enabled")) {
				$checked = 'checked="checked"';
			}
			?>
			<input type="checkbox" id="alnuar_auto_login_new_user_after_registration_admin_email_enabled" name="alnuar_auto_login_new_user_after_registration_admin_email_enabled" <?php echo $checked; ?> >
			<label for="alnuar_auto_login_new_user_after_registration_admin_email_enabled"><?php _e( 'Check to ENABLE admin notification email of new user registrations','auto-login-new-user-after-registration'); ?></label>
			<br><span style="font-size: 85%; font-style: italic;"><?php _e('Uncheck to disable admin emails about new user registrations.<br>This option is independent of the auto-login option, and will work regardless of the auto-login setting.','auto-login-new-user-after-registration'); ?></span>
		</p>

		<p>
			<?php
			$checked = "";
			if (get_option("alnuar_user_password_lost_changed_admin_email_enabled")) {
				$checked = 'checked="checked"';
			}
			?>
			<input type="checkbox" id="alnuar_user_password_lost_changed_admin_email_enabled" name="alnuar_user_password_lost_changed_admin_email_enabled" <?php echo $checked; ?> >
			<label for="alnuar_user_password_lost_changed_admin_email_enabled"><?php _e( 'Check to ENABLE admin notification email of users lost/changed passwords','auto-login-new-user-after-registration'); ?></label>
			<br><span style="font-size: 85%; font-style: italic;"><?php _e('Uncheck to disable admin emails about users that have lost/changed their password.<br>This option is independent of the auto-login option, and will work regardless of the auto-login setting.','auto-login-new-user-after-registration'); ?></span>
		</p>

		<?php $pluginbut = plugins_url( 'dbut.png', __FILE__ ); ?>
		<br><hr><?php _e('How much is this plugin worth to you? A suggested <a href="https://www.paypal.me/jsherk/10usd">donation of $10 or more</a> will help me feed my kids, pay my bills and keep this plugin updated!','auto-login-new-user-after-registration'); ?><br><a href="https://www.paypal.me/jsherk/10usd"><img width="175" src="<?php echo $pluginbut; ?>"></a><hr>

		<?php if ( $settings_saved ) : ?>
			<div id="message" class="updated fadeout">
				<p><strong><?php _e( 'Options saved.','auto-login-new-user-after-registration'); ?></strong></p>
			</div>
		<?php endif ?>

		<p class="submit">
			<input class="button-primary" id="buttonSubmit" name="save" type="submit" value="<?php _e( 'Save Changes','auto-login-new-user-after-registration'); ?>" />
		</p>
		</form>

			<?php

}
function alnuar_load_plugin_textdomain() {
    load_plugin_textdomain( 'auto-login-new-user-after-registration', FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );
}
add_action( 'plugins_loaded', 'alnuar_load_plugin_textdomain');
?>
