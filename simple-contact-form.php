<?php
/*
Plugin Name: Contact Form
Description: A simple contact form plugin with shortcode.
Version: 1.0
Author: Md Abdullah Hannan
*/

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Include form handling logic
include(plugin_dir_path(__FILE__) . 'form-handler.php');

// Add admin menu
function scf_add_admin_menu() {
    add_menu_page(
        'Simple Contact Form Settings',
        'Contact Form',
        'manage_options',
        'simple-contact-form',
        'scf_admin_page',
        'dashicons-email-alt2',
        100
    );
}
add_action('admin_menu', 'scf_add_admin_menu');

// Admin page content
function scf_admin_page() {
    ?>
    <div class="wrap">
        <h1>Simple Contact Form Settings</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('scf_settings_group');
            do_settings_sections('simple-contact-form');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

// Register and define settings
function scf_settings_init() {
    register_setting('scf_settings_group', 'scf_admin_email');

    add_settings_section(
        'scf_settings_section',
        'Contact Form Settings',
        'scf_settings_section_callback',
        'simple-contact-form'
    );

    add_settings_field(
        'scf_admin_email',
        'Admin Email',
        'scf_admin_email_callback',
        'simple-contact-form',
        'scf_settings_section'
    );
}
add_action('admin_init', 'scf_settings_init');

function scf_settings_section_callback() {
    echo 'Enter your settings below:';
}

function scf_admin_email_callback() {
    $email = get_option('scf_admin_email');
    echo '<input type="email" name="scf_admin_email" value="' . esc_attr($email) . '" />';
}

// Enqueue Bootstrap CSS and JS
function scf_enqueue_scripts() {
    wp_enqueue_style('bootstrap-css', 'https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css');
    wp_enqueue_script('bootstrap-js', 'https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js', array('jquery'), null, true);
}
add_action('wp_enqueue_scripts', 'scf_enqueue_scripts');

// Shortcode to display the form
function scf_display_form() {
    ob_start();
    ?>
    <form method="post" action="<?php echo esc_url($_SERVER['REQUEST_URI']); ?>" class="container mt-5">
        <div class="form-group">
            <label for="scf_name">Name</label>
            <input type="text" name="scf_name" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="scf_email">Email</label>
            <input type="email" name="scf_email" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="scf_message">Message</label>
            <textarea name="scf_message" class="form-control" rows="5" required></textarea>
        </div>
        <?php wp_nonce_field('scf_form_nonce', 'scf_nonce_field'); ?>
        <div class="form-group">
            <button type="submit" name="scf_submit" class="btn btn-primary">Send</button>
        </div>
    </form>
    <?php
    scf_handle_form_submission();
    return ob_get_clean();
}
add_shortcode('simple_contact_form', 'scf_display_form');
