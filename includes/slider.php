<?php
/* Custom slider post type. 
 * 
 */
function register_slider_type() {
    global $mn_config;
    $labels = array(
            'name'               => __( 'Slides', 'marknightingale' ),
            'singular_name'      => __( 'Slide', 'marknightingale' ),
            'menu_name'          => __( 'Slideshow', 'marknightingale' )
        );

        $args = array(
            'labels'             => $labels,
            'public'             => false,
            'publicly_queryable' => false,
            'show_ui'            => true,
            'show_in_nav_menus'  => false,
            'rewrite'            => array( 'slug' => 'slide' ),
            'capability_type'    => 'post',
            'has_archive'        => false,
            'hierarchical'       => false,
            'supports'           => array( 'title', 'author', 'thumbnail', 'excerpt', 'editor' )
        );
    	register_post_type( 'slide', $args );
        add_image_size('slider-size', $mn_config['slider']['image_size']['x'], $mn_config['slider']['image_size']['y'], true);
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

/**
 * Build slideshow 
 * @param  boolean $auto_init      autocall the javascript to start the slideshow
 * @param  array   $slider_options array of options to be converted to JSON and passed to the .slick() function. If none passed, defaults used.
 * @param  array   $slider_items   array of items to display in the slider (optional). If no array is provided, the Slider CPT will be used.
 *                                     Arguments : 
 *                                     [0] => array(
 *                                         img_url      => url of the image to use 
 *                                         img          => id of the wordpress attachment to use (if no URL)
 *                                         title        => title text for the slide
 *                                         content      => slide content
 *                                         slider_url   => URL to apply over the slider as a link
 *                                         )
 * @return string                  slider HTML
 */ 
function mn_slideshow( $auto_init = true, $slider_options = array(), $slider_items = array() ) {
    global $mn_config;
    $query = new WP_Query(
        array(
            'post_type' => 'slide',
        )
    );

    // Populate slider options
    $slides = array();
    if (!empty($slider_items)) {

        // If items are passed in params, use
        $slides = $slider_items;

        foreach($slides as &$slide) {
            // Populate img URLs if left empty
            if ( empty($slide['img_url']) && !empty($slide['img']) ) {
                $image = wp_get_attachment_image_src( $slide['img'] , 'slider-size' );
                $slide['img_url'] = $image[0];
            }
            unset($slide['img']);
        }
    unset($slide);
    } else {

        // If not, use CPT slides.
        $slide_posts = get_posts(array(
            'post_type'     => 'slide',
            'posts_per_page'     => -1
        ));

        foreach ($slide_posts as $slide_post ) {
            // Set fields
            $slide = array();
            $image = wp_get_attachment_image_src( get_post_thumbnail_id( $slide_post->ID ), 'slider-size' );
            $slide['img_url'] = $image[0];
            $slide['title'] = $slide_post->post_title;

            if ( $mn_config['slider']['content_type'] == 'content' ) {
                $content = apply_filters( 'the_content', $slide_post->post_content );
                $content = str_replace( ']]>', ']]&gt;', $content );
            } else {
                $content = get_the_excerpt();
            }
                
            $slide['content'] = $content;
            $slide['slider_url'] = get_post_meta( $slide_post->ID, 'slider_url', true );

            array_push($slides, $slide);

        }
    }



    
    $slider_wrapper = '<div class="slick">%s</div>';
    // Build slides
    // 
    foreach($slides as &$slide) {

        $slide_wrapper = '<div class="slide" id="slick_%d">%s</div>';
        
        if ( !empty($slide['img_url']) ) {
            $slide_image_wrapper = '<div class="slider_image_container">%s</div>';
            $slide_image = sprintf("<img src='%s' alt='Slider Image' title='%s' class='slider_image'/>", 
                $slide['img_url'],
                $slide['title']);
            if ( !empty($slide['slider_url']) ) {
                // Add link
                $slide_image = sprintf('<a href="%s">%s</a>', 
                    $slide['slider_url'],
                    $slide_image);
            }
            if ( $mn_config['slider']['has_overlay'] == true ) {
                // Add image overlay
                $slide_image .= '<div class="color_overlay"></div>';
            }

            
            $slide_html = sprintf($slide_image_wrapper, $slide_image);
        } else {
            $slide_html = '';
        }

        $content_wrapper = '<div class="content">%s</div>';

        if ( !empty($slide['title']) ) {
            $title_text = $slide['title'];
            if ( isset($slide['slider_url']) ) {
                $title_text = sprintf('<a href="%s">%s</a>', $slide['url'], $title_text);
            }
            $title = sprintf('<h3 class="title">%s</h3>', $title_text);
        }
        if ( !empty($slide['content']) ) {

            if ( $mn_config['slider']['content_type'] == 'content' ) {
                $content_class = 'full_content';
            } else {
                $content_class = 'excerpt';
            }

            $full_content_wrapper = '<div class="%s">%s</div>';
            $content_text = $slide['content'];
            if ( isset($slide['slider_url']) ) {
                $content_text = sprintf('<a href="%s">%s</a>', $slide['url'], $content_text);
            }
            $content = sprintf($full_content_wrapper, $content_class, $content_text);
        }

        $slide_html .= sprintf($content_wrapper, $title . $content); 
        $slide_html = sprintf($slide_wrapper, $i, $slide_html);
        
        $slider_output .= $slide_html;

        $i++;
    } // End foreach slide

    $slider_output = sprintf($slider_wrapper, $slider_output);
    
    
    if ($auto_init) {
        // Encode slider options
        $js_options = json_encode($slider_options);
        
        // Add starting script
        $slider_output .= "<script>
            jQuery(document).ready(function($){
                $('.slick').slick($js_options);
                })
            </script>";
    }
    
    return $slider_output;
    
}

?>
