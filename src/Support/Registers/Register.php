<?php

namespace Kinja\Framework\Support\Registers;

use Kinja\Framework\Exceptions\NotImplementedException;

defined( 'ABSPATH' ) || exit;

abstract class Register {

    /**
     * Element Slug
     *
     * @var string
     */
    protected string $slug;

    /**
     * Element Plural Name
     *
     * @var string
     */
    protected string $plural;

    /**
     * Element Singular Name
     *
     * @var string
     */
    protected string $singular;

    /**
     * Description
     *
     * @var string
     */
    protected string $description;

    /**
     * Custom Permalink
     *
     * @var string
     */
    protected string $permalink;

    /**
     * Element Labels
     *
     * @var array
     */
    protected array $labels = array();

    /**
     * Element Rewrite Rules
     * 
     * @var array
     */
    protected array $rewrite = array();

    /**
     * Element Args
     *
     * @var array
     */
    protected array $args = array();

    /**
     * A Registerable class should have a constructor.
     *
     * @param string $slug
     * @param string $plural
     * @param string $singular
     * @param string $description
     * @param string $permalink
     */
    public function __construct(
        string $slug,
        string $plural,
        string $singular,
        string $description,
        string $permalink = ''
    ) {
        $this->slug = $slug;
        $this->plural = $plural;
        $this->singular = $singular;
        $this->description = $description;
        $this->permalink = ! $permalink ? $slug : $permalink;

        $this->define_labels();
        $this->define_rewrite();
        $this->define_args();
    }

    /**
     * Define Regsiter Labels.
     *
     * @return void
     */
    protected function define_labels() : void {
        throw new NotImplementedException(
            sprintf(
                'Register (%s) class must implement define_labels() method',
                self::class
            )
        );
    }

    /**
     * Define Regsiter Args.
     *
     * @return void
     */
    protected function define_args() : void {
        throw new NotImplementedException(
            sprintf(
                'Register (%s) class must implement define_args() method',
                self::class
            )
        );
    }

    /**
     * Define Regsiter Labels.
     *
     * @return void
     */
    protected function define_rewrite() : void {
        throw new NotImplementedException(
            sprintf(
                'Register (%s) class must implement define_rewrite() method',
                self::class
            )
        );
    }

    /**
     * Define Regsiter Labels.
     *
     * @return static
     */
    public function register() : self {
        throw new NotImplementedException(
            sprintf(
                'Register (%s) class must implement register() method',
                self::class
            )
        );
    }

    /**
     * Update an argument value
     *
     * @param string $key
     * @param mixed $value
     * @return self
     */
    public function set_argument( string $key, $value ) : self {
        $this->args[ $key ] = $value;

        return $this;
    }

    /**
     * Update an rewrite rule value
     *
     * @param string $key
     * @param mixed $value
     * @return self
     */
    public function set_rewrite_rule( string $key, $value ) : self {
        $this->rewrite[ $key ] = $value;

        return $this;
    }

    /**
     * Load Register
     *
     * @param string $slug
     * @param string $plural
     * @param string $singular
     * @param string $description
     * @param string $permalink
     * @return static
     */
    public static function load(
        string $slug,
        string $plural,
        string $singular,
        string $description,
        string $permalink = ''
    ) : self {
        return new static( $slug, $plural, $singular, $description, $permalink );
    }
}