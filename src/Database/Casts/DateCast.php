<?php

namespace Kinja\Framework\Database\Casts;

use Carbon\Carbon;

defined( 'ABSPATH' ) || exit;

class DateCast extends Cast {

    /**
     * Retrieve the value
     *
     * @return Carbon
     */
    public function get_value() {
        return Carbon::parse( $this->value );
    }

    /**
     * Set Value
     *
     * @param string $value
     * @return string
     */
    public function set_value() {
        if ( $this->value instanceof Carbon ) {
            return $this->value->format( KINJA_FRAMEWORK_DATE_FORMAT );
        }
        
        return Carbon::parse( $this->value )->format( KINJA_FRAMEWORK_DATE_FORMAT );
    }
}