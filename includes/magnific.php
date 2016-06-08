<?php 


/* ********
 * Add Magnific PopUp v1.0.0 JS and css
 * ********
*/

// Add Magnific JS and CSS
function enqueue_magnific_js() {
    wp_enqueue_style( 'magnific', plugin_dir_url( __FILE__ ) . '../vendor/magnific.css' );
    wp_enqueue_script( 'magnific-js', plugin_dir_url( __FILE__ ) . '../vendor/magnific.min.js', array('jquery'), '1.0.0', true );

}
add_action('wp_enqueue_scripts','enqueue_magnific_js');




