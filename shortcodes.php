<?php

/**
 * Add postcard shortcode
 */
add_shortcode('postcard', function($atts) {
    $atts = shortcode_atts([
        'id' => 'default',
    ], $atts);

    return Cgit\Postcard::get($atts['id']);
});
