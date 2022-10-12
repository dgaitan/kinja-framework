<?php

namespace Kinja\Framework\Database\Casts;

defined( 'ABSPATH' ) || exit;

class ObjectCast extends Cast {

    /**
     * Retrieve the value
     *
     * @return object
     */
    public function get_value() : object {
        return (object) unserialize( $this->value );
    }

    /**
     * Set Value
     *
     * @param string $value
     * @return string
     */
    public function set_value() {
        return serialize( (array) $this->value );
    }
}