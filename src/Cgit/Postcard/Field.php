<?php

namespace Cgit\Postcard;

/**
 * Assemble HTML form field
 */
class Field
{
    /**
     * Field options
     */
    private $options = [];

    /**
     * Input element attributes
     */
    private $attributes = [];

    /**
     * Constructor
     */
    public function __construct($options)
    {
        // Set options
        $this->options = $options;

        // Set input ID and attributes
        $this->setId();
        $this->setAttributes();
    }

    /**
     * Set input element ID
     */
    private function setId()
    {
        if (isset($this->options['id'])) {
            return;
        }

        $prefix = '';
        $sep = '_';

        if (isset($this->options['form'])) {
            $prefix = $this->options['form'] . $sep;
        }

        $this->options['id'] = $prefix . $this->options['name'];
    }

    /**
     * Set default input element attributes
     */
    private function setAttributes()
    {
        $standard = [
            'id',
            'max',
            'maxlength',
            'min',
            'minlength',
            'name',
            'pattern',
            'placeholder',
            'type',
        ];

        $booleans = [
            'disabled',
            'required',
        ];

        // Standard attributes
        foreach ($standard as $attr) {
            if (isset($this->options[$attr])) {
                $this->attributes[$attr] = $this->options[$attr];
            }
        }

        // Boolean attributes
        foreach ($booleans as $attr) {
            if (isset($this->options[$attr]) && $this->options[$attr]) {
                $this->attributes[$attr] = $attr;
            }
        }
    }

    /**
     * Return formatted HTML attributes
     */
    private function getAttributes()
    {
        return $this->formatAttributes($this->attributes);
    }

    /**
     * Convert array to HTML attributes
     *
     * Takes an associative array of attributes and returns the keys and values
     * as HTML attributes. Values can also be arrays, which will be converted
     * into a space-separated list on output.
     */
    private function formatAttributes($attr = [])
    {
        $items = [];

        foreach ($attr as $key => $value) {
            if (is_array($value)) {
                $value = implode(' ', $value);
            }

            $items[] = $key . '="' . $value . '"';
        }

        return implode(' ', $items);
    }

    /**
     * Render HTML output
     */
    public function render()
    {
        $type = $this->options['type'];
        $method = 'render' . ucfirst(strtolower($type));

        if (!method_exists($this, $method)) {
            $method = 'renderText';
        }

        ob_start();

        $this->$method();

        $field = ob_get_clean();
        $field = apply_filters('cgit_postcard_field', $field);
        $field = apply_filters('cgit_postcard_field_' . $type, $field);

        return $field;
    }

    /**
     * Render text field
     *
     * Because of their similar appearance and behaviour, this template is also
     * used for date, datetime, email, number, password, search, tel, time, and
     * url inputs.
     */
    private function renderText()
    {
        $this->attributes['class'] = 'text-input';
        $this->attributes['value'] = $this->options['value'];

        ?>
        <div class="field">
            <label for="<?= $this->attributes['id'] ?>">
                <?= $this->options['label'] ?>
            </label>
            <input <?= $this->getAttributes() ?> />
            <?= $this->options['error'] ?>
        </div>
        <?php
    }

    /**
     * Render textarea field
     */
    private function renderTextarea()
    {
        $this->attributes['class'] = 'text-input';
        unset($this->attributes['type']);

        ?>
        <div class="field">
            <label for="<?= $this->attributes['id'] ?>">
                <?= $this->options['label'] ?>
            </label>
            <textarea <?= $this->getAttributes() ?>><?= $this->options['value'] ?></textarea>
            <?= $this->options['error'] ?>
        </div>
        <?php
    }

    /**
     * Render checkbox field
     */
    private function renderCheckbox()
    {
        ?>
        <div class="field checkbox-field">
            <span class="checkbox-field-label">
                <?= $this->options['label'] ?>
            </span>
            <?php

            foreach ($this->options['options'] as $value => $label) {
                $attributeValues = [
                    'type' => 'checkbox',
                    'name' => $this->attributes['name'] . '[]',
                    'value' => $value,
                    'class' => 'checkbox-input',
                ];

                if (in_array($value, $this->options['value'])) {
                    $attributeValues['checked'] = 'checked';
                }

                $attributes = $this->formatAttributes($attributeValues);

                ?>
                <label>
                    <input <?= $attributes ?> />
                    <?= $label ?>
                    <?= $this->options['error'] ?>
                </label>
                <?php
            }

            ?>
        </div>
        <?php
    }

    /**
     * Render radio field
     */
    private function renderRadio()
    {
        ?>
        <div class="field radio-field">
            <span class="radio-field-label">
                <?= $this->options['label'] ?>
            </span>
            <?php

            foreach ($this->options['options'] as $value => $label) {
                $attributeValues = [
                    'type' => 'radio',
                    'name' => $this->attributes['name'],
                    'value' => $value,
                    'class' => 'radio-input',
                ];

                if ($this->options['value'] == $value) {
                    $attributeValues['checked'] = 'checked';
                }

                $attributes = $this->formatAttributes($attributeValues);

                ?>
                <label>
                    <input <?= $attributes ?> />
                    <?= $label ?>
                    <?= $this->options['error'] ?>
                </label>
                <?php
            }

            ?>
        </div>
        <?php
    }

    /**
     * Render select field
     */
    private function renderSelect()
    {
        $this->attributes['class'] = 'select-input';
        unset($this->attributes['type']);
        unset($this->attributes['value']);

        ?>
        <div class="field">
            <label for="<?= $this->attributes['id'] ?>">
                <?= $this->options['label'] ?>
            </label>
            <select <?= $this->getAttributes() ?>>
            <?php

            foreach ($this->options['options'] as $value => $label) {
                $selected = '';

                if ($this->options['value'] == $value) {
                    $selected = ' selected="selected"';
                }
                ?>
                <option value="<?= $value ?>"<?= $selected ?>>
                    <?= $label ?>
                </option>
                <?= $this->options['error'] ?>
                <?php
            }

            ?>
            </select>
        </div>
        <?php
    }

    /**
     * Render hidden field
     */
    private function renderHidden()
    {
        ?>
        <input type="hidden" name="<?= $this->options['name'] ?>" value="<?= $this->options['value'] ?>" />
        <?php
    }

    /**
     * Render button field
     */
    private function renderButton()
    {
        $attributes = $this->formatAttributes([
            'name' => $this->attributes['name'],
            'class' => 'button-input',
        ]);

        ?>
        <div class="field button-field">
            <button <?= $attributes ?>><?= $this->options['label'] ?></button>
        </div>
        <?php
    }

    /**
     * Render submit field
     *
     * For the purposes of this plugin, the submit field type is synonymous with
     * the button field type.
     */
    private function renderSubmit()
    {
        $this->renderButton();
    }
}
