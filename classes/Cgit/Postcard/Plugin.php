<?php

namespace Cgit\Postcard;

class Plugin
{
    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        $this->registerDefaultForm();
        add_shortcode('postcard', [$this, 'shortcode']);
    }

    /**
     * Register default form
     *
     * You can access this form via Cgit\Postcard::get(), the global
     * cgit_postcard() function, or the postcard shortcode.
     *
     * @return void
     */
    private function registerDefaultForm()
    {
        $default = new \Cgit\Postcard('default');

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
            'label' => 'Subject',
            'type' => 'text',
        ]);

        $default->field('message', [
            'label' => 'Message',
            'type' => 'textarea',
            'required' => true,
        ]);

        $default->field('button', [
            'type' => 'button',
            'label' => 'Send Message',
            'exclude' => true,
        ]);
    }

    /**
     * Add shortcode
     *
     * @return void
     */
    public function shortcode($atts)
    {
        $atts = shortcode_atts([
            'id' => 'default',
        ], $atts);

        return \Cgit\Postcard::get($atts['id']);
    }
}
