/** 
 *  Sticky header
 */
jQuery(window).on('load scroll resize', function(){
    var $header = jQuery('.site-header');
    if (jQuery(window).scrollTop() > 300 ) {$header.addClass('stuck'); }
    else { $header.removeClass('stuck'); }
})


jQuery(document).ready(function($){
	

	/**
      * Smooth Scroll function
      * hooks into the .smoothscroll class
      *************************
    */
    $('body').on('click','a.smoothscroll',function(){
        var $target = $( $(this).attr('href') );
        var menuBarHeight = $('.top-bar').height();
        var scrollTo = $target.offset().top-menuBarHeight-50;
        $('html,body').animate({ scrollTop: scrollTo }, 800);
        return false;
    });



    /** 
     *  Highlight sections menu
     *  add 'highlight-menu' to the menu ul to activate
     *  Menu items need urls in the format #foo 
     */
     
    if ( jQuery('ul.highlight-menu').length > 0 ) {
    	var $ul = jQuery('.highlight-menu');

        jQuery(window).on('scroll resize load',function(){
            
            // Get top and bottom of window
            var docViewTop = jQuery(window).scrollTop();
            var docViewBottom = docViewTop + jQuery(window).height();

            // for each block, check if it is in the frame. 
            $ul.children('li').each(function(){
            	
            	var $menuItem = jQuery(this);
            	var $targetElem = jQuery( $menuItem.find('a').eq(0).attr('href') );

            	if ($targetElem.length > 0 ) {
            		var targetElemTop = $targetElem.offset().top;
            		var targetElemBottom = $targetElem.offset().top + $targetElem.height();

                    var marginOfError = 200

            		if ( (targetElemTop < docViewTop+marginOfError && targetElemBottom > docViewTop ) ) {
            		    $menuItem.siblings().removeClass('active')
            		    $menuItem.addClass('active');
            		    return false;
            		} else {
            		    $menuItem.removeClass('active')
            		}

            	}
                
            }) 
        })
    }



})