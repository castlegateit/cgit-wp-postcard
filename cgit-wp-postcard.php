<?php

/*

Plugin Name: Castlegate IT WP Postcard
Plugin URI: http://github.com/castlegateit/cgit-wp-postcard
Description: Quick and easy pre-defined templates for Postman.
Version: 2.1
Author: Castlegate IT
Author URI: http://www.castlegateit.co.uk/
License: MIT

*/

use Cgit\Postcard\Plugin;

// Load plugin
require __DIR__ . '/src/autoload.php';
require __DIR__ . '/functions.php';

// Initialization
add_action('plugins_loaded', function() {
    Plugin::getInstance();
}, 20);
