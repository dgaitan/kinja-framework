<?php

namespace Kinja\Framework\Support\Facades;

use InvalidArgumentException;
use Kinja\Framework\Exceptions\NotImplementedException;

defined( 'ABSPATH' ) || exit;

abstract class Facade {

    /**
     * Accesor Class
     *
     * @return string
     */
    protected static function getAccessor() {
        throw new NotImplementedException( 'Implement getAccessor into your class' );
    }

    /**
     * Call to method statically
     *
     * @param string $method
     * @param mixed $arguments
     * @return mixed
     */
    public static function __callStatic( $method, $arguments ) {
        if ( ! method_exists( static::getAccessor(), 'load' ) ) {
            throw new InvalidArgumentException( 'Class needs implement load method' );
        }
        
        $class = static::getAccessor()::load();

        return $class->$method(...$arguments);
    }
}