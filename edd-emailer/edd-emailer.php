<?php
/*
Plugin Name: EDD Emergency Emailer
Plugin URI: 
Description: At the moment emails aren't sent by EDD because the purchases aren't getting marked as complete. This hooks into the purchase insert function and forces sending of the receipt email at purchase time. 
Version: 0.1
Author: trog
Author URI: 
License: Public domain
License URI: 
*/

function mayone_edd_on_purchase_insert( $payment_id ) 
{
	// None of this information is needed; just left here for reference in case anyone wants to further customise the email so they don't have to look up the reference. 
	//$payment_meta = edd_get_payment_meta( $payment_id ); // Basic payment meta
	//$cart_items = edd_get_payment_meta_cart_details( $payment_id ); // Cart details

	// Ref: https://easydigitaldownloads.com/docs/edd_email_purchase_receipt/
	edd_email_purchase_receipt($payment_id);
}

// Ref: https://easydigitaldownloads.com/docs/edd_insert_payment/
add_action( 'edd_insert_payment', 'mayone_edd_on_purchase_insert' );