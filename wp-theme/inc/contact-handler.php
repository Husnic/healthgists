<?php
/**
 * Server-side handler for the contact form on page-contact.php — no plugin
 * required. Submits via a normal POST to the same page, verified with a
 * nonce + honeypot, sent with wp_mail(), then redirects back with ?sent=1.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function hg_handle_contact_submission() {
	if ( empty( $_POST['hg_contact_submit'] ) ) {
		return;
	}

	if ( ! isset( $_POST['hg_contact_nonce'] ) || ! wp_verify_nonce( $_POST['hg_contact_nonce'], 'hg_contact_form' ) ) {
		wp_safe_redirect( add_query_arg( 'sent', 'error', wp_get_referer() ) );
		exit;
	}

	// Honeypot — real users never fill this hidden field.
	if ( ! empty( $_POST['hg_website'] ) ) {
		wp_safe_redirect( add_query_arg( 'sent', '1', wp_get_referer() ) );
		exit;
	}

	$name    = isset( $_POST['hg_name'] ) ? sanitize_text_field( wp_unslash( $_POST['hg_name'] ) ) : '';
	$email   = isset( $_POST['hg_email'] ) ? sanitize_email( wp_unslash( $_POST['hg_email'] ) ) : '';
	$message = isset( $_POST['hg_message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['hg_message'] ) ) : '';

	if ( empty( $name ) || empty( $message ) || ! is_email( $email ) ) {
		wp_safe_redirect( add_query_arg( 'sent', 'error', wp_get_referer() ) );
		exit;
	}

	$to      = hg_org_info( 'email' );
	$subject = sprintf( '[Healthgists] Message from %s', $name );
	$body    = "New message from the Healthgists website contact form:\n\n"
		. "Name: {$name}\n"
		. "Email: {$email}\n\n"
		. "Message:\n{$message}\n";
	$headers = array( 'Reply-To: ' . $name . ' <' . $email . '>' );

	$sent = wp_mail( $to, $subject, $body, $headers );

	wp_safe_redirect( add_query_arg( 'sent', $sent ? '1' : 'error', wp_get_referer() ) );
	exit;
}
add_action( 'init', 'hg_handle_contact_submission' );
