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





?>