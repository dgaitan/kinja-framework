<?php

namespace Kinja\Framework\Database\Casts;

defined( 'ABSPATH' ) || exit;

class FloatCast extends Cast {

    /**
     * Retrieve the value
     *
     * @return int
     */
    public function get_value() : int {
        return (float) $this->value;
    }

    /**
     * Set Value
     *
     * @param string $value
     * @return integer
     */
    public function set_value() {
        return (float) $this->value;
    }
}