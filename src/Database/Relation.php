<?php

namespace LC\DoorStep\Support\Database;

use LC\DoorStep\Support\Traits\ForwardsCalls;

defined( 'ABSPATH' ) || exit;

abstract class Relation {

    use ForwardsCalls;

    public function __construct() {

    }

    /**
     * Handle dynamic method calls to the relationship.
     *
     * @param  string  $method
     * @param  array  $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        return $this->forwardDecoratedCallTo($this->query, $method, $parameters);
    }
}