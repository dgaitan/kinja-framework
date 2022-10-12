<?php

namespace Kinja\Framework\Concerns;

defined( 'ABSPATH' ) || exit;

trait Singletonable {

    /**
	 * Return singleton instance of class
	 *
	 * @return object
	 * @since 2.1
	 */
	public static function instance() {
		static $instance = false;

		if ( ! $instance ) {
			$instance = new self();
			$instance->setup();
		}

		return $instance;
	}

    /**
     * Alias of get_instance()
     *
     * @return self
     */
    public static function load() : self {
        return self::instance();
    }
}