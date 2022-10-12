<?php

namespace Kinja\Framework\Database\Concerns;

use Exception;
use Kinja\Framework\Casts\ContentCast;
use Kinja\Framework\Casts\DateCast;
use Kinja\Framework\Casts\IntegerCast;
use Kinja\Framework\Casts\StringCast;

defined( 'ABSPATH' ) || exit;

trait HasAttributes {
    /**
     * WP Fields
     *
     * @var array
     */
    protected $wp_fields = array(
        'ID'             => 0,
        'post_title'     => '',
        'post_name'      => '',
        'post_content'   => '',
        'post_excerpt'   => '',
        'post_date'      => '',
        'post_status'    => 'publish',
        'post_password'  => '',
        'post_modified'  => '',
        'post_parent'    => 0,
        'menu_order'     => 0,
        'post_mime_type' => ''
    );

    /**
     * Custom WP Casts
     *
     * @var array
     */
    protected $wp_casts = array(
        'ID'             => IntegerCast::class,
        'post_title'     => StringCast::class,
        'post_name'      => StringCast::class,
        'post_content'   => ContentCast::class,
        'post_excerpt'   => ContentCast::class,
        'post_date'      => DateCast::class,
        'post_status'    => StringCast::class,
        'post_password'  => StringCast::class,
        'post_modified'  => DateCast::class,
        'post_parent'    => IntegerCast::class,
        'menu_order'     => IntegerCast::class,
        'post_mime_type' => StringCast::class
    );

    /**
     * Custom Data
     *
     * @var array
     */
    protected $data = array();

    /**
     * Required fields.
     * 
     * Helpful for validations
     *
     * @var array
     */
    protected $required_fields = array();

    /**
     * Field casting
     *
     * @var array
     */
    protected $casts = array();

    /**
     * Fill Attributes
     *
     * @param array $attributes
     * @return self
     */
    public function fill( array $attributes = array() ) : self {
        $this->set_props( $attributes );

        return $this;
    }

    /**
     * Set Properties to instance
     *
     * @param array $attributes
     * @return void
     */
    protected function set_props( array $attributes = array() ) {
        foreach ( $attributes as $key => $value ) {
            $this->set_prop( $key, $value );
        }
    }

    /**
     * Get a prop value
     *
     * @param string $prop
     * @return mixed
     */
    public function get_prop( string $prop ) {
        if ( $this->is_not_a_valid_prop( $prop ) ) {
            return null;
        }
        
        $cast  = $this->get_prop_cast( $prop );
        $value = $this->get_prop_value( $prop );

        return apply_filters(
            sprintf(
                '%s_get_%s_value',
                $this->hook_prefix,
                $prop
            ),
            $cast::make( $value )->get_value(),
            $this
        );
    }

    /**
     * Get value to store casted
     *
     * @param string $prop
     * @param mixed $value
     * @return void
     */
    public function value_to_store( string $prop, $value ) {
        $cast = $this->get_prop_cast( $prop );

        return $cast::make( $value )->set_value();
    }

    /**
     * Set a property
     *
     * @param string $prop
     * @param mixed $value
     * @return void
     */
    public function set_prop( string $prop, $value ) {
        if ( $this->is_wp_prop( $prop ) ) {
            $this->wp_fields[ $prop ] = $value;
        } else if ( $this->is_prop( $prop ) ) {
            $this->data[ $prop ] = $value;
        }
    }

    /**
     * Has the prop a custom cast
     *
     * @param string $prop
     * @return bool
     */
    protected function prop_has_cast( string $prop ) {
        return in_array( $prop, array_keys( $this->casts ) );
    }

    /**
     * Get a prop cast
     *
     * @param string $prop
     * @return Cast
     */
    protected function get_prop_cast( string $prop ) {
        if ( $this->is_wp_prop( $prop ) ) {
            $cast = $this->wp_casts[ $prop ];
        } else {
            $cast = $this->prop_has_cast( $prop )
                ? $this->casts[ $prop ]
                : StringCast::class;
        }
        
        return $cast;
    }

    /**
     * REturn the property value
     *
     * @param string $prop
     * @return mixed
     */
    protected function get_prop_value( string $prop ) {
        return $this->is_wp_prop( $prop )
            ? $this->wp_fields[ $prop ]
            : $this->data[ $prop ];
    }

    /**
     * Is The prop a valid prop?
     *
     * @param string $prop
     * @return boolean
     */
    public function is_prop( string $prop ) {
        return in_array( $prop, array_keys( $this->data ) );
    }

    /**
     * Is Wp Props?
     *
     * @param string $prop
     * @return boolean
     */
    protected function is_wp_prop( string $prop ) {
        return in_array( $prop, array_keys( $this->wp_fields ) );
    }

    /**
     * Is not this prop a valid property?
     *
     * @param string $prop
     * @return boolean
     */
    protected function is_not_prop( string $prop ) {
        return ! $this->is_prop( $prop );
    }

    /**
     * Is the prop a valid prop?
     *
     * @param string $prop
     * @return boolean
     */
    protected function is_valid_prop( string $prop ) {
        return $this->is_wp_prop( $prop ) || $this->is_prop( $prop );
    }

    /**
     * Is not a valid prop?
     *
     * @param string $prop
     * @return boolean
     */
    protected function is_not_a_valid_prop( string $prop ) {
        return ! $this->is_valid_prop( $prop );
    }

    /**
     * Get a property magically
     *
     * @param string $key
     * @return mixed
     */
    public function __get( string $key ) {
        return $this->get_prop( $key );
    }

    /**
     * Set A value magically
     *
     * @param string $key
     * @param mixed $value
     */
    public function __set( string $key, $value ) {
        $this->set_prop( $key, $value );
    }

    /**
     * Validate props
     *
     * @return void
     */
    public function validate_props() {
        if ( ! $this->required_fields ) {
            return;
        }

        $invalid_fields = array();
        foreach ( $this->required_fields as $field ) {
            if ( $this->is_wp_prop( $field ) && ! $this->wp_fields[ $field ] ) {
                $invalid_fields[] = $field;
            }

            if ( $this->is_prop( $field ) && ! $this->data[ $field ] ) {
                $invalid_fields[] = $field;
            }
        }

        if ( $invalid_fields ) {
            throw new Exception(
                sprintf(
                    'Fields are required: %s',
                    implode( ',', $invalid_fields )
                )
            );
        }
    }
}