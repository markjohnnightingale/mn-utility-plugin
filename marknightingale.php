<?php 
/*
Plugin Name: Mark Nightingale's Utility Plugin
Plugin URI: http://marknightingale.net
Description: This plugin groups custom site functions implemented by Mark Nightingale
Author: Mark Nightingale
Version: 1.0
Author URI: http://marknightingale.net
*/

$dir = plugin_dir_path( __FILE__ );

require $dir . 'includes/meta-box-helper.php';

// Comment / decomment for slideshow
require $dir . 'includes/slider.php';

// Comment / decoment for Panima Query
require $dir . 'includes/sitepress-query.php';

// Comment / decomment for utilities
require $dir . 'includes/utilities.php';

// Comment / decomment for breadcrumb
require $dir . 'includes/breadcrumb.php';





?>