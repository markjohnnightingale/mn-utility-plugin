<?php
/**
 * Override some Restaurant Reservation functions
 */

function rtb_print_element_class( $slug, $additional_classes = array() ) {
	$classes = empty( $additional_classes ) ? array() : $additional_classes;

	// Add foundation classes if text field
	if ( in_array('rtb-text',$additional_classes) || in_array('rtb-select', $additional_classes)) {
		array_push($classes, 'small-12', 'large-4', 'medium-4', 'columns');

	} elseif ( in_array('rtb-textarea', $additional_classes) ) {
		array_push($classes, 'small-12', 'columns');
	}

	if ( ! empty( $slug ) ) {
		array_push( $classes, $slug );
	}

	$class_attr = esc_attr( join( ' ', $classes ) );

	return empty( $class_attr ) ? '' : sprintf( 'class="%s"', $class_attr );

}