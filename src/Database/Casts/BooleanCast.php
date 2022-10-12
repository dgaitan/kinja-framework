<?php

namespace Kinja\Framework\Database\Casts\Support\Casts;

use InvalidArgumentException;

defined( 'ABSPATH' ) || exit;

class BooleanCast extends Cast {

    /**
     * Retrieve the value
     *
     * @return bool
     */
    public function get_value() : bool {
        return 'yes' === $this->value;
    }

    /**
     * Set Value
     *
     * @param string $value
     * @return string
     */
    public function set_value() {
        if ( 'string' === gettype( $this->value ) ) {
            $this->value = 'yes' === $this->value;
        }
        
        if ( 'boolean' !== gettype( $this->value ) ) {
            throw new InvalidArgumentException(
                sprintf(
                    'Invalid value. Field needs to be boolean. %s passed.',
                    $this->value
                )
            );
        }
        
        return $this->value ? 'yes' : 'no';
    }
}