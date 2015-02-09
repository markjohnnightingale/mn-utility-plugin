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
        
        add_image_size('slider-size', 800, 600, true);
}

add_action('init', 'register_slider_type');

/*
    
    * Add SLIDER meta box
    * 
    * 
*/

add_action( 'load-post.php', 'slider_meta_box_setup' );
add_action( 'load-post-new.php', 'slider_meta_box_setup' );
function slider_meta_box_setup() {
    /* Add meta boxes on the 'add_meta_boxes' hook. */
      add_action( 'add_meta_boxes', 'slider_add_post_meta_boxes' );
      
      // Save
      add_action( 'save_post', 'slider_save_post_meta', 10, 2 );
}
function slider_add_post_meta_boxes() {
    add_meta_box(
        'slider-meta',      // Unique ID
        __( 'Slider Metadata', 'marknightingale' ),    // Title
        'slider_meta_box',   // Callback function
        'slide',         // Admin page (or post type)
        'side',         // Context
        'default'         // Priority
      );
}
global $slider_meta_box_fields;
$slider_meta_box_fields = array(
    'id'    => 'slider',
    'boxes' => array(
        array(
            'label' => __('URL', 'marknightingale'),
            'desc'  => __('The URL of the link for this slide', 'marknightingale'),
            'id'    => 'slider_url',
            'type'  => 'text'
        )
    )
);

function slider_meta_box( $object, $box ) {
    global $slider_meta_box_fields;

    echo mn_build_meta_box($slider_meta_box_fields);
}
function slider_save_post_meta( $post_id, $post ) {
    global $slider_meta_box_fields;

    $metabox = $slider_meta_box_fields;
    
    mn_save_meta_data( $post_id, $post, $metabox );
}






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
 * params $auto_init = true ; $framework String = '' (bootstrap|foundation) ; $slider_options Array = array();
 */
function mn_slideshow( $auto_init = true, $slider_options = array() ) {
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
            
            $post_meta = get_post_meta( get_the_ID() );
            
            $output .= '<div class="slide" id="slick_'. $i .'">';
            if (has_post_thumbnail()) {
                $output .= '<div class="slider_image_container">';
                if ( isset($post_meta['slider_url']) ) {
                    $output .= '<a href="'.$post_meta['slider_url'][0].'">';
                }
                $output .= get_the_post_thumbnail(get_the_ID(), "slider-size", array(
                    'class' => "slider_image"
                ));
                if ( isset($post_meta['slider_url']) ) {
                    $output .= '</a>';
                }
                $output .= '</div>';
            }
            $output .= '<div class="content"><p class="title">';
            if ( isset($post_meta['slider_url']) ) {
                $output .= '<a href="'.$post_meta['slider_url'][0].'">';
            }
            $output .= get_the_title();
            if ( isset($post_meta['slider_url']) ) {
                $output .= '</a>';
            }
            $output .= '</p>';
            $output .= '<p class="excerpt">';
            if ( isset($post_meta['slider_url']) ) {
                $output .= '<a href="'.$post_meta['slider_url'][0].'">';
            }
            $output .= get_the_excerpt();
            if ( isset($post_meta['slider_url']) ) {
                $output .= '</a>';
            }
            $output .= '</p></div>';
            
            
            $output .= '</div>';
            $i++;
        }
    }
    $output .= '</div>';
    
    if ($auto_init) {
        // Encode slider options
        $js_options = json_encode($slider_options);
        
        // Add starting script
        $output .= "<script>
            jQuery(document).ready(function($){
                $('.slick').slick($js_options);
                })
            </script>";
    }
    
    
    wp_reset_query();
    return $output;
    
}

?>
