<?php 
/* Breadcrumbs function (compatible Bootstrap)
 * 
 * Params : 
 * $framework = '' || 'bootstrap' || 'foundation'
 * 
 * returns : string
 */

function mn_breadcrumb( $framework = '' ) {
    if ($framework = 'bootstrap') { ?>
        <ol class="breadcrumb">
            <li class="<?php if (is_front_page()) { echo 'active'; }?>"><a href="<?php site_url();?>"><?php _e('Accueil', '_tk');?></a></li>
            <?php if (!is_front_page()) :?>
        
                <?php if (is_category()) : ?>
           
                    <li class="active"><?php single_cat_title( '', true ); ?></li>
            
                <?php elseif (is_single() || is_page()) : ?>
            
                    <?php if (is_single() && has_category()) :?>
                        <?php $cat = get_the_category(); ?>
                        <li><?php $parents_string = get_category_parents( $cat[0], true, '</li><li>' ); 
                            $parents_string = substr($parents_string, 0, -9);
                            echo $parents_string;
                            ?></li>
                    <?php elseif(is_page()):?>
                
                        <?php 
                        $ancestors = get_post_ancestors(get_the_ID());
                        if (!empty( $ancestors )) {
                            foreach ($ancestors as $post_id) {
                                $output .= '<li><a href="'.get_the_permalink($post_id).'">'.get_the_title($post_id).'</a></li>';
                                echo $output;
                            }
                        } ?>
                
                    <?php endif;?>
            
                    <li class="active"><a href="<?php the_permalink();?>"><?php echo truncate(get_the_title()) ;?></a></li>

                <?php elseif (is_tag()) : ?>
                    <li class="active"><?php single_tag_title( '', true ); ?></li>
                <?php elseif (is_day()) :?>
                    <li class="active"><?php echo __('Archive for', 'marknightingale').' '.get_the_time('j F Y') ;?></li>
                <?php elseif (is_month()) :?>
                    <li class="active"><?php echo __('Archive for', 'marknightingale').' '.get_the_time('F Y') ;?></li>
                <?php elseif (is_year()) :?>
                    <li class="active"><?php echo __('Archive for', 'marknightingale').' '.get_the_time('Y') ;?></li>
                <?php elseif (isset($_GET['paged']) && !empty($_GET['paged'])) :?>
                    <li class="active"><?php echo __('News Archives', 'marknightingale') ;?></li>
                <?php elseif (is_search()) :?>
                    <li class="active"><?php echo __('Search Results', 'marknightingale') ;?></li>
                <?php endif;?> 
        
        
            <?php endif;?>
        </ol>
   <?php }
    
}

