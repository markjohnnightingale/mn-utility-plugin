<?php 

//  Load required modules
$mn_config = array();

$mn_config['active_modules'] = array(
	'meta-box-helper.php',
	'slider.php',
	'sitepress-query.php',
	'utilities.php',
	'breadcrumb.php',
	'foundation-walker.php',
	'wpml-appointments.php',
	'faq.php',
	'testimonial.php',
	'google-map.php'
);

$mn_config['include_dir'] = plugin_dir_path( __FILE__ ) . 'includes/';


// Slider overlay 
$mn_config['slider']['has_overlay'] = false;

// Slider image size 
$mn_config['slider']['image_size']['x'] = '1200';
$mn_config['slider']['image_size']['y'] = '500';

// Slider displays what?
$mn_config['slider']['content_type'] = 'content';


// FAQ 
$mn_config['faq']['has_homepage_option'] = false;

?>