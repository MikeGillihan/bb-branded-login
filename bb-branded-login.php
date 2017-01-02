<?php
/**
 * Plugin Name: Beaver Builder Branded Login
 * Plugin URI: http://purposewp.com/
 * Description: Easily add the Beaver Builder theme header logo to the WordPress login page.
 * Version: 1.0.0
 * Author: Michael Gillihan
 * Author URI: http://mikegillihan.com/
 * License: GPL2
 */

/*  Copyright 2014 purposewp.dev (email: hello@purposewp.dev)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/**
 * Silence is golden; exit if accessed directly
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'PWP_BBBL_PATH', dirname( __FILE__ ) );
define( 'PWP_BBBL_URL', plugin_dir_url( __FILE__ ) );

add_action( 'login_header', 'bbbl_login_logo' );
/**
 * Custom Login Logo
 * Replace default WordPress logo on the login page with the Beaver Builder Header Logo. Fallback to site name if
 * Beaver BUilder is not activated
 */
function bbbl_login_logo() {

	$logo_class = 'branded-login';

	if ( class_exists( 'FLTheme' ) ) {

		$logo_type   = FlTheme::get_setting( 'fl-logo-type' );
		$logo_image  = FlTheme::get_setting( 'fl-logo-image' );
		$logo_retina = FlTheme::get_setting( 'fl-logo-image-retina' );
		$logo_text   = FlTheme::get_setting( 'fl-logo-text' );

		if ( $logo_type == 'image' ) {
			$output = printf( '<img class="%s fl-logo-img" itemscope itemtype="http://schema.org/ImageObject" src="%s" data-retina="%s" alt="%s" />', $logo_class, $logo_image, $logo_retina, esc_attr( $logo_text ) );
		} else {
			$output = printf( '<span class="%s fl-logo-text" itemprop="name">%s</span>', $logo_class, do_shortcode( $logo_text ) );
		}
	} else {
		$output = printf( '<span class="%s" itemprop="name">%s</span>', $logo_class, get_bloginfo( 'name' ) );
	}

	return $output;
}

add_action( 'login_enqueue_scripts', 'bbbl_enqueue_styles', 10 );
/**
 * Enqueue Login style
 */
function bbbl_enqueue_styles() {
	wp_enqueue_style( 'login-logo', PWP_BBBL_URL . 'login-logo.css', true );
}

add_action( 'login_enqueue_scripts', 'bbbl_enqueue_scripts', 20 );
/**
 * Enqueue Login script
 */
function bbbl_enqueue_scripts() {
	wp_enqueue_script( 'login-logo', PWP_BBBL_URL . 'login-logo.js', array( 'jquery' ), '1.0.0' );
}

add_filter( 'login_headerurl', 'bbbl_login_logo_link' );
/**
 * Login Logo Link
 *
 * Change the link for the login logo.
 */
function bbbl_login_logo_link() {
	$logo_link = get_bloginfo( 'wpurl' );

	return $logo_link;
}

add_filter( 'login_headertitle', 'bbbl_login_logo_title' );
/**
 * Login Logo Title
 *
 * Change the login logo hover text
 */
function bbbl_login_logo_title() {
	$logo_title = get_bloginfo( 'name' );

	return $logo_title;
}