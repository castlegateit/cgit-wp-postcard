<?php

/*

Plugin Name: Castlegate IT WP Postcard
Plugin URI: http://github.com/castlegateit/cgit-wp-postcard
Description: Quick and easy pre-defined templates for Postman.
Version: 2.4
Author: Castlegate IT
Author URI: http://www.castlegateit.co.uk/
License: MIT

*/

use Cgit\Postcard\Plugin;

define('CGIT_POSTCARD_PLUGIN_FILE', __FILE__);

// Load plugin
require __DIR__ . '/src/autoload.php';
require __DIR__ . '/functions.php';

// Initialization
Plugin::getInstance();
