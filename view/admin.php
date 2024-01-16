<?php
// Function to register settings and add fields
function register_my_custom_settings() {
    // Register a setting and its sanitization callback

}

// Sanitization callback for the radio group
function sanitize_callback($input) {
    return sanitize_text_field($input);
}

// Callback for the radio group section
function my_radio_section_callback() {
    echo '<p>Choose either "Include" or "Exclude".</p>';
}

// Callback for the radio group field
function my_radio_field_callback() {
    $value = esc_attr(get_option('my_custom_option', ''));

    echo '<label><input type="radio" name="my_custom_option" value="include" ' . checked('include', $value, false) . '> Include</label>';
    echo '<br>';
    echo '<label><input type="radio" name="my_custom_option" value="exclude" ' . checked('exclude', $value, false) . '> Exclude</label>';
}

// Callback for the text field section
function my_text_section_callback() {
    echo '<p>Enter a custom message.</p>';
}

// Callback for the text field
function my_text_field_callback() {
    $value = esc_attr(get_option('my_custom_text_option', ''));
    echo '<input type="text" name="my_custom_text_option" value="' . $value . '" placeholder="Enter your message">';
}

?>
<div class="wrap">
    <h2>Custom String for tag price - Settings</h2>
    <form method="post" action="options.php">
        <?php settings_fields('dv_soon_group'); ?>
        <?php do_settings_sections('dv-soon'); ?>
        <?php submit_button(); ?>
    </form>
</div>