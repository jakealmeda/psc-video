<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class PSC_Pull_Entries {

	public function process_entries( $vars ) {

		$out = ''; // intialize empty variable for the loop

		// get the layout
		$format = $vars[ 'e_layout' ];
		
		/**
		 * GET THE ENTRIES (INDIVIDUAL)
		 */
		$rep_clients = $vars[ 'e_entries' ][ 'entries' ];
		if( is_array( $rep_clients ) && count( $rep_clients ) >= 1 ) :

			foreach( $rep_clients as $cpid ) {

				// get info group
				$vid_info = get_field( 'info', $cpid );

				$vid_info_pic_size = $this->psc_val_arr_key( 'pic_size', $vid_info );

				$vid_pic = $this->psc_val_arr_key( 'pic', $vid_info );
				if( empty( $vid_pic ) ) {
					$vid_pic = '';
					$vid_pic_w = '';
					$vid_pic_h = '';
				} else {
					$vid_pic = $vid_info[ 'pic' ][ 'sizes' ][ $vid_info_pic_size ];
					$vid_pic_w = $vid_info[ 'pic' ][ 'sizes' ][ $vid_info_pic_size.'-width' ];
					$vid_pic_h = $vid_info[ 'pic' ][ 'sizes' ][ $vid_info_pic_size.'-height' ];
				}

				// profile
				$acf_profile = $this->psc_profiler( $vid_info[ 'profile' ] );

				// source
				$acf_source = $this->psc_sourcer( $vid_info[ 'source' ] );

				// links
				$acf_links = $this->psc_links( $vid_info[ 'link-entry' ] );

				// set variables
				$replace_array = array(
						'{@wp_title}'				=> get_the_title( $cpid ),
						'{@acf_oembed}'				=> get_field( 'oembed', $cpid ),
						'{@acf_title}'				=> $vid_info[ 'title' ],
						'{@acf_summary}'			=> $vid_info[ 'summary' ],
						'{@pic_src}'				=> $vid_pic,
						'{@pic_width}'				=> $vid_pic_w,
						'{@pic_height}'				=> $vid_pic_h,
						'{@acf_profile}'			=> $acf_profile,
						'{@acf_source}'				=> $acf_source,
						'{@acf_links}'				=> $acf_links,
					);

				// OUTPUT
				$out .= strtr( $this->psc_get_video_view( $format, 'videos' ), $replace_array );

			}

		endif;
		
		/**
		 * GET THE ENTRIES (TAXONOMY)
		 */
		$categ = $vars[ 'e_entries' ][ 'video-category' ];
		if( !empty( $categ ) ) :

			$max_count = $vars[ 'e_entries' ][ 'max_links_to_display' ];
			$orderby = $vars[ 'e_entries' ][ 'order-by' ];
			$order = $vars[ 'e_entries' ][ 'order' ];

			// loop through the array
			for( $t = 0; $t <= ( count( $categ ) - 1 ); $t++ ) {
				//echo '<h1>'.$categ[ $t ].'</h1>';
				//$ppp = -1;
				$maxout = 0;

				$args = array(
					'post_type' 		=> 'video',
					'post_status' 		=> 'publish',
		//			'posts_per_page' 	=> $ppp,
		//			'post__not_in' 		=> $not_in,
					'orderby' 			=> $orderby,
		    		'order'   			=> $order,
					'tax_query' => array(
						array( 'taxonomy' => 'video_tag', 'field' => 'term_id', 'terms' => $categ[ $t ] )
					),
				);

				// query
				$loop = new WP_Query( $args );
				
				// loop
				if( $loop->have_posts() ):

					// get all post IDs
					while( $loop->have_posts() ): $loop->the_post();

						$maxout++;
						
						// get info group
						$vid_info = get_field( 'info', get_the_ID() );

						// $key, $array
						$vid_info_pic_size = $this->psc_val_arr_key( 'pic_size', $vid_info );

						$vid_pic = $this->psc_val_arr_key( 'pic', $vid_info );
						if( empty( $vid_pic ) ) {
							$vid_pic = '';
							$vid_pic_w = '';
							$vid_pic_h = '';
						} else {
							$vid_pic = $vid_info[ 'pic' ][ 'sizes' ][ $vid_info_pic_size ];
							$vid_pic_w = $vid_info[ 'pic' ][ 'sizes' ][ $vid_info_pic_size.'-width' ];
							$vid_pic_h = $vid_info[ 'pic' ][ 'sizes' ][ $vid_info_pic_size.'-height' ];
						}
						
						// profile
						$acf_profile = $this->psc_profiler( $vid_info[ 'profile' ] );

						// source
						$acf_source = $this->psc_sourcer( $vid_info[ 'source' ] );

						// links
						$acf_links = $this->psc_links( $vid_info[ 'link-entry' ] );

						// set variables
						$replace_array = array(
								'{@wp_title}'				=> get_the_title( get_the_ID() ),
								'{@acf_oembed}'				=> get_field( 'oembed', get_the_ID() ),
								'{@acf_title}'				=> $vid_info[ 'title' ],
								'{@acf_summary}'			=> $vid_info[ 'summary' ],
								'{@pic_src}'				=> $vid_pic,
								'{@pic_width}'				=> $vid_pic_w,
								'{@pic_height}'				=> $vid_pic_h,
								'{@acf_profile}'			=> $acf_profile,
								'{@acf_source}'				=> $acf_source,
								'{@acf_links}'				=> $acf_links,
							);
						
						// OUTPUT
						$out .= strtr( $this->psc_get_video_view( $format, 'videos' ), $replace_array );

						if( $maxout == $max_count ) {
							break;
						}

					endwhile;

					// reset WP Query
					$this->psc_reset_query();

				endif;

			}

		endif;
		
		return $out;

	}


	/**
	 * LINKS (VIDEO) FIELD
	 */
	public function psc_links( $links ) {

		//$vid_links = $vid_info[ 'link-entry' ];
		if( !empty( $links ) ) :

			for( $x=0; $x<=( count( $links ) - 1 ); $x++ ){
				
				$layouts = $links[ $x ][ 'links-layout' ];
				$entriez = $links[ $x ][ 'entry' ];
				$acf_links = '<div class="item-links-rep">'.$this->psc_link_repeater_parse( $layouts, $entriez ).'</div>';

			}

			return $acf_links;

		else :

			return NULL;

		endif;

	}


	/**
	 * SOURCE FIELD
	 * 
	 */
	public function psc_sourcer( $source ) {

		if( !empty( $source ) ) {

			return '<div class="item-source">'.$source.'</div>';

		} else {

			return NULL;

		}

	}


	/**
	 * PROFILE FIELD
	 * 
	 */
	public function psc_profiler( $profile ) {

		if( is_array( $profile ) ) {

			return '<div class="item-profile">'.$this->psc_profile_field( $profile ).'</div>';

		} else {

			return NULL;

		}

	}


	/**
	 * PROFILE FIELD
	 * 
	 */
	public function psc_profile_field( $pids ) {
		
		$outs = '';

		foreach( $pids as $pid ) {
			$outs .= '<div class="item-prof-link"><a href="'.get_the_permalink( $pid ).'">'.get_the_title( $pid ).'</a></div>';
		}

		return $outs;

	}


	/**
	 * LINK REPEATER PARSER 
	 * 
	 */
	public function psc_link_repeater_parse( $layout, $link_entries ) {

		$out = ''; // declare empty variable
		
		for( $x = 0; $x <= ( count( $link_entries ) - 1 ); $x++ ) {

			// true (empty) to show and false (not empty) to hide
			if( empty( $link_entries[ $x ][ 'hide' ] ) ) :

				$link = $link_entries[ $x ][ 'link' ];
				$icon = $link_entries[ $x ][ 'icon' ];
				$attachments = $link_entries[ $x ][ 'attachments' ];

				// selector
				if( $icon == 'none' ) {
					$class_add = '';
				} else {
					$class_add = ' class="item-'.$icon.'"';
				}

				// link target
				if( empty( $link[ 'target' ] ) ) {
					$link_target = '';
				} else {
					$link_target = ' target="'.$link[ 'target' ].'"';
				}

				// upload & URL
				if( $icon == 'upload' ) {

					$pic = wp_get_attachment_image( $attachments[ 'image' ][ 'ID' ], $attachments[ 'image_size' ] );

				} elseif( $icon == 'url' ) {

					$pic = '<img src="'.$attachments[ 'image_url' ].'" />';

				} else {
					$pic = '';
				}

				// set variables
				$replace_array = array(
						'{@link_title}'				=> $link[ 'title' ],
						'{@link_url}'				=> $link[ 'url' ],
						'{@link_target}'			=> $link_target,
						'{@class}'					=> $class_add,
						'{@pic}'					=> $pic,
					);

				// OUTPUT
				$out .= strtr( $this->psc_get_block_view( $layout, 'views' ), $replace_array );

			endif;

		}

		return $out;

	}


	/**
	 * Get VIEW template (INCLUDE)
	 * For: STRTR - Translate characters or replace substrings
	 * https://www.php.net/manual/en/function.strtr.php
	 */
	public function psc_get_block_view( $layout, $folder ) {

		$layout_file = psc_plugin_dir_path_vids().'partials/'.$folder.'/'.$layout;

		return file_get_contents( $layout_file );

	}


	/**
	 * Get Template
	 */
	public function psc_get_video_view( $layout, $folder ) {

	    $layout_file = psc_plugin_dir_path_vids().'partials/'.$folder.'/'.$layout;
	    
	    if( is_file( $layout_file ) ) {

	        ob_start();

	        include $layout_file;

            return ob_get_clean();

	    } else {

	        //$output = FALSE;
            return FALSE;

	    }

	}


	/**
	 * Reset Query
	 */
	public function psc_reset_query() {
		wp_reset_postdata();
		wp_reset_query();
	}


	/**
	 * Simple array & key validation
	 *
	 */
	public function psc_val_arr_key( $key, $array ) {

		if( array_key_exists( $key, $array ) && !empty( $array[ $key ] ) ) {
			return $array[ $key ];
		} else {
			return FALSE; // return nothing
		}

	}

}