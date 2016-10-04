/**********************************
***** Create config object ********
***********************************/
var mnConfig;

// Set defaults (can be overridden by changing specific properties)
var mnConfig = {
    parallax: {
        velocity: 0.3,
        parallaxSelector: '.parallax',
        slideInSelector: '.slide-in',
        slideDistance: 600,
        enabledOnMobile: false
    },
    stickyHeader: {
        marginTop: 300,
        stickyClass: 'stuck',
        headerSelector: '.site-header'
    },
    smoothScroll: {
        menuBarHeight: jQuery('.title-area').height(),
        marginOfError: 25,
        smoothScrollSelector: '.smoothscroll',
    },
    highlightMenu: {
        marginOfError: 200,
    }
}


// Test if Safari
var is_safari = navigator.userAgent.indexOf("Safari") > -1;

/** 
 *  Sticky header
 */
jQuery(window).on('load scroll resize', function(){
    var $header = jQuery( mnConfig.stickyHeader.headerSelector );
    if (jQuery(window).scrollTop() > mnConfig.stickyHeader.marginTop ) {$header.addClass( mnConfig.stickyHeader.stickyClass ); }
    else { $header.removeClass( mnConfig.stickyHeader.stickyClass ); }
})

/**
      * jQuery().smoothScroll() function. 
      *  
      *************************
    */
jQuery.fn.smoothScroll = function() {
    var $target = jQuery(this);
    var menuBarHeight = mnConfig.smoothScroll.menuBarHeight;
    var scrollTo = $target.offset().top-menuBarHeight-mnConfig.smoothScroll.marginOfError;
    jQuery('html,body').animate({ scrollTop: scrollTo }, 800);
    return false;
}

