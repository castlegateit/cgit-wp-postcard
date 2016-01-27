<?php

/**
 * Return contact form by ID
 */
function cgit_postcard($id = 'default') {
    return Cgit\Postcard::get($id);
}
