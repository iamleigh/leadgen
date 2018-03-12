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
		'title',
		'editor'

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
		
	</table>

<?php }

function leadgen_customer_info_add( $customer_id, $customer_info ) {

	if ( $customer_info->post_type === 'customers' ) {

		if ( isset( $_POST['leadgen_customer_phone'] ) && $_POST['leadgen_customer_phone'] != '' ) {
			update_post_meta( $customer_id, 'phone', $_POST['leadgen_customer_phone'] );
		}

		if ( isset( $_POST['leadgen_customer_email'] ) && $_POST['leadgen_customer_email'] != '' ) {
			update_post_meta( $customer_id, 'email', $_POST['leadgen_customer_email'] );
		}

		if ( isset( $_POST['leadgen_customer_budget'] ) && $_POST['leadgen_customer_budget'] != '' ) {
			update_post_meta( $customer_id, 'budget', $_POST['leadgen_customer_budget'] );
		}

	}

}

add_action( 'save_post', 'leadgen_customer_info_add', 10, 2 );

// SHORTCODE
function leadgen_form( $atts ) {

	$errors = array();

	extract( shortcode_atts( array(
		'styles'		=> true,
		'title'			=> 'Contact Us',
		'name'			=> 'Name',
		'name_max'		=> '20',
		'phone'			=> 'Phone',
		'phone_max'		=> '10',
		'email'			=> 'Email',
		'email_max'		=> '',
		'budget'		=> 'Desired Budget',
		'budget_max'	=> '4',
		'message'		=> 'Message',
		'submit'		=> 'Submit'
	), $atts ) );

	if ( 'POST' === $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action'] ) &&  $_POST['action'] === "leadgen_new_customer" ) {

		if ( ! isset( $_POST['leadgen_nonce'] ) || ! wp_verify_nonce( $_POST['leadgen_nonce'], 'leadgen-new-client' ) ) {
			
			print 'Sorry, your nonce did not verify.';
			
			exit;
		
		} else {
			
			$customer_name = $customer_phone = $customer_email = $customer_budget = $customer_message = '';

			if (
				( empty( $_POST['title'] ) && $_POST['title'] === '' ) ||
				( empty( $_POST['leadgen_customer_phone'] ) && $_POST['leadgen_customer_phone'] === '' ) ||
				( empty( $_POST['leadgen_customer_email'] ) && $_POST['leadgen_customer_email'] === '' ) ||
				( empty( $_POST['leadgen_customer_budget'] ) && $_POST['leadgen_customer_budget'] === '' )
			) {
				$errors['form'] = 'Something went wrong with your form. Please, verify and try again.';
				$errors['class'] = 'leadgen-has_error';
			}
			
			if ( isset( $_POST['title'] ) && $_POST['title'] !== '' ) {
				$customer_name =  $_POST['title'];
			} else {
				$errors['title'] = '"' . $name . '" is required';
			}

			if ( isset( $_POST['leadgen_customer_phone'] ) && $_POST['leadgen_customer_phone'] !== '' ) {
				$customer_phone = $_POST['leadgen_customer_phone'];
			} else {
				$errors['leadgen_customer_phone'] = '"' . $phone . '" is required';
			}

			if ( isset( $_POST['leadgen_customer_email'] ) && $_POST['leadgen_customer_email'] !== '' ) {
				$customer_email = $_POST['leadgen_customer_email'];
			} else {
				$errors['leadgen_customer_email'] = '"' . $email . '" is required';
			}

			if ( isset( $_POST['leadgen_customer_budget'] ) && $_POST['leadgen_customer_budget'] !== '' ) {
				$customer_budget = $_POST['leadgen_customer_budget'];
			} else {
				$errors['leadgen_customer_budget'] = '"' . $budget . '" is required';
			}
			
			if ( isset( $_POST['description'] ) ) {
				$customer_message = $_POST['description'];
			}

			if ( !empty( $customer_name ) && !empty( $customer_phone ) && !empty( $customer_email ) && !empty( $customer_budget ) ) {

				// Add the content of the form to $post as an array
				$new_client = array(
					'post_title'				=> $customer_name,
					'post_content'				=> $customer_message,
					'leadgen_customer_phone'	=> $customer_phone,
					'leadgen_customer_email'	=> $customer_email,
					'leadgen_customer_budget'	=> $customer_budget,
					'post_status'				=> 'publish',
					'post_type'					=> 'customers'
				);

				// Save the new post
				$pid = wp_insert_post( $new_client );
			}
		
		}
		
	}

	if ( $styles === true ) {
		$class = ' class="leadgen-ui leadgen-form"';
	} else {
		$class = '';
	} ?>

	<div id="leadgen-form">
		
		<form id="leadgen_new_customer"<?php echo $class; ?> name="leadgen_new_customer" method="post" action="">

			<?php if (
				( isset( $_POST['title'] ) && !empty( $_POST['title'] ) ) ||
				( isset( $_POST['leadgen_customer_phone'] ) && !empty( $_POST['leadgen_customer_phone'] ) ) ||
				( isset( $_POST['leadgen_customer_email'] ) && !empty( ['leadgen_customer_email'] ) ) ||
				( isset( $_POST['leadgen_customer_budget'] ) && !empty( ['leadgen_customer_budget'] ) )
			) { ?>
				
				<label class="leadgen-label--info"><?php echo $errors['form']; ?></label>

			<?php } ?>

			<h1 class="leadgen-title"><?php echo $title; ?></h1>

			<div class="leadgen-field">
				
				<label for="lgf-title" class="leadgen-label"><?php echo $name; ?></label>

				<input type="text" id="lgf-title" class="leadgen-input<?php if ( isset( $errors['title'] ) && !empty( $errors['title'] ) ) { echo ' ' . $errors['class']; } ?>" value="" tabindex="1" name="title" maxlength="<?php echo $name_max; ?>" />

				<?php if ( isset( $errors['title'] ) && !empty( $errors['title'] ) ) { ?>
					<label id="lgf-title" class="leadgen-label--error"><?php echo $errors['title']; ?></label>
				<?php } ?>

			</div>

			<div class="leadgen-field">
				
				<label for="lgf-phone" class="leadgen-label"><?php echo $phone; ?></label>
				
				<input type="tel" id="lgf-phone" class="leadgen-input<?php if ( isset( $errors['leadgen_customer_phone'] ) && !empty( $errors['leadgen_customer_phone'] ) ) { echo ' ' . $errors['class']; } ?>" value="" tabindex="1" name="leadgen_customer_phone" maxlength="<?php echo $phone_max; ?>" />
				
				<?php if ( isset( $errors['leadgen_customer_phone'] ) && !empty( $errors['leadgen_customer_phone'] ) ) { ?>
					<label id="lgf-phone" class="leadgen-label--error"><?php echo $errors['leadgen_customer_phone']; ?></label>
				<?php } ?>

			</div>

			<div class="leadgen-field">
				
				<label for="lgf-email" class="leadgen-label"><?php echo $email; ?></label>
				
				<input type="email" id="lgf-email" class="leadgen-input<?php if ( isset( $errors['leadgen_customer_email'] ) && !empty( $errors['leadgen_customer_email'] ) ) { echo ' ' . $errors['class']; } ?>" value="" tabindex="1" name="leadgen_customer_email" maxlength="<?php echo $email_max; ?>" />
				
				<?php if ( isset( $errors['leadgen_customer_email'] ) && !empty( $errors['leadgen_customer_email'] ) ) { ?>
					<label id="lgf-email" class="leadgen-label--error"><?php echo $errors['leadgen_customer_email']; ?></label>
				<?php } ?>

			</div>

			<div class="leadgen-field">
				
				<label for="lgf-budget" class="leadgen-label"><?php echo $budget; ?></label>
				
				<input type="number" id="lgf-budget" class="leadgen-input<?php if ( isset( $errors['leadgen_customer_budget'] ) && !empty( $errors['leadgen_customer_budget'] ) ) { echo ' ' . $errors['class']; } ?>" value="" tabindex="1" name="leadgen_customer_budget" maxlength="<?php echo $budget_max; ?>" />
				
				<?php if ( isset( $errors['leadgen_customer_budget'] ) && !empty( $errors['leadgen_customer_budget'] ) ) { ?>
					<label id="lgf-budget" class="leadgen-label--error"><?php echo $errors['leadgen_customer_budget']; ?></label>
				<?php } ?>
				
			</div>
			
			<div class="leadgen-field">
				
				<label for="lgf-description" class="leadgen-label"><?php echo $message; ?></label>
				
				<textarea id="lgf-description" class="leadgen-textarea" tabindex="3" name="description" cols="50" rows="6"></textarea>

			</div>
			
			<div class="leadgen-field--button">
				
				<button id="submit" class="leadgen-button" name="submit"><?php echo $submit; ?></button>
				
			</div>
			
			<input type="hidden" name="action" value="leadgen_new_customer" />
			<?php wp_nonce_field( 'leadgen-new-client', 'leadgen_nonce' ); ?>

		</form>
		
	</div>

<?php }

add_shortcode( "leadgen", "leadgen_form" );

function leadgen_load_styles() {
	
	$plugin_url = plugin_dir_url( __FILE__ );
	
	wp_enqueue_style( 'style1', $plugin_url . 'assets/css/leadgen.css' );
	
}

add_action( 'wp_enqueue_scripts', 'leadgen_load_styles' );