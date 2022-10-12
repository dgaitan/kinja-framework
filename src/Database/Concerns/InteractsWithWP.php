<?php

namespace Kinja\Framework\Database\Concerns;

use Kinja\Framework\Database\Paginator;
use Tightenco\Collect\Support\Collection;

defined( 'ABSPATH' ) || exit;

trait InteractsWithWP {
    /**
     * Save the instance
     *
     * @return self
     */
    public function save() {
        $this->perform_save();

        return $this;
    }

    /**
     * Update the instance
     *
     * @param array $attributes
     * @return self
     */
    public function update( array $attributes = array() ) {
        if ( $attributes ) {
            $this->set_props( $attributes );
        }

        $this->perform_save();

        return $this;
    }

    /**
     * Create a new element
     *
     * @param array $attributes
     * @return self
     */
    public static function create( array $attributes = array() ) {
        $model = new static( $attributes );
        $model->save();

        $model->after_create();
        
        return $model->refresh();
    }

    /**
     * Execute a WP_Query
     *
     * @param array $args
     * @return Collection
     */
    public static function where( array $args = array() ) {
        $instance   = new static;
        $query_args = $instance->parse_arguments( $args );
        $posts      = get_posts( $query_args );

        // We're going to convert the posts to a collection
        $posts = Collection::make( $posts )->map( function ( $post ) {
            $post = new static( (array) $post );
            $post->refresh();

            return $post;
        } );
        
        return $posts;
    }

    /**
     * It basically runs a WP_Query to be able to retrieve pagination stuff
     *
     * @param array $args
     * @return Paginator
     */
    public static function paginate( array $args = array() ) : Paginator {
        return new Paginator( static::class, $args );
    }

    /**
     * Retrieve all records
     *
     * @return Collection
     */
    public static function all() {  
        return self::where( array( 'posts_per_page' => 50 ) );
    }

    /**
     * Find a model
     *
     * @param [type] $object_id
     * @return self
     */
    public static function find( $object_id ) {
        $model = new static( array( 'ID' => $object_id ) );
        $model->refresh();
        
        return $model;
    }

    /**
     * Parse arguments and convert it in a classic WP_Query args params
     *
     * @param array $args
     * @return array
     */
    public function parse_arguments( array $args = array() ) : array {
        $query_args = wp_parse_args( $args, array(
            'posts_per_page' => 20
        ) );

        $meta_query = array();
        foreach ( array_keys( $args ) as $argument ) {
            if ( $this->is_prop( $argument ) ) {
                $meta_query[] = array(
                    'key'     => $argument,
                    'value'   => $this->value_to_store(
                        $argument,
                        $query_args[ $argument ] 
                    ),
                    'compare' => '='
                );
            }
        }

        if ( $meta_query ) {
            $meta_query['relation']   = 'AND';
            $query_args['meta_query'] = $meta_query;
        }

        $query_args['post_type'] = $this->get_post_type_slug();

        return $query_args;
    }

    /**
     * Peform saving
     *
     * @return integer
     */
    protected function perform_save() : int {
        $args = array(
            'post_type' => $this->get_post_type_slug()
        );

        $this->before_save();

        // This will throw an error if fails
        $this->validate_props();

        foreach ( $this->wp_fields as $key => $value ) {
            $args[ $key ] = $this->value_to_store( $key, $value );
        }
        
        if ( $this->data ) {
            $meta_fields = array();

            foreach ( $this->data as $key => $value ) {
                $meta_fields[ $key ] = $this->value_to_store( $key, $value );
            }

            $args['meta_input'] = $meta_fields;
        }

        $post_id = wp_insert_post( $args );

        // Set the post id if it's zero.
        // It means that the action is creating.
        if ( $args['ID'] === 0 ) {
            $this->set_prop( 'ID', $post_id );
        }

        return $post_id;
    }

    /**
     * Fires actions before save the object
     *
     * @return void
     */
    protected function before_save() : void {

    }

    /**
     * Fires actions after create the object
     */
    protected function after_create() : void {

    }

    /**
     * Refresh content from database
     *
     * @return self|null
     */
    public function refresh() {
        /**
         * Obviously to refresh an isntance is necessary have an ID.
         * Otherwise it will return null
         */
        if ( $this->ID === 0 ) {
            return null;
        }

        // Retrieve the post updated as array
        $object = get_post( $this->ID, ARRAY_A );

        // Return null if it does not exists
        if ( ! $object ) {
            return null;
        }

        // Fill the wp fields first
        $this->fill( $object );

        // Now let us take a look on post meta fields
        if ( $this->data ) {
            foreach ( $this->data as $key => $value ) {
                $this->data[ $key ] = get_post_meta( $this->ID, $key, true );
            }
        }

        return $this;
    }

    /**
     * Update only a data using update_post_meta
     *
     * @param string $prop
     * @param mixed $value
     * @return bool|self
     */
    public function update_data( string $prop, $value ) {
        if ( $this->is_not_prop( $prop ) ) {
            return false;
        }

        update_post_meta( $this->ID, $prop, $value );

        return $this;
    }
}