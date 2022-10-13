<?php

namespace Kinja\Framework\Support\Helpers;

use Exception;
use Kinja\Framework\Concerns\Singletonable;
use Kinja\Framework\Support\Traits\ForwardsCalls;
use stdClass;

defined( 'ABSPATH' ) || exit;

class Helpers {

    use Singletonable;
    use ForwardsCalls;

    /**
     * List of helper classes
     *
     * @var array
     */
    protected $helpers = [];

    /**
     * Constructor
     */
    public function setup() : void {
        $this->helpers = [
            new ValueHelpers,
        ];
    }

    /**
     * Add a new helper
     *
     * @param stdClass $class
     * @return void
     */
    public function add_helper( stdClass $class ) : void {
        $this->helpers[] = $class;
    }

    /**
     * Add many helpers through an array
     *
     * @param array $classes
     * @return void
     */
    public function add_helpers( array $classes ) : void {
        $this->helpers = array_merge( $this->helpers, $classes );
    }

    /**
     * Dynamically pass method calls to the underlying resource.
     *
     * @param  string  $method
     * @param  array  $parameters
     * @return mixed
     */
    public function __call( $method, $parameters ) {
        $helperClass = null;

        foreach ( $this->helpers as $helper ) {
            if ( method_exists( $helper, $method ) ) {
                $helperClass = $helper;
                break;
            }
        }

        if ( is_null( $helperClass ) ) {
            throw new Exception(
                sprintf( 'Trying to call an %s helper method but is undefined', $method )
            );
        }

        return $this->forwardCallTo($helperClass, $method, $parameters);
    }
}