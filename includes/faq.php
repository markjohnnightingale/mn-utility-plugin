<?php
/* Custom FAQ post type. 
 * 
 */

function register_faq_type() {
    $labels = array(
		'name'               => __( 'FAQ', 'marknightingale' ),
		'singular_name'      => __( 'Question', 'marknightingale' ),
		'menu_name'          => __( 'FAQ', 'marknightingale' )
	);

	$args = array(
		'labels'             => $labels,
		'public'             => false,
		'publicly_queryable' => false,
		'show_ui'            => true,
		'show_in_nav_menus'  => false,
		'rewrite'            => array( 'slug' => 'faq' ),
		'capability_type'    => 'post',
		'has_archive'        => false,
		'hierarchical'       => false,
		'supports'           => array( 'title', 'author', 'thumbnail', 'editor', 'page-attributes' )
	);

	register_post_type( 'faq', $args );
    
    $labels = array(
        'name'                    => _x( 'FAQ Categories', 'Taxonomy FAQ Categories', 'marknightingale' ),
        'singular_name'            => _x( 'FAQ Category', 'Taxonomy FAQ Category', 'marknightingale' ),
        'search_items'            => __( 'Search FAQ Categories', 'marknightingale' ),
        'popular_items'            => __( 'Popular FAQ Categories', 'marknightingale' ),
        'all_items'                => __( 'All FAQ Categories', 'marknightingale' ),
        'parent_item'            => __( 'Parent FAQ Category', 'marknightingale' ),
        'parent_item_colon'        => __( 'Parent FAQ Category', 'marknightingale' ),
        'edit_item'                => __( 'Edit FAQ Category', 'marknightingale' ),
        'update_item'            => __( 'Update FAQ Category', 'marknightingale' ),
        'add_new_item'            => __( 'Add New FAQ Category', 'marknightingale' ),
        'new_item_name'            => __( 'New FAQ Category Name', 'marknightingale' ),
        'add_or_remove_items'    => __( 'Add or remove FAQ Categories', 'marknightingale' ),
        'choose_from_most_used'    => __( 'Choose from most used FAQ Categories', 'marknightingale' ),
        'menu_name'                => __( 'FAQ Category', 'marknightingale' ),
    );

    $args = array(
        'labels'            => $labels,
        'public'            => false,
        'hierarchical'      => true
    );

    register_taxonomy( 'faq-category', array( 'faq' ), $args );

}

add_action('init', 'register_faq_type');

function register_faq_taxo() {
       $labels = array(
        'name'                    => _x( 'FAQ Categories', 'Taxonomy FAQ Categories', 'marknightingale' ),
        'singular_name'            => _x( 'FAQ Category', 'Taxonomy FAQ Category', 'marknightingale' ),
        'search_items'            => __( 'Search FAQ Categories', 'marknightingale' ),
        'popular_items'            => __( 'Popular FAQ Categories', 'marknightingale' ),
        'all_items'                => __( 'All FAQ Categories', 'marknightingale' ),
        'parent_item'            => __( 'Parent FAQ Category', 'marknightingale' ),
        'parent_item_colon'        => __( 'Parent FAQ Category', 'marknightingale' ),
        'edit_item'                => __( 'Edit FAQ Category', 'marknightingale' ),
        'update_item'            => __( 'Update FAQ Category', 'marknightingale' ),
        'add_new_item'            => __( 'Add New FAQ Category', 'marknightingale' ),
        'new_item_name'            => __( 'New FAQ Category Name', 'marknightingale' ),
        'add_or_remove_items'    => __( 'Add or remove FAQ Categories', 'marknightingale' ),
        'choose_from_most_used'    => __( 'Choose from most used FAQ Categories', 'marknightingale' ),
        'menu_name'                => __( 'FAQ Category', 'marknightingale' ),
    );

    $args = array(
        'labels'            => $labels,
        'public'            => false,
        'hierarchical'      => true,
        'show_ui'           => true
    );

    register_taxonomy( 'faq-category', array( 'faq' ), $args );
}
add_action('init', 'register_faq_taxo');


/*
    
    * Add SLIDER meta box
    * 
    * 
*/

add_action( 'load-post.php', 'faq_meta_box_setup' );
add_action( 'load-post-new.php', 'faq_meta_box_setup' );
function faq_meta_box_setup() {
    /* Add meta boxes on the 'add_meta_boxes' hook. */
      add_action( 'add_meta_boxes', 'faq_add_post_meta_boxes' );
      
      // Save
      add_action( 'save_post', 'faq_save_post_meta', 10, 2 );
}
function faq_add_post_meta_boxes() {
    add_meta_box(
        'faq-meta',      // Unique ID
        __( 'FAQ Metadata', 'marknightingale' ),    // Title
        'faq_meta_box',   // Callback function
        'faq',         // Admin page (or post type)
        'side',         // Context
        'default'         // Priority
      );
}
global $faq_meta_box_fields;
$faq_meta_box_fields = array(
    'id'    => 'faq'
    );

$faq_meta_box_fields['boxes'] = array();
if ( $mn_config['faq']['has_homepage_option'] === true) {
    $homepage_box = array(
            'label' => __('Appear on Home', 'marknightingale'),
            'desc'  => __('Check if you wish the FAQ to appear on the homepage', 'marknightingale'),
            'id'    => 'faq_home',
            'type'  => 'checkbox'
        );
    $faq_meta_box_fields['boxes'][] = $homepage_box;
} 


function faq_meta_box( $object, $box ) {
    global $faq_meta_box_fields;

    echo mn_build_meta_box($faq_meta_box_fields);
}
function faq_save_post_meta( $post_id, $post ) {
    global $faq_meta_box_fields;

    $metabox = $faq_meta_box_fields;
    
    mn_save_meta_data( $post_id, $post, $metabox );
}
?>
