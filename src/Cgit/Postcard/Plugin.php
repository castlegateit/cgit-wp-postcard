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
        // Load the plugin after the Postman plugin
        add_action('plugins_loaded', [$this, 'init'], 20);
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
     * Initialization
     *
     * @return void
     */
    public function init()
    {
        if (!$this->checkDeps()) {
            return;
        }

        $this->registerDefaults();
        add_shortcode('postcard', [$this, 'shortcode']);
    }

    /**
     * Check dependencies
     *
     * Looks for plugin dependencies and automatically deactivates the plugin if
     * any are missing.
     *
     * @return boolean
     */
    private function checkDeps()
    {
        if (!class_exists('Cgit\Postman')) {
            require_once ABSPATH . '/wp-admin/includes/plugin.php';

            $this->displayMessage('Postcard has been deactivated because the'
                . ' Postman plugin is not active.', 'Warning');
            deactivate_plugins(plugin_basename(CGIT_POSTCARD_PLUGIN_FILE));

            return false;
        }

        return true;
    }

    /**
     * Display an error message
     *
     * @return void
     */
    private function displayMessage($message, $heading = 'Error')
    {
        add_action('admin_notices', function () use ($message) {
            ?>
            <div class="error">
                <p><strong><?= $heading ?>:</strong> <?= $message ?></p>
            </div>
            <?php
        });
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
