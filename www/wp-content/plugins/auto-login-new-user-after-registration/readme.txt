=== Auto Login New User After Registration ===
Contributors: jsherk, jlaumond
Author URI: http://www.iwebss.com/contact
Plugin URI: http://www.iwebss.com/wordpress/1300-auto-login-new-user-registration-wordpress-plugin
Donate link: https://www.paypal.me/jsherk/10usd
Tags: auto,login,after,register,registration,automatically,new,user,redirect,disable,admin,notification,email,administrator,password,field,fields,add,page,create,lastname,last,name,firstname,first,name
Requires at least: 4.3.1
Tested up to: 6.0
Stable tag: 1.9.6
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0

Automatically login new user right after they have registered. Add Password field to registration form. Disable admin notification emails of new user registrations and user password resets. Allow redirect of new user to any page right after they have registered. Add Firstname and Lastname field to registration form.

== Description ==

This plugin has the following great features:

- Auto-login: When a new user registers, this will automatically log them in right away.

- Redirect: Choose the web page you want them to be redirected too after they register and are auto-logged in.

- Independent options to add Password field to new user registration form, so users can create their password during registration instead of having to wait for an email and clicking the link in the email.

- Independent options to add First Name field and/or Last Name field to registration form.

- Independent option to disable the annoying admin notification emails about new user registrations. User will still receive their email about how to setup their password.

- Independent option to disable the annoying admin notification emails about users lost/changed password. User will still receive their email about how to reset their password.

== Screenshots ==

1. Settings page

== Change Log ==

= 1.9.6 =
* Clarified instructions for when redirect works.

= 1.9.5 =
* Workaround for Disable "admin notification email of users lost/changed passwords" and "admin notification email of new user registrations". When the $to field was an array, it would cause a FATAL error and crash site.

= 1.9.4 =
* Thanks to metapml for the Brazilian Portuguese translation.

= 1.9.3 =
* Compatible with WordPress v5.5

= 1.9.2 =
* Fixed bug: Changing text in email from "set password" to "change password" was not working.

= 1.9.1 =
* Fixed bug: disable plugin from running when a new user is added by another plugin or other code outside this plugin.

= 1.9.0 =
* Fixed bug "Unexpected token < in JSON" that was preventing WooCommerce from completing checkout when a new user registered on the WooCommerce checkout page.

= 1.8.1 =
* Fix text domain for translations.

= 1.8.0 =
* Add translations for French and Spanish. Thanks to Julien Laumond for providing this code.

= 1.7.1 =
* Minor code cleanup

= 1.7.0 =
* Code improvements

= 1.6.2 =
* Minor code cleanup

= 1.6.1 =
* Fixed "Notice Undefined Index" errors on Settings page when you click SAVE button.

= 1.6.0 =
* Option to change the text "set your password" to "change your password" is now available even when you have not added PASSWORD field to your registration page. This way option is available for other registration pages beside your custom registration page.

= 1.5.4 =
* Added option to change the text "set your password" to "change your password" in new user welcome email. Works for English only. For greater customization of message (for any language) I actually recommend you search for and use a plugin that allows you to customize the new user welcome email.

= 1.5.3 =
* Fixed bug where user gets email asking to click link to "set your password" even though they already set a password when they created the user account. Message now reads to "change your password" instead.

= 1.5.2 =
* Fixed bug where admin email for user "Password Lost/Changed" was disabled, but admin email for user "Password Changed" was not being disabled.

= 1.5.1 =
* Fixed bug where First Name and Last Name where not being saved to user profile.

= 1.5.0 =
* Added options to add First Name field and Last Name field to registration form.

= 1.4.0 =
* Fixed security issue where non-Admins could view options page and change plugin settings.

= 1.3.0 =
* More general improvements.

= 1.2.0 =
* Improved handling of redirect and default redirect behaviour.

= 1.1.0 =
* Added option to disable annoying admin notification email about users lost/changed password.

= 1.0.0 =
* First release
