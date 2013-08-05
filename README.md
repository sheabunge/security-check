# Security Check

Help combat spam form submissions by forcing the user to answer a simple math sum. In the form of a PHP class that can easily be integrated into your applications.

## Usage

Include the class file in your application and initialize the class. The first argument is the prefix that will be used for form fields.

```php
require_once __DIR__ . '/lib/class-security-check';
$security_check = new Security_Check( 'security_question_' );
```

Call the `Security_Check::show_input_field()` method in your form where you want the math sum to display. You might want to edit the HTML code in the `Security_Check::show_input_field()` method to customize how the form field is displayed.

```php
<form>
	<input type="text" name="your_name">
	<?php $security_check->show_input_field(); ?>
</form>
```

Remember check the value of the `Security_Check::check_validation()` method before acting on the form's contents.

```php
if ( $security_check->check_validation ) {
	/* Security check passed! */
} else {
	/* Security check failed :( */
}
```
