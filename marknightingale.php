<?php 
/*
Plugin Name: Mark Nightingale's Utility Plugin
Plugin URI: http://marknightingale.net
Description: This plugin groups custom site functions implemented by Mark Nightingale
Author: Mark Nightingale
Version: 1.1
Text Domain: marknightingale
Domain Path: /lang
Author URI: http://marknightingale.net
*/

//  Load text domaine
function marknightingale_load_plugin_textdomain() {
    load_plugin_textdomain( 'marknightingale', FALSE, basename( dirname( __FILE__ ) ) . '/lang/' );
}
add_action( 'plugins_loaded', 'marknightingale_load_plugin_textdomain' );


// Load config file (and modules);
require plugin_dir_path( __FILE__ ) . 'conf.php';

<<<<<<< HEAD
foreach ($config['active_modules'] as $module) {
	require $config['include_dir'] . $module;
}





?>
