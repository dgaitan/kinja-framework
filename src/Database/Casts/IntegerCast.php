<?php

namespace Kinja\Framework\Database\Casts;

defined( 'ABSPATH' ) || exit;

class IntegerCast extends Cast {

    /**
     * Retrieve the value
     *
     * @return int
     */
    public function get_value() : int {
        return absint( (int) $this->value );
    }

    /**
     * Set Value
     *
     * @param string $value
     * @return integer
     */
    public function set_value() {
        return absint( (int) $this->value );
    }
}