<?php
/*
Plugin Name: New Checkout Fields
Plugin URI: 
Description: Derived from instructions at https://easydigitaldownloads.com/docs/custom-checkout-fields/ with minor modifications
Version: 0.1
Author: trog
Author URI: 
License: Public domain
License URI: 
*/

// output our custom field HTML
function mayone_edd_custom_checkout_fields() {
  ?>
	<p id="edd-occupation-wrap">
		<label class="edd-label" for="edd-occupation"><?php _e('Occupation', 'mayone_edd'); ?></label>
		<span class="edd-description"><?php _e( 'Enter your occupation.', 'mayone_edd' ); ?></span>
		<input class="edd-input" type="text" name="edd_occupation" id="edd-occupation" placeholder="<?php _e('Occupation', 'mayone_edd'); ?>" value=""/>
	</p>
	<p id="edd-employer-wrap">
		<label class="edd-label" for="edd-employer"><?php _e('Employer Name', 'mayone_edd'); ?></label>
		<span class="edd-description"><?php _e( 'Enter the name of your employer.', 'mayone_edd' ); ?></span>
		<input class="edd-input" type="text" name="edd_employer" id="edd-employer" placeholder="<?php _e('Employer Name', 'mayone_edd'); ?>" value=""/>
	</p>
	<?php

	// Unhook this action so it doesn't fire twice if the registration option is turned on
	// (ref:  https://easydigitaldownloads.com/support/topic/edd_purchase_form_user_info-seems-to-be-running-twice-on-checkout-page/)
	remove_action('edd_purchase_form_user_info', 'mayone_edd_custom_checkout_fields');
}
add_action('edd_purchase_form_user_info', 'mayone_edd_custom_checkout_fields');

// check for errors with out custom fields
function mayone_edd_validate_custom_fields($valid_data, $data) {
	if( empty( $data['edd_occupation'] ) ) {
		// check for a occupation number
		edd_set_error( 'invalid_occupation', __('Please provide your occupation.', 'mayone_edd') );
	}
	if( empty( $data['edd_employer'] ) ) {
		// check for an employer
		edd_set_error( 'invalid_employer', __('Please provide an employer.', 'mayone_edd') );
	}
}
add_action('edd_checkout_error_checks', 'mayone_edd_validate_custom_fields', 10, 2);

// store the custom field data in the payment meta
function mayone_edd_store_custom_fields($payment_meta) {
	$payment_meta['occupation']   = isset( $_POST['edd_occupation'] ) ? sanitize_text_field( $_POST['edd_occupation'] ) : '';
	$payment_meta['employer'] = isset( $_POST['edd_employer'] ) ? sanitize_text_field( $_POST['edd_employer'] ) : '';
	return $payment_meta;
}
add_filter('edd_payment_meta', 'mayone_edd_store_custom_fields');

// show the custom fields in the "View Order Details" popup
function mayone_edd_purchase_details($payment_meta, $user_info) {
	$occupation   = isset( $payment_meta['occupation'] ) ? $payment_meta['occupation'] : 'none';
	$employer = isset( $payment_meta['employer'] ) ? $payment_meta['employer'] : 'none';
	?>
	<li><?php echo __('Occupation:', 'mayone_edd') . ' ' . $occupation; ?></li>
	<li><?php echo __('Employer:', 'mayone_edd') . ' ' . $employer; ?></li>

	<?php
}
add_action('edd_payment_personal_details_list', 'mayone_edd_purchase_details', 10, 2);
