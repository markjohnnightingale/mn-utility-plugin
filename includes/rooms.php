<?php
/* Custom Room post type. 
 * 
 */

function register_room_type() {
    $labels = array(
    		'name'               => __( 'Enigmas', 'marknightingale' ),
    		'singular_name'      => __( 'Enigma', 'marknightingale' ),
    		'menu_name'          => __( 'Enigmas', 'marknightingale' )
    	);

    	$args = array(
    		'labels'             => $labels,
    		'public'             => true,
    		'publicly_queryable' => true,
    		'show_ui'            => true,
    		'show_in_nav_menus'  => true,
    		'rewrite'            => array( 'slug' => 'mystery' ),
    		'capability_type'    => 'post',
    		'has_archive'        => false,
    		'hierarchical'       => false,
    		'supports'           => array( 'title', 'author', 'thumbnail', 'editor', 'excerpt', 'page-attributes', 'custom-fields' )
    	);

    	register_post_type( 'room', $args );
        
}

add_action('init', 'register_room_type');

/*
    
    * Add Rooms meta box
    * 
    * 
*/

add_action( 'load-post.php', 'room_meta_box_setup' );
add_action( 'load-post-new.php', 'room_meta_box_setup' );
function room_meta_box_setup() {
    /* Add meta boxes on the 'add_meta_boxes' hook. */
      add_action( 'add_meta_boxes', 'room_add_post_meta_boxes' );
      
      // Save
      add_action( 'save_post', 'room_save_post_meta', 10, 2 );
}
function room_add_post_meta_boxes() {
    add_meta_box(
        'room-meta',      // Unique ID
        __( 'Room Metadata', 'marknightingale' ),    // Title
        'room_meta_box',   // Callback function
        'room',         // Admin page (or post type)
        'side',         // Context
        'default'         // Priority
      );
}
global $room_meta_box_fields;
$room_meta_box_fields = array(
    'id'    => 'room',
    'boxes' => array(
    	array(
            'label' => __('Players', 'marknightingale'),
            'desc'  => __('Text displaying the number of players', 'marknightingale'),
            'id'    => 'room_players',
            'type'  => 'text'
        ),
        array(
            'label' => __('Icon URL', 'marknightingale'),
            'desc'  => __('URL for the room icon', 'marknightingale'),
            'id'    => 'room_icon_url',
            'type'  => 'text'
        ),
        array(
            'label' => __('Desktop X position', 'marknightingale'),
            'desc'  => __('Position in % from center of map on desktop', 'marknightingale'),
            'id'    => 'room_x_desk_coord',
            'type'  => 'text'
        ),
        array(
            'label' => __('Desktop Y position', 'marknightingale'),
            'desc'  => __('Position in % from center of map on desktop', 'marknightingale'),
            'id'    => 'room_y_desk_coord',
            'type'  => 'text'
        ),
        array(
            'label' => __('Mobile X position', 'marknightingale'),
            'desc'  => __('Position in % from center of map on mobile', 'marknightingale'),
            'id'    => 'room_x_mob_coord',
            'type'  => 'text'
        ),
        array(
            'label' => __('Mobile Y position', 'marknightingale'),
            'desc'  => __('Position in % from center of map on mobile', 'marknightingale'),
            'id'    => 'room_y_mob_coord',
            'type'  => 'text'
        ),

    )
);

function room_meta_box( $object, $box ) {
    global $room_meta_box_fields;

    echo mn_build_meta_box($room_meta_box_fields);
}
function room_save_post_meta( $post_id, $post ) {
    global $room_meta_box_fields;

    $metabox = $room_meta_box_fields;
    
    mn_save_meta_data( $post_id, $post, $metabox );
}
