<div class="wrap">
    <h2><?php _e('Custom String instead of price', 'dv-custom-string') ?></h2>
    <form method="post" action="options.php" id="dv-soon">
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

        <h2><?php _e('Products', 'dv-custom-string') ?></h2>
        <p><?php _e('Choose products to which you want to apply changes.', 'dv-custom-string') ?></p>
        <select id="product-search" name="product-search[]" multiple="multiple" style="width: 100%;">
            <?php
            foreach ($product_titles as $key => $title) {
                echo '<option value="' . esc_attr($key) . '" selected="selected">' . esc_html($title) . '</option>';
            }
            ?>
        </select>
        <input type="hidden" name="products" id="products">

        <?php submit_button(null, 'primary', 'dv_soon_form_submit'); ?>
    </form>
</div>