<?php

/*

Plugin Name: Castlegate IT Postcard
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
    include dirname(__FILE__) . '/field.php';
    include dirname(__FILE__) . '/postcard.php';
    include dirname(__FILE__) . '/defaults.php';
    include dirname(__FILE__) . '/functions.php';
    include dirname(__FILE__) . '/shortcodes.php';
}, 20);
