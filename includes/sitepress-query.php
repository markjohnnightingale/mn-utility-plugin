<?php
/* Custom query_posts function, to allow showing of different posts in the correct languages. 
 * 
 * query_pamina_posts( $options Array )
 * 
 * options : 
 *  
 * returns $query (array of posts)
 */

function query_pamina_posts( $options = array() ) {
    global $sitepress;
    $sitepress->switch_lang('all');
    
        
    $query = new WP_Query( $options );
    // wp_reset_query();

    // Initialise array of original post IDs
    $post_ids = array();
    
    // Add all post ids to array
    foreach ($query->posts as $post) {
        
        array_push($post_ids, $post->ID);
    }
    // echo "<pre> Pre-sort IDs"; print_r($post_ids); echo "</pre>";
    
    // Initialise array of translated IDs
    $translated_ids = array();
    
    foreach ($post_ids as $post_id) {
        
        // Does the post exist in current language ?
        if (icl_object_id($post_id, 'post', false) ) {
            array_push($translated_ids, icl_object_id($post_id, 'post', false));
        }
        // Does the post exist in french ?
        else if (icl_object_id($post_id, 'post', false, 'fr')) {
            array_push($translated_ids, icl_object_id($post_id, 'post', false, 'fr'));
        }
        // Does the post exist in german ?
        else if (icl_object_id($post_id, 'post', false, 'de')) {
            array_push($translated_ids, icl_object_id($post_id, 'post', false, 'de'));
        }
        // Does the post exist in english ?
        else if (icl_object_id($post_id, 'post', false, 'en')) {
            array_push($translated_ids, icl_object_id($post_id, 'post', false, 'en'));
        }
        
    }
    
    // Set language back to current language
    global $sitepress;
    $sitepress->switch_lang(ICL_LANGUAGE_CODE);
    
    // Now we are all in the correct language, remove duplicates
    $translated_ids = array_unique($translated_ids);
    // echo "<pre> post-sort IDs"; print_r($translated_ids); echo "</pre>";
    
    // Now query all those posts from the DB. 
    
    $translated_query = new WP_Query( array('post__in' => $translated_ids, 'suppress_filters' => true) );
    return $translated_query;
}

?>