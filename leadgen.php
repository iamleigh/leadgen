<?php
/**
 * Plugin Name:	Lead Gen Form
 * Description:	Collect customer data and build customer profiles inside of your WordPress Dashboard.
 * Version:		1.0
 * Author:		Leighton Sapir
 * Author URI:	http://iamleigh.com
 */

defined( 'ABSPATH' ) or die( "Now you see me, now you don't... puff!!!" );

function leadgen_form(){ ?>

	<form>

		<label for="lgf-name">Name</label>
		<input type="text" id="lgf-name" name="name" maxlength="" />

		<label for="lgf-phone">Phone Number</label>
		<input type="number" id="lgf-phone" name="phone" maxlength="" />

		<label for="lgf-email">Phone Number</label>
		<input type="email" id="lgf-email" name="email" maxlength="" />

		<label for="lgf-budget">Desired Budget</label>
		<input type="number" id="lgf-budget" name="budget" maxlength="" rows="" cols="" />

		<label for="lgf-message">Message</label>
		<textarea id="lgf-message" maxlength=""></textarea>

	</form>

<?php }

add_shortcode( "leadgen", "leadgen_form" );