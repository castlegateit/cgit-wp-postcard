<?php

/**
 * Register default form
 *
 * You can access this form via Cgit\Postcard::get(), the global cgit_postcard()
 * function, or the postcard shortcode.
 */
use Cgit\Postcard;

$default = new Postcard('default');

$default->field('username', [
    'label' => 'Name',
    'type' => 'text',
    'required' => true,
]);

$default->field('email', [
    'label' => 'Email',
    'type' => 'email',
    'required' => true,
    'validate' => [
        'type' => 'email',
    ],
]);

$default->field('subject', [
    'label' => 'Name',
    'type' => 'text',
]);

$default->field('textarea', [
    'label' => 'Message',
    'type' => 'textarea',
    'required' => true,
]);

$default->field('button', [
    'type' => 'button',
    'label' => 'Send Message',
]);
