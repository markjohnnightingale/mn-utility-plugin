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
function mn_language_selector( $classes = ''){
    $languages = icl_get_languages('skip_missing=0&orderby=code');
    if(!empty($languages)){
        echo '<ul id="lang_selector" class="'.$classes.'">';
        foreach($languages as $l){
            if ($l['active']) { $class = 'active'; } else {$class = '';}
            echo '<li class="'.$class.'">';
            echo '<a href="'.$l['url'].'">';
            echo ''.$l['language_code'].'';
            echo '</a>';
            echo '</li>';
        }
    }
    echo '</ul>';
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

?>