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
		$class = ' class="leadgen-ui"';
	} else {
		$class = '';
	} ?>

	<div id="leadgen-form">
		
		<form id="new_post"<?php echo $class; ?> name="new_post" method="post" action="">

			<p><label for="lgf-title"><?php echo $name; ?></label>
			<input type="text" id="lgf-title" value="" tabindex="1" size="20" name="title" required />
			</p>

			<p><label for="lgf-phone"><?php echo $phone; ?></label>
			<input type="tel" id="lgf-phone" value="" tabindex="1" size="20" name="leadgen_customer_phone" />
			</p>

			<p><label for="lgf-email"><?php echo $email; ?></label>
			<input type="email" id="lgf-email" value="" tabindex="1" size="20" name="leadgen_customer_email" required />
			</p>

			<p><label for="lgf-budget"><?php echo $budget; ?></label>
			<input type="number" id="lgf-budget" value="" tabindex="1" size="20" name="leadgen_customer_budget" />
			</p>
			
			<p><label for="lgf-description"><?php echo $message; ?></label>
			<textarea id="lgf-description" tabindex="3" name="description" cols="50" rows="6"></textarea>
			</p>
			
			<p><input type="submit" value="<?php echo $submit; ?>" tabindex="6" id="submit" name="submit" /></p>
			
			<input type="hidden" name="action" value="new_post" />
			<?php wp_nonce_field( 'new-post' ); ?>

		</form>
		
	</div>

	<?php
	if ( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action'] ) &&  $_POST['action'] == "new_post" ) {
		
		if ( isset( $_POST['title'] ) ) {
			$title =  $_POST['title'];
		} else {
			echo '<p>Please, enter customer name.</p>';
		}

		if ( isset( $_POST['leadgen_customer_phone'] ) && $_POST['leadgen_customer_phone'] != '' ) {
			$phone = $_POST['leadgen_customer_phone'];
		} else {
			echo '<p>Please, enter a valid number.</p>';
		}

		if ( isset( $_POST['leadgen_customer_email'] ) && $_POST['leadgen_customer_email'] != '' ) {
			$email = $_POST['leadgen_customer_email'];
		} else {
			echo '<p>Please, enter a valid email.</p>';
		}

		if ( isset( $_POST['leadgen_customer_budget'] ) && $_POST['leadgen_customer_budget'] != '' ) {
			$budget = $_POST['leadgen_customer_budget'];
		} else {
			echo '<p>Please, enter a valid budget.</p>';
		}
		
		if ( isset( $_POST['description'] ) ) {
			$description = $_POST['description'];
		}
		
		// Add the content of the form to $post as an array
		$new_post = array(
			'post_title'				=> $title,
			'post_content'				=> $description,
			'leadgen_customer_phone'	=> $phone,
			'leadgen_customer_email'	=> $email,
			'leadgen_customer_budget'	=> $budget,
			'post_status'				=> 'publish',
			'post_type'					=> 'customers'
		);
		
		// Save the new post
		$pid = wp_insert_post( $new_post );
		
	}

}

add_shortcode( "leadgen", "leadgen_form" );

function leadgen_load_styles() {
	
	$plugin_url = plugin_dir_url( __FILE__ );
	
	wp_enqueue_style( 'style1', $plugin_url . 'assets/css/leadgen.css' );
	
}

add_action( 'wp_enqueue_scripts', 'leadgen_load_styles' );