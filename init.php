<?php

/*
Plugin Name: Soon instead of price
Plugin URI: https://github.com/devlifeX/custom-string-for-price-woocommerce
Description: Allows you to show a word instead of price, Also support for variable products
Version: 1.0.5
Author: Dariush vesal
Author URI: https://vesal.blog
*/


define('DV_SOON_DIR', dirname(__FILE__) . '/');
define('DV_SOON_URL', plugin_dir_url(__FILE__));


require DV_SOON_DIR . "base-class.php";
require DV_SOON_DIR . "soon-class.php";
require DV_SOON_DIR . "admin-class.php";


add_action('init', function () {
    new DV_Soon([
        'type' => 'include', // include -  exclude
        'product_ids' => [],
        'message' => 'به‌زودی'
    ]);
});
