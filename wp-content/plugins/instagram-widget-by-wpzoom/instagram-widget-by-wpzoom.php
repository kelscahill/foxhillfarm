<?php

/**
 * Plugin Name: Instagram Widget by WPZOOM
 * Plugin URI: http://www.wpzoom.com/plugins/instagram-widget/
 * Description: Fully customisable and responsive Instagram timeline widget for WordPress
 * Author: WPZOOM
 * Author URI: http://www.wpzoom.com/
 * Version: 1.1.0
 * License: GPLv2 or later
 */

require_once( plugin_dir_path( __FILE__ ) . 'class-wpzoom-instagram-widget-api.php' );
require_once( plugin_dir_path( __FILE__ ) . 'class-wpzoom-instagram-widget-settings.php' );
require_once( plugin_dir_path( __FILE__ ) . 'class-wpzoom-instagram-widget.php' );

add_action( 'widgets_init', 'zoom_instagram_widget_register' );
function zoom_instagram_widget_register() {
	register_widget( 'Wpzoom_Instagram_Widget' );
}

/* Display a notice that can be dismissed */

add_action('admin_notices', 'wpzoom_instagram_admin_notice');

function wpzoom_instagram_admin_notice() {
	global $current_user ;
	$user_id = $current_user->ID;
	/* Check that the user hasn't already clicked to ignore the message */
	if ( ! get_user_meta($user_id, 'wpzoom_instagram_admin_notice') ) {
		echo '<div class="error notice" style="position:relative"><p>';
		printf(__('<strong>Instagram Widget by WPZOOM</strong><br /><br/> If you have just installed or updated this plugin, please go to the <a href="options-general.php?page=wpzoom-instagram-widget">Settings page</a> and <strong>connect</strong> it with your Instagram account.<br/> Due to the recent Instagram API changes, in order to continue using the plugin after <strong><u>June 1, 2016</u></strong> you have to obtain a new <strong>Access Token</strong>. <br/>If you have already obtained a new token you can ignore this message. <a style="text-decoration: none" class="notice-dismiss" href="%1$s"></a>'), '?wpzoom_instagram_ignore_admin_notice=0');
		echo "</p></div>";
	}
}

add_action('admin_init', 'wpzoom_instagram_ignore_admin_notice');

function wpzoom_instagram_ignore_admin_notice() {
	global $current_user;
	$user_id = $current_user->ID;
	/* If user clicks to ignore the notice, add that to their user meta */
	if ( isset($_GET['wpzoom_instagram_ignore_admin_notice']) && '0' == $_GET['wpzoom_instagram_ignore_admin_notice'] ) {
		add_user_meta($user_id, 'wpzoom_instagram_admin_notice', 'true', true);
	}
}
