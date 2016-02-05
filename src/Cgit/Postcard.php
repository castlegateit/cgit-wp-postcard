<?php

namespace Cgit;

use Cgit\Postcard\Field;

/**
 * Simple, pre-defined templates for Postman
 */
class Postcard
{
    /**
     * Unique identifier for this form
     */
    public $id;

    /**
     * Postman object instance
     */
    private $form;

    /**
     * Default form action
     */
    public $action = '';

    /**
     * Hidden field name for unique ID
     */
    public $detectName = 'postcard';

    /**
     * Form has errors?
     */
    private $hasErrors = false;

    /**
     * Error template
     */
    private $errorTemplate = '<span class="error">%s</span>';

    /**
     * Success message
     */
    public $successMessage = 'Your message has been sent. Thank you.';

    /**
     * Form error message
     */
    public $errorMessage = 'Your message contains errors. '
        . 'Please correct them and try again.';

    /**
     * Field error message
     */
    public $errorMessageSingle = false;

    /**
     * Prevent client-side validation
     */
    public $novalidate = false;

    /**
     * Form fields
     */
    private $fields = [];

    /**
     * Mail settings
     */
    public $mailTo;
    public $mailFrom;
    public $mailSubject;
    public $mailHeaders = [];

    /**
     * Constructor
     */
    public function __construct($id)
    {
        $this->id = $id;
        $this->form = new Postman();

        // Set indicator field and update form settings
        $this->setConditions();
        $this->updateSettings();
    }

    /**
     * Add default indicator field
     *
     * Use the unique ID set in the constructor to distinguish the current form
     * data from any other requests.
     */
    private function setConditions()
    {
        $this->form->detect([
            $this->detectName => $this->id,
        ]);

        $this->field($this->detectName, [
            'type' => 'hidden',
            'value' => $this->id,
        ]);
    }

    /**
     * Add field
     *
     * Appends field to array of fields and adds field to Postman form instance
     * with options. Also updates the version of the form available in the
     * shortcode.
     */
    public function field($name, $options)
    {
        // Indicator fields are not registered with Postman
        if ($name != $this->detectName) {
            $this->form->field($name, $options);
        }

        $this->fields[$name] = $options;
        $this->update();
    }

    /**
     * Render HTML form output
     */
    public function render()
    {
        $this->updateSettings();

        // If form has been submitted successfully, return success message
        if ($this->form->submit()) {
            return '<div class="cgit-postcard-message success"><p>'
                . $this->successMessage . '</p></div>';
        }

        $items = [];
        $form = '';
        $novalidate = '';

        foreach (array_keys($this->fields) as $name) {
            $items[] = $this->renderField($name);
        }

        if ($this->novalidate) {
            $novalidate = ' novalidate="novalidate"';
        }

        if ($this->hasErrors) {
            $form .= '<div class="cgit-postcard-message error><p>'
                . $this->errorMessage . '</p></div>';
        }

        $form .= '<form action="' . $this->action . '" method="post"'
            . $novalidate . '>' . implode(PHP_EOL, $items) . '</form>';

        $form = apply_filters('cgit_postcard_form', $form);

        return $form;
    }

    /**
     * Render HTML field output
     *
     * Add values and errors to the array of options and get the HTML from the
     * Field class.
     */
    private function renderField($name)
    {
        $options = $this->fields[$name];
        $options['name'] = $name;
        $options['form'] = $this->id;
        $options['error'] = $this->form->error($name);

        if (!isset($options['value'])) {
            $options['value'] = $this->form->value($name);
        }

        if ($options['error']) {
            $this->hasErrors = true;
        }

        $field = new Field($options);

        return $field->render();
    }

    /**
     * Save form
     *
     * This makes the form available via the cgit_postcard_forms filter, which
     * is used in the global cgit_postcard() function and the postcard
     * shortcode.
     */
    private function update()
    {
        add_filter('cgit_postcard', function($forms) {
            $forms[$this->id] = $this->render();

            return $forms;
        });

    }

    /**
     * Update form settings
     *
     * Sends settings from the Postcard instance to the Postman instance. Should
     * be called before doing anything important with Postman.
     */
    private function updateSettings()
    {
        // Set error format
        $this->form->errorTemplate = $this->errorTemplate;

        if ($this->errorMessageSingle) {
            $this->form->errorMessage = $this->errorMessageSingle;
        }

        // Set mail settings and headers
        $settings = ['to', 'from', 'subject', 'headers'];

        foreach ($settings as $setting) {
            $property = 'mail' . ucfirst(strtolower($setting));

            if ($this->$property) {
                $this->form->$property = $this->$property;
            }
        }
    }

    /**
     * Get a saved form
     */
    public static function get($id = 'default')
    {
        return apply_filters('cgit_postcard', [])[$id];
    }
}