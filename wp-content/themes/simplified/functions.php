<?php
require_once get_template_directory() . '/scripts/scriptlinker.php';

/**
 * Disable admin verification check
 */
add_filter('admin_email_check_interval', '__return_false');

/**
 * Enqueue scripts and styles.
 */
function simplified_scripts()
{
	// Theme stylesheet.
	wp_enqueue_style('simplified-style', get_stylesheet_uri());
}
add_action('wp_enqueue_scripts', 'simplified_scripts');

add_filter('auth_cookie_expiration', 'my_expiration_filter', 99, 3);
function my_expiration_filter($seconds, $user_id, $remember)
{
	//if "remember me" is checked;
	if ($remember) {
		//WP defaults to 2 weeks;
		$expiration = 14 * 24 * 60 * 60; //UPDATE HERE;
	} else {
		//WP defaults to 48 hrs/2 days;
		$expiration = 2 * 24 * 60 * 60; //UPDATE HERE;
	}
	//http://en.wikipedia.org/wiki/Year_2038_problem
	if (PHP_INT_MAX - time() < $expiration) {
		//Fix to a little bit earlier!
		$expiration = PHP_INT_MAX - time() - 5;
	}
	return $expiration;
}


add_action('wp_ajax_is_user_logged_in', 'ajax_check_user_logged_in');
add_action('wp_ajax_nopriv_is_user_logged_in', 'ajax_check_user_logged_in');
function ajax_check_user_logged_in()
{
	echo is_user_logged_in();
	exit();
}
