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

	register_post_type( 'customers', $args );

}

add_action( 'init', 'leadgen_customers' );

// CUSTOMER META BOX
function leadgen_customer_info() {

	add_meta_box(
		'leadgen_customer_info_meta',
		'Customer Info',
		'leadgen_customer_info_show',
		'customers',
		'normal',
		'high'
	);

}

add_action( 'init', 'leadgen_customer_info' );

function leadgen_customer_info_show( $customer_info ) {

	$phone		= esc_html( get_post_meta( $customer_info->ID, 'phone', true ) );
	$email		= esc_html( get_post_meta( $customer_info->ID, 'email', true ) );
	$budget		= esc_html( get_post_meta( $customer_info->ID, 'budget', true ) );
	$message	= esc_html( get_post_meta( $customer_info->ID, 'message', true ) ); ?>

	<table>
		
		<tr>
			
			<td>Phone:</td>
			<td><input type="number" size="80" name="leadgen_customer_phone" value="<?php echo $phone; ?>" /></td>
			
		</tr>

		<tr>
			
			<td>Email:</td>
			<td><input type="email" size="80" name="leadgen_customer_email" value="<?php echo $email; ?>" /></td>
			
		</tr>

		<tr>
			
			<td>Desired Budget:</td>
			<td><input type="number" size="80" name="leadgen_customer_budget" value="<?php echo $budget; ?>" /></td>
			
		</tr>

		<tr>
			
			<td>Message:</td>
			<td><input type="text" size="80" name="leadgen_customer_message" value="<?php echo $message; ?>" /></td>
			
		</tr>
		
	</table>

<?php }

function leadgen_customer_info_add( $customer_id, $customer_info ) {

	if ( $customer_info->post_type == 'customers' ) {

		if ( isset( $_POST['leadgen_customer_phone'] ) && $_POST['leadgen_customer_phone'] != '' ) {
			update_post_meta( $customer_id, 'phone', $_POST['leadgen_customer_phone'] );
		}

		if ( isset( $_POST['leadgen_customer_email'] ) && $_POST['leadgen_customer_email'] != '' ) {
			update_post_meta( $customer_id, 'email', $_POST['leadgen_customer_email'] );
		}

		if ( isset( $_POST['leadgen_customer_budget'] ) && $_POST['leadgen_customer_budget'] != '' ) {
			update_post_meta( $customer_id, 'budget', $_POST['leadgen_customer_budget'] );
		}

		if ( isset( $_POST['leadgen_customer_message'] ) && $_POST['leadgen_customer_message'] != '' ) {
			update_post_meta( $customer_id, 'message', $_POST['leadgen_customer_message'] );
		}

	}

}

add_action( 'save_post', 'leadgen_customer_info_add', 10, 2 );

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