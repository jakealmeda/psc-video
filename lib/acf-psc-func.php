<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


/**
* MAIN FUNCTION
*/
function psc_get_entries() {

	$outs = ''; // declare empty variable for the loop

	$r = 'video_entries_repeater';

	if( have_rows( $r ) ):

		while ( have_rows( $r ) ) : the_row();

			$use_func = get_sub_field( 'video-layout' );
			//if( array_key_exists( 'hook-enable', $use_func ) && !$use_func[ 'hook-enable' ] ) {

				// using function
				$vars = array(
					'e_layout'			=> $use_func[ 'layout-video' ],
					//'e_layout'			=> get_sub_field( 'layout-video' ),
					'e_entries'			=> get_sub_field( 'video-entries' ),
				);

				// class selectors
				$css_class = $use_func[ 'container_class' ];
				//$css_class = get_sub_field( 'container_class' );
				if( empty( $css_class ) ) :
					$class = '';
				else :
					$class = ' class="'.$css_class.'"';
				endif;

				// inline style
				$css_inline = $use_func[ 'container_in_line_style' ];
				//$css_inline = get_sub_field( 'container_in_line_style' );
				if( empty( $css_inline ) ) :
					$inline = '';
				else :
					$inline = ' style="'.$css_inline.'"';
				endif;

				$q = new PSC_Pull_Entries();
				$w = $q->process_entries( $vars );
				if( !empty( $w ) )
					$outs .= '<div'.$class.$inline.'>'.$w.'</div>';

			/*} else {

				// do nothing | display is handled through hooks
				$outs .= '';

			}*/

		endwhile;

	/*else:

		$pid = get_the_ID();

		if( 'video' == get_post_type( $pid ) ) {



		}
	*/
	endif;

	return $outs;

}

