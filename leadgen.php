<?php
/**
 * Plugin Name:	Lead Gen Form
 * Description:	Collect customer data and build customer profiles inside of your WordPress Dashboard.
 * Version:		1.0
 * Author:		Leighton Sapir
 * Author URI:	http://iamleigh.com
 */

defined( 'ABSPATH' ) or die( "Now you see me, now you don't... puff!!!" );

function leadgen_form( $atts ) {

	extract( shortcode_atts( array(
		'styles'	=> true,
		'name'		=> 'Name',
		'phone'		=> 'Phone',
		'email'		=> 'Email',
		'budget'	=> 'Desired Budget',
		'message'	=> 'Message',
		'submit'	=> 'Submit'
	), $atts ) );

	if ( $styles === true ) {
		$class = ' class="lgf-ui"';
	} else {
		$class = '';
	}

	return '<form' . $class . '>

		<label for="lgf-name">' . $name . '</label>
		<input type="text" id="lgf-name" name="name" maxlength="" />

		<label for="lgf-phone">' . $phone . '</label>
		<input type="number" id="lgf-phone" name="phone" maxlength="" />

		<label for="lgf-email">' . $email . '</label>
		<input type="email" id="lgf-email" name="email" maxlength="" />

		<label for="lgf-budget">' . $budget . '</label>
		<input type="number" id="lgf-budget" name="budget" maxlength="" rows="" cols="" />

		<label for="lgf-message">' . $message . '</label>
		<textarea id="lgf-message" maxlength=""></textarea>

		<button>' . $submit . '</button>

	</form>';

}

add_shortcode( "leadgen", "leadgen_form" );