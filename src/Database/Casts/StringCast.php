<?php

namespace Kinja\Framework\Database\Casts;

defined( 'ABSPATH' ) || exit;

class StringCast extends Cast {

    /**
     * Retrieve the value
     *
     * @return string
     */
    public function get_value() : string {
        return esc_html( (string) $this->value );
    }

    /**
     * Set Value
     *
     * @param string $value
     * @return mixed
     */
    public function set_value() {
        return sanitize_text_field( (string) $this->value );
    }
}