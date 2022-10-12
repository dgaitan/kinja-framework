<?php

namespace Kinja\Framework\Database\Casts;

use InvalidArgumentException;

defined( 'ABSPATH' ) || exit;

class EmailCast extends Cast {

    /**
     * Retrieve the value
     *
     * @return string
     */
    public function get_value() : string {
        return (string ) $this->value;
    }

    /**
     * Set Value
     *
     * @param string $value
     * @return string
     */
    public function set_value() {
        if ( ! filter_var( $this->value, FILTER_VALIDATE_EMAIL ) ) {
            throw new InvalidArgumentException(
                sprintf(
                    'Invalid value. Field needs to be a valid email. %s passed.',
                    $this->value
                )
            );
        }
        
        return sanitize_email( $this->value );
    }
}