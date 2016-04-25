<?php

/*

Plugin Name: Castlegate IT WP Postcard
Plugin URI: http://github.com/castlegateit/cgit-wp-postcard
Description: Quick and easy pre-defined templates for Postman.
Version: 1.0
Author: Castlegate IT
Author URI: http://www.castlegateit.co.uk/
License: MIT

*/

require __DIR__ . '/src/autoload.php';

/**
 * Load plugin
 */
add_action('plugins_loaded', function() {
    require __DIR__ . '/defaults.php';
    require __DIR__ . '/functions.php';
    require __DIR__ . '/shortcodes.php';
}, 20);
