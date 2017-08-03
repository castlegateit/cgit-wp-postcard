# Castlgate IT WP Postcard #

Postcard provides simple, pre-defined templates for the [Postman](http://github.com/castlegateit/cgit-wp-postman) plugin. It doesn't give you much control over your HTML output, but it does make it quick and easy to generate a Postman form.

## Postcard ##

The `Cgit\Postcard` class is used to create the form. The constructor requires a single argument, which is used as a unique identifier for that form and which is shared with the `Postman` form:

~~~ php
$postcard = new \Cgit\Postcard('foo');
~~~

### Properties ###

You can access the underlying `Postman` form instance, which includes the field data, errors, and ID:

~~~ php
echo $postcard->form->id;
~~~

You can set the form action, which defaults to the current page:

~~~ php
$postcard->action = 'http://www.example.com/';
~~~

You can edit the form and default individual field messages and the field error message template (see [Postman](http://github.com/castlegateit/cgit-wp-postman)):

~~~ php
$postcard->errorMessage = 'Bad value';
$postcard->errorTemplate = '<strong>%s</strong>';
$postcard->formError = 'You did something wrong.';
$postcard->formSuccess = 'Message sent.';
~~~

You can add the `novalidate` attribute to the HTML form:

~~~ php
$postcard->novalidate = true;
~~~

### Methods ###

The `field()` and `fields()` method are for adding new field(s) to the form. The arguments for these method are a superset of those for the the `field` and `fields` method in `Postman`. The _additional_ options are:

~~~ php
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
    'options', // An array of [ value => labels ] for checkboxes, radio buttons and select elements
    'type', // HTML input type
];
~~~

Return the complete HTML output for the form:

~~~ php
echo $postcard->render();
~~~

Return the complete HTML output for an existing form by ID. The plugin includes a basic form called `default`:

~~~ php
echo Postcard::get($id);
~~~

## Function and shortcode ##

The plugin includes a function and a shortcode to return forms by ID. The following are all equivalent:

~~~ php
$foo = Cgit\Postcard::get('foo');
$foo = cgit_postcard('foo');
$foo = do_shortcode('[postcard id="foo"]');
~~~

## Example ##

The following will create the same form as the [example given for the Postman plugin](http://github.com/castlegateit/cgit-wp-postman).

~~~ php
$card = new Cgit\Postcard('contact');

$card->errorMessage = 'That doesn\'t work';
$card->form->mailerSettings['to'] = get_bloginfo('admin_email');
$card->form->mailerSettings['headers'] = [
    'Reply-To': 'example@example.com'
];

$card->field('username', [
    'type' => 'text',
    'label' => 'Name',
]);

$card->field('email', [
    'type' => 'email',
    'label' => 'Email',
    'required' => true,
    'validate' => [
        'type' => 'email',
    ],
    'error' => 'Please enter a valid email address'
]);

$card->field('submit', [
    'type' => 'button',
    'label' => 'Send Message',
    'exclude' => true,
]);

echo $card->render();
~~~

Alternatively, this form could be returned by any of the following:

~~~ php
echo Cgit\Postcard::get('contact');
echo cgit_postcard('contact');
echo do_shortcode('[postcard id="contact"]');
~~~

## Captcha ##

You can add a captcha to a Postcard form by using the following :

~~~ php
$card->enableCaptcha();
~~~

The order in which you declare this statement with regards to your form fields matters, as the fields, including the captcha, will be rendered in the order they are defined.


For Example:

~~~ php
$card->field('email', [
    'type' => 'email',
    'label' => 'Email',
    'required' => true,
    'validate' => [
        'type' => 'email',
    ],
    'error' => 'Please enter a valid email address'
]);

$card->enableCaptcha();

$card->field('submit', [
    'type' => 'button',
    'label' => 'Send Message',
    'exclude' => true,
]);
~~~


## Filters ##

*   `cgit_postcard_field` rendered HTML of all fields.
*   `cgit_postcard_field_{$type}` rendered HTML of fields of type `$type`.
*   `cgit_postcard_form` rendered HTML of the form.
*   `cgit_postcard_label_suffix_optional` adds a string to the end of the label for optional fields; default empty.
*   `cgit_postcard_label_suffix_required` adds a string to the end of the label for required fields; default `<span class="required">*</span>`.

## Custom field HTML ##

The recommended way of writing a form with custom HTML is to use [Postman](http://github.com/castlegateit/cgit-wp-postman) directly. However, it is possible to customize the HTML output of Postcard by extending the `Field` class and telling your `Postcard` instance to use that instead:

~~~ php
use Cgit\Postcard\Field;

class Foo extends Field
{
    protected function renderText()
    {
        // new text field method
    }
}

$postcard->fieldClass = 'Foo';
~~~

## Changes since version 2.0 ##

*   The `Postcard` class now provides access to the `form` property, which is the `Postman` instance, allowing more detailed control over form settings.

*   The error message properties have been renamed for better compatibility with Postman. The `errorMessage` property now means the same thing in both plugins.

*   The error template is now available via the `errorTemplate` property.

*   The mailer properties have been removed. You can now edit these directly via the `Postman` instance stored in the `form` property.

*   The `Field` class can now be extended or replaced.
