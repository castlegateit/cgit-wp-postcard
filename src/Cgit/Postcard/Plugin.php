<?php

namespace Cgit\Postcard;

class Plugin
{
    /**
     * Singleton class instance
     *
     * @var Plugin
     */
    private static $instance;

    /**
     * Private constructor
     *
     * @return void
     */
    private function __construct()
    {
        // Register default form
        $this->registerDefaults();

        // Add shortcode
        add_shortcode('postcard', [$this, 'shortcode']);
    }

    /**
     * Return the singleton class instance
     *
     * @return Plugin
     */
    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    /**
     * Register default form
     *
     * You can access this form via Cgit\Postcard::get(), the global
     * cgit_postcard() function, or the postcard shortcode.
     *
     * @return void
     */
    private function registerDefaults()
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
