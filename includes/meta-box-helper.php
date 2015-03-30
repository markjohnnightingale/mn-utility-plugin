<?php
/* Custom meta_box builder
 * 
 * mn_build_meta_box( $metabox Array )
 * 
 * 
 */
function mn_build_meta_box( $metabox ) {
    global $post;

    wp_nonce_field( basename( __FILE__ ), $metabox['id'].'_metabox_nonce' );
    $output = '';
    foreach($metabox['boxes'] as $field) {
        $meta = get_post_meta($post->ID, $field['id'], true);
        switch( $field['type'] ) {
            case 'text': 
            $output .= "<p><label for='${field['id']}'>${field['label']}</label>";
            $output .= "<input class='widefat' type='text' name='${field['id']}' id='${field['id']}' value='$meta' size=30/>";
            $output .= "<span class='description'>${field['desc']}</span>";
            break; 
            case 'checkbox':
            $output .= "<p><label for='${field['id']}'>${field['label']}</label><br>";
            $output .= "<input type='checkbox' name='${field['id']}' id='${field['id']}' value='yes' ";
            if ( isset ( $meta ) ) $output .= checked( $meta, 'yes' ); 
            $output .= " />";
            $output .= "<span class='description'>${field['desc']}</span>";
            break;

        }
    }
    return $output;
}
/* Custom meta_box builder
 * 
 * mn_save_meta_data( $post_id, $post, $metabox Array )
 * 
 * 
 */
function mn_save_meta_data( $post_id, $post, $metabox ) {
    
    
    /* Verify the nonce before proceeding. */
      if ( !isset( $_POST[$metabox['id'].'_metabox_nonce'] ) || !wp_verify_nonce( $_POST[$metabox['id'].'_metabox_nonce'], basename( __FILE__ ) ) )
        return $post_id;
      
      /* Get the post type object. */
      $post_type = get_post_type_object( $post->post_type );
      
      
      /* Check if the current user has permission to edit the post. */
      if ( !current_user_can( $post_type->cap->edit_post, $post_id ) )
          return $post_id;
        
      // loop through fields and save the data
      foreach ($metabox['boxes'] as $field) {
          $old = get_post_meta($post_id, $field['id'], true);
          $new = $_POST[$field['id']];
          if ($new && $new != $old) {
              update_post_meta($post_id, $field['id'], $new);
          } elseif ('' == $new && $old) {
              delete_post_meta($post_id, $field['id'], $old);
          }
      } // end foreach  
}