<?php

if (!defined('ABSPATH')) {
    exit;
}

class DV_Soon_Admin extends DV_Soon_Base {
    public $fields = [];
    public function __construct($args = []) {
        parent::__construct($args);
        add_action('admin_menu', [$this, 'my_custom_menu']);

        add_action('wp_ajax_admin_autocomplete_search', [$this, 'admin_autocomplete_search_callback']);

        add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_autocomplete_scripts']);

        add_action('admin_init', [$this, 'save_settings']);
    }

    function save_settings() {
        if (isset($_POST['submit'])) {


            if (isset($_POST['product-search'])) {
                if (empty($_POST['product-search'])) {
                    update_option('dv_soon_autocomplete_result', []);
                } else {
                    update_option('dv_soon_autocomplete_result', $_POST['product-search']);
                }
            } else {
                update_option('dv_soon_autocomplete_result', []);
            }
        }
    }

    function admin_autocomplete_search_callback() {
        check_ajax_referer('admin_autocomplete_nonce', 'nonce');

        $term = sanitize_text_field($_GET['term']);

        $args = array(
            'post_type' => 'product',
            'posts_per_page' => -1,
            's' => $term,
        );

        $query = new WP_Query($args);
        $products = array();

        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $products[] = array(
                    'id' => get_the_ID(),
                    'text' => get_the_title(),
                );
            }
        }

        wp_reset_postdata();

        wp_send_json($products);
    }

    function enqueue_admin_autocomplete_scripts() {
        if (is_admin()) {
            // Enqueue Select2
            wp_enqueue_script('select2', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js', array('jquery'), '4.0.13', true);
            wp_enqueue_style('select2', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css');

            // Enqueue your script
            wp_enqueue_script('admin-autocomplete-script', plugin_dir_url(__FILE__) . 'assets/js/admin-autocomplete.js', array('select2'), null, true);

            // Localize script with nonce and URL parameters
            wp_localize_script('admin-autocomplete-script', 'admin_autocomplete_params', array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce'    => wp_create_nonce('admin_autocomplete_nonce'),
            ));
        }
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
        register_setting('dv_soon_group', 'dv_soon_message', [$this, 'sanitize_callback']);
        add_settings_section('my_radio_section', 'Radio Group Section', [$this, 'my_radio_section_callback'], 'dv-soon');
        add_settings_field('my_radio_field', 'Select an option:', [$this, 'my_radio_field_callback'], 'dv-soon', 'my_radio_section');
        add_settings_section('my_text_section', 'Message Section', [$this, 'my_text_section_callback'], 'dv-soon');
        add_settings_field('dv_soon_message', 'Enter a message:', [$this, 'my_text_field_callback'], 'dv-soon', 'my_text_section');
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

        // print_r($value);
        // exit();

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
