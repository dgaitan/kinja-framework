<?php

namespace Kinja\Framework\Concerns;

defined( 'ABSPATH' ) || exit;

trait AsAction {

    /**
     * @return static
     */
    public static function make() {
        static $instance = false;

		if ( ! $instance ) {
			$instance = new self();
		}

		return $instance;
    }

    /**
     * @see static::handle()
     * @param mixed ...$arguments
     * @return mixed
     */
    public static function run( ...$arguments ) {
        return static::make()->handle( ...$arguments );
    }
}