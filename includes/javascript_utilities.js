/** 
 *  Sticky header
 */
jQuery(window).on('load scroll resize', function(){
    var $header = jQuery('.site-header');
    if (jQuery(window).scrollTop() > 300 ) {$header.addClass('stuck'); }
    else { $header.removeClass('stuck'); }
})

/**
      * jQuery().smoothScroll() function. 
      *  
      *************************
    */
jQuery.fn.smoothScroll = function() {
    var $target = jQuery(this);
    var menuBarHeight = jQuery('.top-bar').height();
    var scrollTo = $target.offset().top-menuBarHeight-25;
    jQuery('html,body').animate({ scrollTop: scrollTo }, 800);
    return false;
}

function getHashFromUrl( url ) {
    var matches = url.match(/(#[\w-_\.]*)/);
    if (matches.length == 0) {
        console.log('No Hash Found');
        return -1;
    } else {
        return matches[0];
    }
}


jQuery(document).ready(function($){
	

	/**
      * Smooth Scroll function
      * hooks into the .smoothscroll class
      *************************
    */
    $('body').on('click','.smoothscroll',function(){
        if ( $(this).is('a') ) {
            var $target = $( getHashFromUrl( $(this).attr('href') ) );
        } else if ( $(this).children('a').length === 1 ) {
            var $target = $( getHashFromUrl( $(this).children('a').attr('href') ) );
        } else {
            return false;
        }
        if ($target.length > 0) {
            $target.smoothScroll();
            return false;
        } else {
            return true;
        }
    });



    /** 
     *  Highlight sections menu
     *  add 'highlight-menu' to the menu ul to activate
     *  Menu items need urls in the format #foo 
     */
     
    if ( jQuery('ul.highlight-menu').length > 0 ) {
    	var $ul = jQuery('.highlight-menu');

        jQuery(window).on('load resize',function(){
            
            

            var targets = [];
            var marginOfError = 200

            // for each block, check if it is in the frame. 
            $ul.children('li').each(function(){
            	
            	var $menuItem = jQuery(this);
            	var $targetElem = jQuery( getHashFromUrl( $menuItem.find('a').eq(0).attr('href') ) );

            	if ($targetElem.length > 0 ) {
            		var targetElemTop = $targetElem.offset().top;
            		var targetElemBottom = $targetElem.offset().top + $targetElem.height();

                    targets.push({
                        $menuItem: $menuItem,
                        id: $targetElem.attr('id'),
                        top: targetElemTop,
                        bottom: targetElemBottom
                    })

            		// if ( (targetElemTop < docViewTop+marginOfError && targetElemBottom > docViewTop ) ) {
            		//     $menuItem.siblings().removeClass('active')
            		//     $menuItem.addClass('active');
            		//     return false;
            		// } else {
            		//     $menuItem.removeClass('active')
            		// }

            	}
                
            });

            // Sort the array from the highest to lowest elements
            targets.sort(function(a, b){
                if (a.bottom < b.bottom) {
                    return -1;
                } else if (a.bottom > b.bottom) {
                    return 1;
                } else {
                    return 0;
                }
            });
            console.log(targets);

            // On scroll;
            jQuery(window).on('scroll load resize', function() {
                
                // Get top and bottom of window
                var docViewTop = jQuery(window).scrollTop();
                var docViewBottom = docViewTop + jQuery(window).height();

                // Iterate backwards through the array until you hit an element which is visible above the bottom of the screen. 
                for (var i = targets.length-1; i >= 0; i-- ) {
                    
                    if (targets[i].top+marginOfError < docViewBottom) {
                        
                        // Element is above the fold
                        targets[i].$menuItem.siblings().removeClass('active');
                        targets[i].$menuItem.addClass('active');
                        return false;
                    } 

                }
            }) 
        })
    }



})