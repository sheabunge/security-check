<?php

/**
 * Help combat spam form submissions by forcing the user to answer a simple math sum.
 * Can easily be integrated into your PHP-powered application
 *
 * @link    https://github.com/bungeshea/security-check
 * @author  Shea Bunge (http://bungeshea.com)
 * @version 1.0
 * @license http://opensource.org/licenses/MIT
 */
class Security_Check {

	/**
	 * The prefix that will be used for form fields
	 *
	 * @var    string
	 *
	 * @since  1.0
	 * @access private
	 */
	private $prefix;

	/**
	 * The message for a security check error
	 *
	 * @var    string
	 *
	 * @since  1.0
	 * @access private
	 */
	private $error;

	/**
	 * The constructor function for the class
	 *
	 * @param  string $prefix The prefix that will be used for form fields
	 *
	 * @since  1.0
	 * @access public
	 */
	public function __construct( $prefix = '' ) {
		$this->prefix = $prefix;
		$this->error  = null;
	}

	/**
	 * Retrieve the value of a form field
	 *
	 * @param  string $field The name of the field, sans prefix
	 * @return mixed              The value of the field
	 *
	 * @since  1.0
	 * @access private
	 */
	public function get_field_value( $field ) {
		return $_POST[ $this->prefix . $field ];
	}

	/**
	 * Return the form field prefix
	 *
	 * @return string The form field prefix
	 *
	 * @since  1.0
	 * @access private
	 */
	public function get_prefix() {
		return $this->prefix;
	}

	/**
	 * Change the form field prefix
	 *
	 * @param  string $prefix The new field prefix
	 * @return void
	 *
	 * @since  1.0
	 * @access private
	 */
	public function set_prefix( $prefix ) {
		$this->prefix = $prefix;
	}

	/**
	 * Calculate the result for a math sum
	 *
	 * @param  integer $a  The first number
	 * @param  integer $b  The second number
	 * @param  integer $op The operation code
	 * @return integer     The sum result
	 *
	 * @since  1.0
	 * @access private
	 */
	private function do_sum( $a, $b, $op ) {

		switch( $op ) {

			/* Addition */
			case 1:
			default:
				return $a + $b;

			/* Subtraction */
			case 2:
				return $a - $b;

			/* Multiplication */
			case 3:
				return $a * $b;

			/* Division */
			case 4:
				return $a / $b;
		}
	}

	/**
	 * Retrieve the HTML entity for an operation
	 *
	 * @param  integer $op The operation code
	 * @return string      A HTML entity
	 *
	 * @since  1.0
	 * @access private
	 */
	private function format_operation( $op ) {

		switch ( $op ) {

			/* Addition */
			case 1:
			default:
				return '&#43;';

			/* Subtraction */
			case 2:
				return '&#8722;';

			/* Multiplication */
			case 3:
				return '&#215;';

			/* Division */
			case 4:
				return '&#247;';
		}
	}

	/**
	 * Retrieve the error message
	 *
	 * @return string The error message
	 *
	 * @since  1.0
	 * @access public
	 */
	public function get_error() {

		if ( null === $this->error )
			$this->check_validation();

		return $this->error;
	}

	/**
	 * Check if the sum validated
	 *
	 * @return boolean Did the sum validate or not?
	 *
	 * @since  1.0
	 * @access public
	 */
	public function check_validation() {

		$number_a  = intval( $this->get_field_value( 'number_a' ) );
		$number_b  = intval( $this->get_field_value( 'number_b' ) );
		$answer    = intval( $this->get_field_value( 'answer' ) );
		$operation = $this->get_field_value( 'operation' );

		if ( $this->do_sum( $number_a, $number_b, $operation ) !== $answer ) {
			/* The submitted answer was incorrect */
			$this->error = 'Sorry, please answer the question again';
			return false;
		}
		elseif ( empty( $answer ) ) {
			/* The answer field wasn't filled in */
			$this->error = 'This is a required field';
			return false;
		}

		return true;
	}

	/**
	 * Output the HTML code for the sum input field
	 *
	 * @return void
	 *
	 * @since  1.0
	 * @access private
	 */
	public function show_input_field() {

		/* Get a random number between 0 and 10 (inclusive) */
		$a = rand( 0, 10 );
		$b = rand( 0, 10 );

		/* Make sure that $a is greater then $b; if not, switch them */
		if ( $b > $a ) {
			$_a = $a;     // backup $a
			$a = $b;      // assign $a (lower number) to $b (higher number)
			$b = $_a;     // assign $b to the original $a
			unset( $_a ); // destroy the backup variable
		}

		/* Get a random operation */
		$op = rand( 1, 2 );

		?>
		<div class="security-check">
			<h4>Security Question</h4>

			<div class="security-check-error"><?php echo $this->get_error(); ?></div>

			<label for="<?php echo $this->prefix; ?>answer">
				<?php printf( '%1$d %3$s %2$d &#61;', $a, $b, $this->format_operation( $op ) ); ?>
			</label>

			<input type="number" name="<?php echo $this->prefix; ?>answer" min="0" max="20" required="required">

			<input type="hidden" name="<?php echo $this->prefix; ?>number_a" value="<?php echo $a; ?>">
			<input type="hidden" name="<?php echo $this->prefix; ?>number_b" value="<?php echo $b; ?>">
			<input type="hidden" name="<?php echo $this->prefix; ?>operation" value="<?php echo $op; ?>">
		</div>
		<?php
	}
}
