<?php
/**
 * Plugin Name:	Lead Gen Form
 * Description:	Collect customer data and build customer profiles inside of your WordPress Dashboard.
 * Version:		1.0
 * Author:		Leighton Sapir
 * Author URI:	http://iamleigh.com/
 * License:		MIT
 */

defined( 'ABSPATH' ) or die( "Now you see me, now you don't... puff!!!" );

// CUSTOMER CPT
function leadgen_customers() {

	$labels		= array(
		'name'					=> 'Customers',
		'singular_name'			=> 'Customer',
		'add_new'				=> 'Add New',
		'add_new_item'			=> 'Add New Customer',
		'edit_item'				=> 'Edit Customer',
		'new_item'				=> 'New Customer',
		'view_item'				=> 'View Customer',
		'view_items'			=> 'View Customers',
		'search_items'			=> 'Search Customers',
		'not_found'				=> 'No customers found',
		'not_found_in_trash'	=> 'No customers found in trash'
	);

	$supports	= array(
		'title'
	);

	$args		= array(
		'labels'			=> $labels,
		'public'			=> true,
		'menu_icon'			=> null,
		'rewrite'			=> array( 'slug' => 'customer' ),
		'capability_type'	=> 'post',
		'has_archive'		=> false,
		'menu_position'		=> '15',
		'supports'			=> $supports,
	);

	register_post_type( 'customer', $args );

}

add_action( 'init', 'leadgen_customers' );

// SHORTCODE
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