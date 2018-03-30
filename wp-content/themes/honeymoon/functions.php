<?php

/**
 * Theme functions. Initializes the Vamtam Framework.
 *
 * @package  wpv
 */

require_once('vamtam/classes/framework.php');

new WpvFramework(array(
	'name' => 'honeymoon',
	'slug' => 'honeymoon'
));

// TODO remove next line when the editor is fully functional, to be packaged as a standalone module with no dependencies to the theme
define ('VAMTAM_EDITOR_IN_THEME', true); include_once THEME_DIR.'vamtam-editor/editor.php';

// only for one page home demos
function wpv_onepage_menu_hrefs( $atts, $item, $args ) {
	if ( $item->type === 'custom' && strpos( $atts['href'], '/#' ) === 0 ) {
		$atts['href'] = $GLOBALS['wpv_inner_path'] . $atts['href'];
	}
	return $atts;
}

if ( ( $path = parse_url( get_home_url(), PHP_URL_PATH ) ) !== null ) {
	$GLOBALS['wpv_inner_path'] = untrailingslashit( $path );
	add_filter( 'nav_menu_link_attributes', 'wpv_onepage_menu_hrefs', 10, 3 );
}

remove_action( 'admin_head', 'jordy_meow_flattr', 1 );

/**
 * Proper way to enqueue scripts and styles
 */
function wpdocs_theme_name_scripts() {
    wp_enqueue_style( 'style.css', get_stylesheet_uri() );
}
add_action( 'wp_enqueue_scripts', 'wpdocs_theme_name_scripts' );
