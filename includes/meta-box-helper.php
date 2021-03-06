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
            $output .= "<span class='description'>${field['desc']}</span></p>";
            break; 
            case 'checkbox':
            $output .= "<p><label for='${field['id']}'>${field['label']}</label><br>";
            $output .= "<input type='checkbox' name='${field['id']}' id='${field['id']}' value='yes' ". checked( $meta, 'yes', false ) ." /> ";
            $output .= "<label class='description' for='${field['id']}'>${field['desc']}</span>";
            break;
            case 'upload':
            $output .= "<p><label for='${field['id']}'>${field['label']}</label><br>";
            $output .= "<input type='text' name='${field['id']}' id='${field['id']}' class='upload_image' value='$meta' /> ";
            $output .= "<input id='${field['id']}_button' type='button' class='upload_image_button' value=".__('Upload Image', 'marknightingale')." data-target='${field['id']}'/> ";
            $output .= "<span class='description'>${field['desc']}</span>";
            break;
            case 'select':
            $output .= "<p><label for='${field['id']}'>${field['label']}</label><br>";
            $output .= "<select name='${field['id']}' id='${field['id']}' >";
              foreach ($field['options'] as $opt) {
                $output .= "<option value='${opt['value']}'" . selected( $opt['value'], $meta, false ) . ">${opt['name']}</option>";
              }
            $output .= "</select><br>";
            $output .= "<span class='description'>${field['desc']}</span><br>";
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
          if ( isset($new) && $new != $old) {
              update_post_meta($post_id, $field['id'], $new);
          } elseif ( ('' === $new || !isset($new) ) && isset($old) )  {
              delete_post_meta($post_id, $field['id'], $old);
          } 
      } // end foreach  
}

/* Custom meta_box builder
 * 
 * mn_save_meta_data( $post_id, $post, $metabox Array )
 * 
 * 
 */

function my_admin_scripts() {
    wp_enqueue_script('media-upload');
    wp_enqueue_script('thickbox');

    wp_register_script('uploader-js',  plugin_dir_url( __FILE__ )  .'../vendor/uploader.js', array('jquery'));
    wp_enqueue_script('uploader-js');
}

function my_admin_styles() {

    wp_enqueue_style('thickbox');
}
add_action('admin_enqueue_scripts', 'my_admin_scripts');
add_action('admin_enqueue_styles', 'my_admin_styles');
