<?php 
/*
Plugin Name: Mark Nightingale's Utility Plugin
Plugin URI: http://marknightingale.net
Description: This plugin groups custom site functions implemented by Mark Nightingale
Author: Mark Nightingale
Version: 1.0
Text Domain: marknightingale
Domain Path: /lang
Author URI: http://marknightingale.net
*/

//  Load text domaine
function marknightingale_load_plugin_textdomain() {
    load_plugin_textdomain( 'marknightingale', FALSE, basename( dirname( __FILE__ ) ) . '/lang/' );
}
add_action( 'plugins_loaded', 'marknightingale_load_plugin_textdomain' );



$dir = plugin_dir_path( __FILE__ );

require $dir . 'includes/meta-box-helper.php';

// Comment / decomment for slideshow
//require $dir . 'includes/slider.php';

// Comment / decoment for Panima Query
//require $dir . 'includes/sitepress-query.php';

// Comment / decomment for utilities
require $dir . 'includes/utilities.php';

// Comment / decomment for breadcrumb
//require $dir . 'includes/breadcrumb.php';

// Comment / decomment for foundation menu walker
//require $dir . 'includes/foundation-walker.php';

// Comment / decomment for wpml-appointments
//require $dir . '/includes/wpml-appointments.php';

// Comment / decomment for FAQ custom post type
//require $dir . '/includes/faq.php';

// Comment / decomment for Testimonial custom post type
require $dir . '/includes/testimonial.php';

// Add Room custom post type
require $dir . '/includes/rooms.php';



?>
