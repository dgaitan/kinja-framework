<?php

namespace Kinja\Framework\Database;

use Kinja\Framework\Contracts\Instanciable;
use Kinja\Framework\Database\Concerns\HasAttributes;
use Kinja\Framework\Database\Concerns\InteractsWithWP;

defined( 'ABSPATH' ) || exit;

abstract class Model implements Instanciable {

    use HasAttributes,
        InteractsWithWP;

    /**
     * Hook Prefix used for actions and filters
     *
     * @var string
     */
    protected $hook_prefix;

    /**
     * Original WP Post instance
     *
     * @var WP_Post
     */
    protected $post;

    /**
     * Initialize it
     *
     * @param array $attributes
     */
    public function __construct( $attributes = array() ) {
        if ( $attributes ) {
            $this->set_props( $attributes );
        }
    }

    /**
     * Convert data to an array
     *
     * @return array
     */
    public function to_array() : array {
        return array_merge(
            array_map( function ( $item ) {
                return array(
                    $item => $this->get_prop( $item ),
                );
            }, $this->wp_fields ),
            array_map( function ( $item ) {
                return array(
                    $item => $this->get_prop( $item ),
                );
            }, $this->data ),
        );
    }

    /**
     * Return Post Type Slug
     *
     * @return string
     */
    abstract public function get_post_type_slug() : string;

    /**
     * Setup to load the post type
     *
     * @return void
     */
    abstract public function setup() : void;
    
}