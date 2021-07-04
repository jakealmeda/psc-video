<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


class psc_init {


	/**
	 * MAIN FUNCTION TO CALL VIA TEMPLATES
	 */
	/*function psc_get_entries() {

	}*/


	/**
	 * MAIN FUNCTION TO CALL VIA HOOKS
	 */
	function psc_hook_entries() {

		$args = array();
		//get_page_by_path( "/this-is-the-question", "ARRAY_A", get_post_types() );
		// PULL POST ID FROM THE URL
		$pid = url_to_postid( $_SERVER['REQUEST_URI'] , '_wpg_def_keyword', true );
		
		$args[] = $pid;
		// loop through each repeater's row
		/*if( have_rows( 'hook_menu', $post_id ) ):
			
			while( have_rows( 'hook_menu', $post_id ) ): the_row();

				$args = array (
						'pid'						=> $post_id,
						'content_wrap'				=> get_sub_field( 'content_wrap' ),
						'content_header_wrap'		=> get_sub_field( 'content_header_wrap' ),
						'content_selector'			=> get_sub_field( 'content_selector' ),
						'content_footer_wrap'		=> get_sub_field( 'content_footer_wrap' ),
						'menu_entries'				=> get_sub_field( 'menu_entries' ),
						//'view_template'				=> get_sub_field( 'view_template' ),
					);
				
				// display fields based on the hook	| pass the variable to the function
				add_action( get_sub_field( 'menu_hook' ), function() use ( $args ) {

					$this->setup_sfmenux_get_menus( $args );

				});

			endwhile;
		
		endif;*/
		add_action( 'genesis_after_content', function() use ( $args ) {

			$this->here( $args );

		});
		
	}

			public function here( $args ) {
				echo 'ISSACHAR<hr />';
				var_dump($args);
			}
	/**
	 * Loop through the entry repeater
	 */
	public function setup_sfmenux_get_menus( $args ) {

	}

	
	/**
	 * Handle the display
	 */
	public function __construct() {

		// Enqueue scripts
		if ( !is_admin() ) {

//			add_action( 'wp_enqueue_scripts', array( $this, 'setup_sfmenux_enqueue_scripts' ), 20 );

			add_action( 'init', array( $this, 'psc_hook_entries' ) );

		}

	}

}