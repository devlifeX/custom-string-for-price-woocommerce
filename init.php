<?php

/*
Plugin Name: Custom String instead of price
Plugin URI: https://github.com/devlifeX/custom-string-for-price-woocommerce
Description: Allows you to show a String instead of price, Also support for variable products
Version: 1.2.0
Author: Dariush vesal
Author URI: https://vesal.blog
Text Domain: dv-custom-string
*/

define('DV_SOON_DIR', dirname(__FILE__) . '/');
define('DV_SOON_URL', plugin_dir_url(__FILE__));

require DV_SOON_DIR . "base-class.php";
require DV_SOON_DIR . "soon-class.php";
require DV_SOON_DIR . "admin-class.php";

add_action('plugins_loaded', function () {

    print_r(DV_SOON_DIR . 'languages/');
    exit();
    load_plugin_textdomain('dv-custom-string', false, DV_SOON_DIR . 'languages/');
});

if (class_exists('DV_Soon')) {
    new DV_Soon([
        'type' => 'include', // include -  exclude
        'product_ids' => [],
        'message' => __('Soon', 'dv-custom-string')
    ]);
}
