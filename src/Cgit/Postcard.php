<?php

namespace Cgit;

use Cgit\Postcard\Field;

/**
 * Simple, pre-defined templates for Postman
 */
class Postcard
{
    /**
     * Postman object instance
     *
     * @var \Cgit\Postman
     */
    public $form;

    /**
     * Field class
     *
     * The fully qualified name of the class used to generate the HTML of each
     * field. This can be overridden to change the HTML output.
     *
     * @var string
     */
    public $fieldClass = '\Cgit\Postcard\Field';

    /**
     * Default form action
     *
     * @var string
     */
    public $action = '';

    /**
     * Form has errors?
     *
     * @var boolean
     */
    private $errors = false;

    /**
     * Default error message
     *
     * This is used by the Postman property of the same name. It can be
     * overridden for each field using the "error" item in the array of field
     * options.
     *
     * @var string
     */
    public $errorMessage = 'Invalid input';

    /**
     * Default error template
     *
     * This is used by the Postman property of the same name. See Postman for
     * details.
     *
     * @var string
     */
    public $errorTemplate = '<span class="error">%s</span>';

    /**
     * Default form error message
     *
     * @var string
     */
    public $formError = 'Your message contains errors. Please correct them and try again.';

    /**
     * Default form success message
     *
     * @var string
     */
    public $formSuccess = 'Your message has been sent. Thank you.';

    /**
     * Prevent client-side validation
     *
     * @var boolean
     */
    public $novalidate = false;

    /**
     * Form fields
     *
     * @var array
     */
    private $fields = [];

    /**
     * Constructor
     *
     * Creates a new Postman instance, assigns the form ID, and creates the
     * required hidden field to identify submissions from that form.
     *
     * @param string $id
     * @return void
     */
    public function __construct($id)
    {
        $this->form = new Postman($id);

        // Create hidden input to identify form on submission
        $this->field('postman_form_id', [
            'type' => 'hidden',
            'value' => $this->form->id,
            'exclude' => true,
        ]);

        // Update form settings
        $this->update();
    }

    /**
     * Update form settings
     *
     * Sends settings from the Postcard instance to the Postman instance. Should
     * be called before doing anything important with Postman.
     *
     * @return void
     */
    private function update()
    {
        $this->form->errorTemplate = $this->errorTemplate;
        $this->form->errorMessage = $this->errorMessage;
    }

    /**
     * Add field
     *
     * Appends field to array of fields and adds field to Postman form instance
     * with options. Also updates the version of the form available in the
     * shortcode.
     *
     * @param string $name
     * @param array $options
     * @return void
     */
    public function field($name, $options)
    {
        $this->form->field($name, $options);
        $this->fields[$name] = $options;
        $this->save();
    }

    /**
     * Save form
     *
     * This makes the form available via the cgit_postcard_forms filter, which
     * is used in the global cgit_postcard() function and the postcard
     * shortcode.
     *
     * @return void
     */
    private function save()
    {
        add_filter('cgit_postcard', function($instances) {
            $instances[$this->form->id] = $this;

            return $instances;
        });
    }

    /**
     * Render HTML form output
     *
     * @return string
     */
    public function render()
    {
        $this->update();

        // If form has been submitted successfully, return success message
        if ($this->form->submit()) {
            return '<div class="cgit-postcard-message success"><p>'
                . $this->formSuccess . '</p></div>';
        }

        $items = [];
        $form = '';
        $novalidate = '';

        // Add each rendered field to the array of fields
        foreach (array_keys($this->fields) as $name) {
            $items[] = $this->renderField($name);
        }

        // Add novalidate attribute
        if ($this->novalidate) {
            $novalidate = ' novalidate="novalidate"';
        }

        // If the form has been submitted with errors, add the error message
        if ($this->errors) {
            $form .= '<div class="cgit-postcard-message error"><p>'
                . $this->formError . '</p></div>';
        }

        // Assemble the form element from the array of rendered fields
        $form .= '<form action="' . $this->action . '" method="post"'
            . $novalidate . '>' . implode(PHP_EOL, $items) . '</form>';

        // Allow the rendered HTML to be filtered
        $form = apply_filters('cgit_postcard_form', $form);

        return $form;
    }

    /**
     * Render HTML field output
     *
     * Add values and errors to the array of options and get the HTML from the
     * Field class.
     *
     * @param string $name
     * @return string
     */
    private function renderField($name)
    {
        $options = $this->fields[$name];
        $options['name'] = $name;
        $options['form'] = $this->form->id;
        $options['error'] = $this->form->error($name);

        if (!isset($options['value'])) {
            $options['value'] = $this->form->value($name);
        }

        if ($options['error']) {
            $this->errors = true;
        }

        $field_class = $this->fieldClass;
        $field = new $field_class($options);

        return $field->render();
    }

    /**
     * Get a saved form
     *
     * @param string $id
     * @return string
     */
    public static function get($id = 'default')
    {
        return apply_filters('cgit_postcard', [])[$id]->render();
    }
}
