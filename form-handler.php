<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

function scf_handle_form_submission() {
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['scf_nonce_field']) && wp_verify_nonce($_POST['scf_nonce_field'], 'scf_form_nonce')) {
        // Sanitize and validate form inputs
        $name = sanitize_text_field($_POST['scf_name']);
        $email = sanitize_email($_POST['scf_email']);
        $message = sanitize_textarea_field($_POST['scf_message']);

        // Check if required fields are not empty
        if (!empty($name) && !empty($email) && !empty($message)) {
            // Get admin email from settings
            $admin_email = get_option('scf_admin_email', get_option('admin_email'));

            // Send email to the admin
            wp_mail($admin_email, 'New Contact Form Submission', "Name: $name\nEmail: $email\nMessage: $message");

            // Redirect to thank you page or show a success message
            echo '<div class="alert alert-success">Thank you for your message!</div>';
        } else {
            echo '<div class="alert alert-danger">All fields are required.</div>';
        }
    }
}
