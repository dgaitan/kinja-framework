<?php

namespace Kinja\Framework\Support\Facades;

defined( 'ABSPATH' ) || exit;

/**
 * @method static mixed get( string $key, $default = false )
 * @method static void update( string $key, $value )
 * 
 * @see \Kinja\Framework\Features\Settings\Setting
 */
class Setting extends Facade {
    /**
     * Accesor Class
     *
     * @return string
     */
    protected static function getAccessor() {
        return '\Kinja\Framework\Features\Settings\Setting';
    }
}