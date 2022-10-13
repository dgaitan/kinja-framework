<?php

namespace Kinja\Framework\Support\Helpers;

defined( 'ABSPATH' ) || exit;

class ValueHelpers {

    /**
     * Retrieve a value
     *
     * @param mixed $must_be
     * @param mixed $default
     * @return mixed
     */
    public function get_value( $must_be, $default ) {
        return $must_be ? $must_be : $default;
    }
}