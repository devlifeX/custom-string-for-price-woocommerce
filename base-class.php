<?php

if (!defined('ABSPATH')) {
    exit;
}

class DV_Soon_Base {
    protected $option = [];
    protected $o = [];

    function __construct($args = []) {
        $this->option = $args;
    }

    public function head($obj) {
        try {
            if (is_array($obj)) {
                return $obj[0];
            }

            if (is_object($obj)) {
                return $obj->{0};
            }
            return null;
        } catch (\Throwable $th) {
            return null;
        }
    }

    function _o($arr) {
        return function ($selector, $default) use ($arr) {
            $ex = explode(".", $selector);
            $re = array_reduce($ex, function ($acc, $i) {
                try {
                    $val = json_encode($acc);
                    $acc = json_decode($val, true);
                    $acc = $acc[$i];
                } catch (\Throwable $th) {
                    $acc = null;
                }
                return $acc;
            }, $arr);
            if ($re == null) {
                return $default;
            } else {
                return $re;
            }
        };
    }

    public function every($items) {
        return function ($fn) use ($items) {
            foreach ($items as $key => $item) {
                if (!$fn($item, $key)) {
                    return false;
                }
            }
            return true;
        };
    }

    public function o($selector, $default) {
        return $this->_o($this->option)($selector, $default);
    }

    public function loadView($viewFile, $argsView = []) {
        $path = DV_SOON_DIR . "view/" . $viewFile;
        if (!file_exists($path)) {
            return "view not found!";
        }

        ob_start();
        include $path;
        return ob_get_clean();
    }
}
