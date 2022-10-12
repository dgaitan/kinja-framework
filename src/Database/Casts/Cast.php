<?php

namespace Kinja\Framework\Database\Casts;

defined( 'ABSPATH' ) || exit;

class Cast {

    /**
     * Cast Value
     *
     * @var string
     */
    protected $value;

    /**
     * Initialize Cast
     *
     * @param mixed $value
     */
    public function __construct( $value ) {
        $this->value = $value;
    }

    /**
     * Retrieve the value
     *
     * @return mixed
     */
    public function get_value() {
        return $this->value;
    }

    /**
     * Set Value
     *     
     * @return mixed
     */
    public function set_value() {
        return $this->value;
    }

    /**
     * Get Value
     *
     * @return mixed
     */
    public static function make( $value ) {
        $cast = new static( $value );

        return $cast;
    }
}