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

        <?php
        $ids = get_option('dv_soon_autocomplete_result');
        $product_titles = array();
        if (!empty($ids)) {
            foreach ($ids as $id) {
                $product_titles[$id] = get_the_title($id);
            }
        }
        ?>
        <select id="product-search" name="product-search[]" multiple="multiple" style="width: 100%;">
            <?php
            foreach ($product_titles as $key => $title) {
                echo '<option value="' . esc_attr($key) . '" selected="selected">' . esc_html($title) . '</option>';
            }
            ?>
        </select>
        <?php submit_button(); ?>
    </form>
</div>


<script>
    jQuery(document).ready(function($) {
        $('#product-search').select2({
            ajax: {
                url: admin_autocomplete_params.ajax_url,
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        action: 'admin_autocomplete_search',
                        nonce: admin_autocomplete_params.nonce,
                        term: params.term
                    };
                },
                processResults: function(data) {
                    return {
                        results: data
                    };
                },
                cache: true
            },
            minimumInputLength: 2,
            multiple: true
        });
    });
</script>