<?php 

//  Load required modules
$config = array();

$config['active_modules'] = array(
	'meta-box-helper.php',
	'slider.php',
	'sitepress-query.php',
	'utilities.php',
	'breadcrumb.php',
	'foundation-walker.php',
	'wpml-appointments.php',
	'faq.php',
	'testimonial.php'
);

$config['include_dir'] = plugin_dir_path( __FILE__ ) . 'includes/';


// Slider overlay 
$config['slider']['has_overlay'] = false;

// Slider image size 
$config['slider']['image_size']['x'] = '1200';
$config['slider']['image_size']['y'] = '500';

// Slider displays what?
$config['slider']['content_type'] = 'content';


// FAQ 
$config['faq']['has_homepage_option'] = false;

?>