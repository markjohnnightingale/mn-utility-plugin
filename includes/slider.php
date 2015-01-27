<?php
/* Custom slider post type. 
 * 
 */

function register_slider_type() {
    $labels = array(
    		'name'               => __( 'Slides', 'marknightingale' ),
    		'singular_name'      => __( 'Slide', 'marknightingale' ),
    		'menu_name'          => __( 'Slideshow', 'marknightingale' )
    	);

    	$args = array(
    		'labels'             => $labels,
    		'public'             => true,
    		'publicly_queryable' => true,
    		'show_ui'            => true,
    		'show_in_nav_menus'  => false,
    		'rewrite'            => array( 'slug' => 'slide' ),
    		'capability_type'    => 'post',
    		'has_archive'        => false,
    		'hierarchical'       => false,
    		'supports'           => array( 'title', 'author', 'thumbnail', 'excerpt' )
    	);

    	register_post_type( 'slide', $args );
        
        add_image_size('slider-size', 1200, 600);
}

add_action('init', 'register_slider_type');

/* Enqueue Javascript 
 * 
 */
function enqueue_slider_assets() {
    
    wp_enqueue_script('mn_slick_js', plugins_url( 'assets/slider/slick.min.js', dirname(__FILE__) ) , array('jquery') );
    wp_enqueue_style('mn_slick_css', plugins_url( 'assets/slider/slick.css', dirname(__FILE__) ));
}
add_action( 'wp_enqueue_scripts', 'enqueue_slider_assets' );


/* Build Slideshow 
 * 
 */
function mn_slideshow() {
    $query = new WP_Query(
        array(
            'post_type' => 'slide',
        )
    );
    
    $output = '';
    $i = 1;
    $output .= '<div class="slick">';
    if ($query->have_posts() ) {
        while ($query->have_posts() ) {
            $query->the_post();
            
            $output .= '<div class="slide" id="slick_'. $i .'">';
            if (has_post_thumbnail()) {
                $output .= get_the_post_thumbnail(get_the_ID(), "slider-size", array(
                    'class' => "attachment-$size slider_image"
                ));
            }
            $output .= '<p class="title">'.get_the_title().'</p>';
            $output .= '<p class="excerpt">'.get_the_excerpt().'</p>';
            
            
            $output .= '</div>';
            $i++;
        }
    }
    $output .= '</div>';
    
    // Add starting script
    $output .= "<script>
        jQuery(document).ready(function($){
            $('.slick').slick();
            })
        </script>";
    
    wp_reset_query();
    return $output;
    
}

?>
