<?php
/* Custom Testimonial post type. 
 * 
 */

function register_testimonial_type() {
    $labels = array(
    		'name'               => __( 'Testimonials', 'marknightingale' ),
    		'singular_name'      => __( 'Testimonial', 'marknightingale' ),
    		'menu_name'          => __( 'Testimonials', 'marknightingale' )
    	);

    	$args = array(
    		'labels'             => $labels,
    		'public'             => false,
    		'publicly_queryable' => false,
    		'show_ui'            => true,
    		'show_in_nav_menus'  => false,
    		'rewrite'            => array( 'slug' => 'testimonial' ),
    		'capability_type'    => 'post',
    		'has_archive'        => false,
    		'hierarchical'       => false,
    		'supports'           => array( 'title', 'author', 'thumbnail', 'editor' )
    	);

    	register_post_type( 'testimonial', $args );
        
}

add_action('init', 'register_testimonial_type');

/*
    
    * Add SLIDER meta box
    * 
    * 
*/

add_action( 'load-post.php', 'testimonial_meta_box_setup' );
add_action( 'load-post-new.php', 'testimonial_meta_box_setup' );
function testimonial_meta_box_setup() {
    /* Add meta boxes on the 'add_meta_boxes' hook. */
      add_action( 'add_meta_boxes', 'testimonial_add_post_meta_boxes' );
      
      // Save
      add_action( 'save_post', 'testimonial_save_post_meta', 10, 2 );
}
function testimonial_add_post_meta_boxes() {
    add_meta_box(
        'testimonial-meta',      // Unique ID
        __( 'Testimonial Metadata', 'marknightingale' ),    // Title
        'testimonial_meta_box',   // Callback function
        'testimonial',         // Admin page (or post type)
        'side',         // Context
        'default'         // Priority
      );
}
global $testimonial_meta_box_fields;
$testimonial_meta_box_fields = array(
    'id'    => 'testimonial',
    'boxes' => array(
        array(
            'label' => __('Featured', 'marknightingale'),
            'desc'  => __('Check if you wish the testimonial to appear in the person block', 'marknightingale'),
            'id'    => 'testimonial_featured',
            'type'  => 'checkbox'
        ),
        array(

            'label' => __('Name', 'marknightingale'),
            'desc'  => __('The name of the person giving the testimonial', 'marknightingale'),
            'id'    => 'testimonial_name',
            'type'  => 'text'
        ),
        array(
            
            'label' => __('Thumbnail URL', 'marknightingale'),
            'desc'  => __('URL of the image to use as a thumbnail', 'marknightingale'),
            'id'    => 'testimonial_thumb_url',
            'type'  => 'text'
        )
    )
);

function testimonial_meta_box( $object, $box ) {
    global $testimonial_meta_box_fields;

    echo mn_build_meta_box($testimonial_meta_box_fields);
}
function testimonial_save_post_meta( $post_id, $post ) {
    global $testimonial_meta_box_fields;

    $metabox = $testimonial_meta_box_fields;
    
    mn_save_meta_data( $post_id, $post, $metabox );
}
?>
