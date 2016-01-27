# Castlgate IT WP Postcard #

Postcard provides simple, pre-defined templates for the [Postman](http://github.com/castlegateit/cgit-wp-postman) plugin. It doesn't give you much control over your HTML output, but it does make it quick and easy to generate a form to a Postman form.

## Postcard ##

The `Cgit\Postcard` class is used to create the form.

### `Cgit\Postcard->id` ###

A unique identifier for this form. This is set in the constructor and is used to create a hidden field that can identified by `Cgit\Postman->detect()`.

### `Cgit\Postcard->action` ###

The form `action` attribute. By default, this is blank and so the form submits to the current URL.

### `Cgit\Postcard->detectName` ###

The name of the unique hidden field. By default, this is `postcard`.

### `Cgit\Postcard->errorTemplate` ###

Works as per `Cgit\Postman->errorTemplate`, but the default value is `<span class="error">%s</span>`.

### `Cgit\Postcard->errorMessage` ###

The default field error message. Equivalent to `Cgit\Postman->errorMessage`.

### `Cgit\Postcard->successMessage` ###

Text to display when the form has submitted successfully. The default value is "Your message has been sent. Thank you.".

### `Cgit\Postcard->novalidate` ###

Set to `true` to add the `novalidate` attribute to the form. Useful if your custom validation differs significantly from the default HTML validation.

### `Cgit\Postcard->field($name, $options)` ###

Adds a new field to the form. The `$options` array is a superset of the options for `Cgit\Postman->field()`. The _additional_ options are:

    $options = [
        'disabled',
        'id',
        'max',
        'maxlength',
        'min',
        'minlength',
        'name',
        'pattern',
        'placeholder',
        'type', // HTML input type
    ];

### `Cgit\Postcard->render()` ###

Return the complete HTML output for the form.

### `Cgit\Postcard::get($id = 'default')` ###

Return the complete HTML output for an existing form by ID. The plugin includes a basic form called `default`.

## Function and shortcode ##

The plugin includes a function and a shortcode to return forms by ID. The following are all equivalent:

    $foo = Cgit\Postcard::get('foo');
    $foo = cgit_postcard('foo');
    $foo = do_shortcode('[postcard id="foo"]');

## Example ##

The following will create the same form as the [example given for the Postman plugin](http://github.com/castlegateit/cgit-wp-postman).

    $form = new Cgit\Postcard('contact');

    $form->errorMessage = 'That doesn\'t work';

    $form->field('username', [
        'type' => 'text',
        'label' => 'Name',
    ]);

    $form->field('email', [
        'type' => 'email',
        'label' => 'Email',
        'required' => true,
        'validate' => [
            'type' => 'email',
            'match' => 'confirm_email',
        ],
        'error' => 'Please enter a valid email address'
    ]);

    $form->field('submit', [
        'type' => 'button',
        'label' => 'Send Message',
    ]);

    echo $form->render();

Alternatively, this form could be returned by any of the following:

    echo Cgit\Postcard::get('contact');
    echo cgit_postcard('contact');
    echo do_shortcode('[postcard id="contact"');
