<?php

namespace Kinja\Framework\Support\Facades;

defined( 'ABSPATH' ) || exit;

/**
 * @method static mixed get_value( mixed $must_be, mixed $default )
 * 
 * @see \Kinja\Framework\Support\Helpers\Helpers
 */
class Helpers extends Facade {
    /**
     * Accesor Class
     *
     * @return string
     */
    protected static function getAccessor() {
        return '\Kinja\Framework\Support\Helpers\Helpers';
    }
}