function getHashFromUrl( url ) {
    var matches = url.match(/(#[\w-_\.]*)/);
    if (matches) {
        if (matches.length == 0) {
            return -1;
        } else {
            return matches[0];
        }
    } else {
        return false;
    }
    
}
function getTargetElementStringFromHash( hash ) {
    var target;
    if (hash === '#') {
        target = 'body';
        return target;
    } else {
        target = hash ;
        return target;
    }    
}


jQuery(document).ready(function($){
	

	/**
      * Smooth Scroll function
      * hooks into the .smoothscroll class
      *************************
    */
    $('body').on('click', mnConfig.smoothScroll.smoothScrollSelector ,function(){
        if ( $(this).is('a') ) {
            // If the hash is just '#' scroll to top of page, else scroll to target element.
            var hash = getHashFromUrl( $(this).attr('href') );
            var $target = $( getTargetElementStringFromHash( hash ));
            console.log($target);
        } else if ( $(this).children('a').length === 1 ) {
            var hash = getHashFromUrl( $(this).children('a').first().attr('href') );
            var $target = $( getTargetElementStringFromHash( hash ));
            console.log($target);
        } else {
            throw new Error('Smoothscroll: No <a> element detected.');
            return false;
        }

        if ($target.length > 0) {
            $target.smoothScroll();
            return false;
        } else {
            // Element does not exist on this page - 
            // allow the script to continue as we don't want 
            // to interrupt navigation.
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
            var marginOfError = mnConfig.highlightMenu.marginOfError;

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



});



/***************
***  Parallax ***
***************/
/* ===========
    Document Scroll
    ========= */
// Parallax stuff
var $window = jQuery(window);

// Define viewport 
function viewport(theWindow) {
    this.top = jQuery(theWindow).scrollTop();
    this.bottom = jQuery(theWindow).scrollTop() + jQuery(theWindow).height();
    this.height = this.bottom - this.top;
    this.middlePoint = this.top + (this.height / 2) - 100;
    this.shows = function ($elem) {
        if ( $elem.offset().top < this.bottom && $elem.offset().top + $elem.height() > this.top ) {
            return true;
        } else {
            return false;
        }
    }
    this.update = function() {
        this.top = jQuery(theWindow).scrollTop();
        this.bottom = jQuery(theWindow).scrollTop() + jQuery(theWindow).height();
        this.height = this.bottom - this.top;
        this.middlePoint = this.top + (this.height / 2) - 100;
    }
}
var theWindow = new viewport(window);

// Put slidein values in data-attributes (not for safari because it calculates the CSS left/right properties in crazy ways)
if (!is_safari) {
    jQuery(window).on('load resize', function(){
        jQuery( mnConfig.parallax.slideInSelector ).each(function(){
            // Set element back to inherit stylesheet css
            jQuery(this).css('left','');
            jQuery(this).css('right','');
            
            
            // if css('side') returns a percentage value, convert it to a pixel value
            function storeSideValue( el, side ) {
                
                if ( el.css(side).indexOf('%') >= 0 ) {
                    // Convert side to pixels
                    var percentage = parseFloat( el.css(side) );
                    var parentWidth = el.parent().width();
                    var pixels = percentage / 100 * parentWidth;
                    el.attr('data-orig-'+side, pixels);
                } else if ( el.css(side).indexOf('px') >= 0 ){
                    el.attr('data-orig-'+side, parseFloat( el.css(side) ) );

                } else {
                    el.attr('data-orig-'+side, 0 );
                }
            }

            // Save CSS values
            storeSideValue( jQuery(this), 'left' );
            storeSideValue( jQuery(this), 'right' );


        });
    });
}

jQuery(window).load(function(){
    $window.bind('scroll DOMMouseScroll', update);
    update();
})


// Function to update parallax effects
function update(){
    if ( mnConfig.parallax.enabledOnMobile || jQuery(window).width() > 768 ) {
        var pos = $window.scrollTop();
        
        // Background Parallax
        jQuery( mnConfig.parallax.parallaxSelector ).each(function() {
            var width = (window.innerWidth > 0) ? window.innerWidth : screen.width;
            
            var $element = jQuery(this);
            var height = $element.height();
            var currentOffset = $element.offset();
            jQuery(this).css('backgroundPosition', '50% ' + ( ( pos-currentOffset.top ) * mnConfig.parallax.velocity) + 'px');
        });

        // Images slide-in
        jQuery( mnConfig.parallax.slideInSelector ).each(function(){

            if (jQuery(this).parents('.image-block-right').length > 0 ) {
                var side = 'left';
            } else {
                var side = 'right';
            }
            $element = jQuery(this);

            // Get initial settings
            // Set element back to inherit stylesheet css

            $element.css('left','');
            $element.css('right','');
            var origPos = {};


            origPos['left'] = parseFloat( $element.attr('data-orig-left') );
            origPos['right'] = parseFloat( $element.attr('data-orig-right') );


            

            // Set initial CSS
            $element.css({
                position: "relative",
                opacity: 0
            })
            $element.css(side, mnConfig.parallax.slideDistance + origPos[side]  );

            // Update viewport 
            theWindow.update();

            var $elem = jQuery(this);
            function slideOffset() {
                if ($elem.offset().top - theWindow.middlePoint < 0) {
                    return 0;
                } else if ($elem.offset().top - theWindow.middlePoint > mnConfig.parallax.slideDistance) {
                    return 1;
                } else {
                    return ($elem.offset().top - theWindow.middlePoint) / mnConfig.parallax.slideDistance;
                }
            }
            $elem.css(side, mnConfig.parallax.slideDistance * slideOffset()  + origPos[side] );
            $elem.css("opacity", 1 - slideOffset())


        });
    } else {
        // Set Slide-in to normal
        jQuery( mnConfig.parallax.slideInSelector ).each(function(){
            $element = jQuery(this);
            // Set element back to inherit stylesheet css
            $element.css('left','');
            $element.css('right','');
            $element.css('opacity','');
        });

        // Set backgroundPosition to normal
        jQuery( mnConfig.parallax.parallaxSelector ).each(function() {
            jQuery(this).css('backgroundPosition', '');
        });
    }
    
};


// Fire update() for all touch movements
jQuery(window).on('touchstart touchend touchmove mousewheel touchcancel gesturestart gestureend gesturechange orientationchange', function(){
        //alert($(window).scrollTop());
        jQuery(window).trigger('scroll');
    });

