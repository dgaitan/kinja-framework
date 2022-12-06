<?php

namespace Kinja\Framework\Http;

use Kinja\Framework\Support\Traits\ForwardsCalls;

defined( 'ABSPATH' ) || exit;

abstract class JsonResource {

    use ForwardsCalls;

    /**
     * Instance to serialize
     *
     * @var mixed
     */
    protected $resource;

    /**
     * Initialize the JSON Resource instance
     *
     * @param mixed $instance
     */
    public function __construct( $resource ) {
        $this->resource = $resource;
    }

    /**
     * Iinitalize
     *
     * @param mixed $resource
     * @return self
     */
    public static function make( $resource ) : self {
        return new static( $resource );
    }

    /**
     * Serialize
     *
     * @return array
     */
    public function to_array() : array {
        return method_exists( $this->resource, 'to_array' )
            ? $this->resource->to_array()
            : array();
    }

    /**
     * Retrieve json resource values
     *
     * @return mixed
     */
    public function __call( $method, $parameters ) {
        return $this->forwardCallTo( $this->resource, $method, $parameters );
    }

    /**
     * Retrieve resource values
     *
     * @param mixed $key
     * @return mixed
     */
    public function __get( $key ) {
        return $this->resource->{$key};
    }
}