<?php

namespace Kinja\Framework\Http;

use Tightenco\Collect\Support\Collection;

defined( 'ABSPATH' ) || exit;

abstract class JsonCollection {

    /**
     * Collection
     * 
     * @var Collection
     */
    protected $collection;

    /**
     * JSON resource
     *
     * @var JsonResource|null
     */
    protected $resource = null;

    /**
     * Collection data to convert
     *
     * @param Collection $collection
     */
    public function __construct( Collection $collection, $resource = null ) {
        $this->collection = $collection;
        $this->resource   = $resource;
    }

    /**
     * Convert collection to array
     *
     * @return array
     */
    public function to_array() : array {
        return $this->collection->map( function ( $item ) {
            return ! is_null( $this->resource )
                ? $this->resource::make( $item )->to_array()
                : $item->to_array();
        } )->toArray();
    }
}