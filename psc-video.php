<?php
/**
 * Plugin Name: PSC Videos
 * Description: Handles the data collection and display of the custom post type, Video.
 * Version: 1.0
 * Author: Jake Almeda
 * Author URI: http://smarterwebpackages.com/
 * Network: true
 * License: GPL2
 */


if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

include_once( 'lib/acf-psc-autofill.php' );
include_once( 'lib/acf-psc-processor.php' );
include_once( 'lib/acf-psc-func.php' );
include_once( 'lib/acf-psc-hook.php' );


// simply return this plugin's main directory
function psc_plugin_dir_path() {

    return plugin_dir_path( __FILE__ );

}