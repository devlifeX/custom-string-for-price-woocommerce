<?php

if (!defined('ABSPATH')) {
    exit;
}

class DV_Soon_Admin extends DV_Soon_Base {
    public $fields = [];
    public function __construct($args = []) {
        parent::__construct($args);
        add_action('admin_menu', [$this, 'my_custom_menu']);
    }

    function my_custom_menu() {
        add_submenu_page(
            'options-general.php',
            'Soon',
            'Soon',
            'manage_options',
            'dv-soon',
            function () {
                echo $this->loadView('admin.php');
            }
        );
        add_action('admin_init', [$this, 'register_my_custom_settings']);
    }

    function register_my_custom_settings() {
        register_setting('dv_soon_group', 'dv_soon_include', [$this, 'sanitize_callback']);
        add_settings_section('my_radio_section', 'Radio Group Section', [$this, 'my_radio_section_callback'], 'dv-soon');
        add_settings_field('my_radio_field', 'Select an option:', [$this, 'my_radio_field_callback'], 'dv-soon', 'my_radio_section');
        add_settings_section('my_text_section', 'Message Section', [$this, 'my_text_section_callback'], 'dv-soon');
        add_settings_field('my_text_field', 'Enter a message:', [$this, 'my_text_field_callback'], 'dv-soon', 'my_text_section');
    }


    function sanitize_callback($input) {
        return sanitize_text_field($input);
    }

    // Callback for the radio group section
    function my_radio_section_callback() {
        echo '<p>Choose either "Include" or "Exclude".</p>';
    }

    // Callback for the radio group field
    function my_radio_field_callback() {
        $value = esc_attr(get_option('dv_soon_include', ''));

        echo '<label><input type="radio" name="dv_soon_include" value="include" ' . checked('include', empty($value) ? 'include' : $value, false) . '> Include</label>';
        echo '<br>';
        echo '<label><input type="radio" name="dv_soon_include" value="exclude" ' . checked('exclude', $value, false) . '> Exclude</label>';
    }

    // Callback for the text field section
    function my_text_section_callback() {
        echo '<p>Enter a custom message.</p>';
    }

    // Callback for the text field
    function my_text_field_callback() {
        $value = esc_attr(get_option('dv_soon_message', ''));
        if (empty($value)) {
            $value = "به‌زودی";
        }
        echo '<input type="text" name="dv_soon_message" value="' . $value . '" placeholder="Enter your message">';
    }
}
