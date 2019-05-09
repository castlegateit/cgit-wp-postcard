<?php

/*

Plugin Name: Castlegate IT WP Postcard
Plugin URI: http://github.com/castlegateit/cgit-wp-postcard
Description: Quick and easy pre-defined templates for Postman.
Version: 2.6
Author: Castlegate IT
Author URI: http://www.castlegateit.co.uk/
License: AGPLv3

*/

if (!defined('ABSPATH')) {
    wp_die('Access denied');
}

define('CGIT_POSTCARD_PLUGIN', __FILE__);

add_action('cgit_postman_loaded', function () {
    require_once __DIR__ . '/classes/autoload.php';
    require_once __DIR__ . '/functions.php';

    $plugin = new \Cgit\Postcard\Plugin();

    do_action('cgit_postcard_loaded');
});
