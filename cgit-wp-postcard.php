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

/**
 * Load plugin
 */
add_action('plugins_loaded', function() {
    include __DIR__ . '/src/autoload.php';
    include __DIR__ . '/defaults.php';
    include __DIR__ . '/functions.php';
    include __DIR__ . '/shortcodes.php';
}, 20);
