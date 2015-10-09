<?php
/* Mark Nightingale Plugin
 * 
 * Utility functions
 * 
*/

/*
 * Truncate 
 * Truncates text after certain length 
 * 
 * Params : 
 * $string (required)
 * $length = 50
 * 
 * Returns : string
*/


function truncate( $string, $length = 50 ) {
    $out = strlen($string) > $length ? substr($string,0,$length)."..." : $string;
    return $out;
}

/*
 * Get flag for language of post 
 * 
 * Params : $post_id [optional]
 * Returns : string
*/

function get_country_flag( $post_id = '' ) {
    
    if ($post_id == '') {
        $post_id = get_the_ID();
    }
    
    // Get langauge and display flag
    $post_language_information = wpml_get_language_information( $post_id );
    $url_prefix = plugins_url() . '/sitepress-multilingual-cms/res/flags/';
    
    switch ($post_language_information['locale']) {
        case 'fr_FR': $flag_url = $url_prefix . 'fr.png'; break;
        case 'de_DE': $flag_url = $url_prefix . 'de.png'; break;
        case 'en_US': $flag_url = $url_prefix . 'en.png'; break;
    }
    
    return '<img src="' . $flag_url . '" alt="Flag '. $post_language_information['locale'] .'" class="flag" />';
    
}


// Get language selector with just language code
/* $args : array or string (of classes - depreciated).
    $args = array(
        'classes' => string of classes,
        'has_dividers' => bool (default false),
        'format' => (inline-menu (default) | dropdown-menu | dropdown-button ),
        'full_name' => bool (default false),
        'order' => 'ASC',
        'orderby' => 'code'
    )
    
*/

function mn_language_selector( $args = array() ){
        $defaults = array(
            'classes' => '',
            'has_dividers' => false,
            'format' => 'inline-menu',
            'full_name' => false,
            'order' => 'ASC',
            'orderby' => 'code'
        );
    if ( is_string($args) ) {
        $input = array('classes' => $args);
    } else {
        if ( is_array($args) ) {
            $input = $args;
        } else {
            $input = array();
        }
    }
    $params = array_replace_recursive($defaults, $input);


    $languages = icl_get_languages('skip_missing=0&orderby='.$params['orderby'].'&order='.$params['order']);
    if(!empty($languages)){
        if ($params['format'] === 'dropdown-button') {
            echo '<button id="lang_selector" href="#" data-dropdown="languages" aria-controls="languages" aria-expanded="false" class="button dropdown '.$params['classes'].' ">'.ICL_LANGUAGE_NAME.'</button>';
            echo '<ul class="f-dropdown" data-dropdown-content id="languages" aria-hidden="true">';
        } else {echo '<ul id="lang_selector" class="'.$params['classes'].'">'; 
        }   
        if ($params['format'] === 'dropdown-menu') {
            echo '<li class="has-dropdown"><a href="#">'.ICL_LANGUAGE_NAME.'</a>';
            echo '<ul class="dropdown">';
        } 
        $i = 0;
        foreach($languages as $l){
            if ($l['language_code'] == 'zh-hans') { $l['language_code'] = '中文'; }
            if ($l['active']) { $class = 'active'; } else {$class = '';}

            if ( $params['has_dividers'] && $i > 0) echo "<li class='divider'></li>";
            echo '<li class="'.$class.'">';
            echo '<a href="'.$l['url'].'">';
            $language_name = ($params['full_name'] ? $l['native_name'] : $l['language_code'] );
            echo $language_name;
            echo '</a>';
            echo '</li>';
            $i++;
        }
        if ($params['format'] === 'dropdown-menu') echo '</ul></li>';
        if ($params['format'] === 'dropdown-button') echo '</ul></button>';
    }
    echo '</ul>';
}

// Get language selector with just language code compatible with a foundation button
// Depreciated - use mn_language_selector instead
function mn_full_foundation_language_button( $args = ''){
    mn_language_selector( array('full_name' => true, 'format' => 'dropdown-button') );
}

// Get language selector with full name compatible with a foundation dropdown nav menu
// Depreciated - use mn_language_selector instead
function mn_full_foundation_language_nav( $args = '' ){
    mn_language_selector( array('full_name' => true, 'format' => 'dropdown-menu') );
}



// Get ID from Slug
function get_id_by_slug($page_slug) {
    $page = get_page_by_path($page_slug);
    if ($page) {
        return $page->ID;
    } else {
        return null;
    }
}

// Get Translated ID from Slug 
function icl_get_page_id_by_slug($page_slug) {

    return icl_object_id(get_id_by_slug($page_slug), 'page') ;
    
}

// Allow SVG
function allow_svg_mime_types( $mimes ){
    $mimes['svg'] = 'image/svg+xml';
    return $mimes;
}
add_filter( 'upload_mimes', 'allow_svg_mime_types' );


// Add Custom JS Utilities
function enqueue_utility_js() {
    wp_enqueue_script( 'mn_utility_js', plugin_dir_url( __FILE__ ) . '/javascript_utilities.js', array('jquery'), '1.0.0', true );
}
add_action('wp_enqueue_scripts','enqueue_utility_js');

/**
 * Add the_slug function
 */
function the_slug($echo=true){
    $slug = basename(get_permalink());
    do_action('before_slug', $slug);
    $slug = apply_filters('slug_filter', $slug);
    if( $echo ) echo $slug;
    do_action('after_slug', $slug);
    return $slug;
}

?>