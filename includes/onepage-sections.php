<?php
/* Custom OnePage Section post type. 
 * 
 */

function register_onepage_section_type() {
    $labels = array(
		'name'               => __( 'OnePage Sections', 'marknightingale' ),
		'singular_name'      => __( 'OnePage Section', 'marknightingale' ),
		'menu_name'          => __( 'OnePage Sections', 'marknightingale' )
	);

	$args = array(
		'labels'             => $labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_nav_menus'  => true,
		'rewrite'            => array( 'slug' => 'onepage_section' ),
		'capability_type'    => 'post',
		'has_archive'        => false,
		'hierarchical'       => false,
		'supports'           => array( 'title', 'author', 'thumbnail', 'editor', 'page-attributes' )
	);

	register_post_type( 'onepage_section', $args );

}

add_action('init', 'register_onepage_section_type');
?>
