<?php

namespace Kinja\Framework\Database\Casts;

defined( 'ABSPATH' ) || exit;

class ContentCast extends Cast {

    /**
     * Retrieve the value
     *
     * @return string
     */
    public function get_value() : string {
        return esc_html( $this->value );
    }

    /**
     * Set Value
     *
     * @param string $value
     * @return string
     */
    public function set_value() {
        return sanitize_text_field( $this->value );
    }
}