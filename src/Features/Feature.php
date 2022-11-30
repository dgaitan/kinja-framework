<?php

namespace Kinja\Framework\Features;

defined( 'ABSPATH' ) || exit;

abstract class Feature {
    /**
	 * Feature slug
	 *
	 * @var string
	 * @since  2.1
	 */
	public $slug;

	/**
	 * Feature pretty title
	 *
	 * @var string
	 * @since  2.1
	 */
	public $title;

    /**
     * Setup the feature
     *
     * @return void
     */
    abstract public function setup() : void;

	/**
	 * Is the feature valid?
	 *
	 * @return boolean
	 */
	abstract public function is_active() : bool;
}