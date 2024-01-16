<?php


class DV_Soon extends DV_Soon_Base {

    private $type_include = 'include';
    private $type_exclude = 'exclude';
    private $current_type = 'include';

    public function __construct($args = null) {
        parent::__construct($args);
        new DV_Soon_Admin($args);
        $o = $this->_o($args);
        $this->current_type  =  $o('type', $this->type_include);
        $this->actionHandler();
    }

    public function actionHandler() {
        if (is_admin()) {
            return;
        }
        $this->option = [
            'product_ids' => get_option('dv_soon_autocomplete_result', ''),
            'type' => get_option('dv_soon_include', ''),
            'message' => get_option('dv_soon_message', '')
        ];

        $fnPrice = function ($price, $product) {
            return $this->replacePrice($price, $product, $this->conditionHandler($product));
        };
        $fnHide = function ($purchasable, $product) {
            return $this->hideCart($purchasable, $product, $this->conditionHandler($product));
        };

        add_action('wp_head', function () {
            global $product;
            if (is_a($product, 'WC_Product')) {
                $condition =   $this->conditionHandler($product);
                if ($condition) {
                    echo "<style>
                    .variations_form,
                  .woocommerce-variation-add-to-cart{display:none!important};
                  <style>";
                }
            }
        });
        add_filter('woocommerce_product_get_price', $fnPrice, 100000, 2);
        add_filter('woocommerce_get_price_html', $fnPrice, 100000, 2);
        add_filter('woocommerce_is_purchasable', $fnHide, 100000, 2);
    }

    public function conditionHandler($product) {
        $o = $this->_o($this->option);
        $product_ids_to_replace = $o('product_ids', []);

        if (empty($product_ids_to_replace)) {
            return false;
        }

        $is_include = $o('type', 'include') === 'include';
        $variation_ids = [];

        if ($product->is_type('variation')) {
            $variation_ids[] = $product->get_id();
        }

        if ($product->is_type('variable')) {
            $variation_ids = $product->get_children();
        }

        $variation_ids = array_merge($product_ids_to_replace,  $variation_ids);
        $is_exist =   in_array($product->get_id(), $variation_ids);


        $condition = !$is_include ? $is_exist : !$is_exist;

        /**
         * False means show message instead of price
         */
        return $condition;
    }

    public function replacePrice($price, $product, $condition) {
        if ($condition) {
            remove_action('woocommerce_after_single_product', 'woocommerce_template_single_add_to_cart', 30);
            return "به‌زودی";
        }
        return $price;
    }

    public function hideCart($purchasable, $product, $condition) {
        if ($condition) {
            return false;
        }

        return $purchasable;
    }
}
