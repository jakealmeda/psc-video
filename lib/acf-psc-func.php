<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


/**
* MAIN FUNCTION
*/
function psc_get_entries() {

	global $post;
	
	if( $post->post_type == 'video' ) :

		$use_func = get_field( 'video-layout' );
		if( array_key_exists( 'use-hooks', $use_func ) && !$use_func[ 'use-hooks' ] ) {

			$vars = array(
				'e_layout'			=> $use_func[ 'layout-video' ],
				'e_entries'			=> get_field( 'video-entries' ),
			);

			// class selectors
			$css_class = $use_func[ 'container_class' ];
			if( empty( $css_class ) ) :
				$class = '';
			else :
				$class = ' class="'.$css_class.'"';
			endif;

			// inline style
			$css_inline = $use_func[ 'container_in_line_style' ];
			if( empty( $css_inline ) ) :
				$inline = '';
			else :
				$inline = ' style="'.$css_inline.'"';
			endif;

			$q = new PSC_Pull_Entries();
			return '<div'.$class.$inline.'>'.$q->process_entries( $vars ).'</div>';

		} else {

			// do nothing | display is handled through hooks
			return FALSE;

		}

	endif;

}

