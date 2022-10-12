<?php

namespace Kinja\Framework\Features;

use Kinja\Framework\Features\Feature;

defined( 'ABSPATH' ) || exit;

class Features {

    /**
     * Features list
     *
     * @var array
     */
    protected $features = array();

    /**
	 * Initiate class actions
	 *
	 * @since 2.1
	 */
	public function setup() {		
		// hooks order matters, make sure feature activation goes before features setup
		add_action( 'init', array( $this, 'setup_features' ), 0 );
	}

    /**
	 * Registers a feature for use in ElasticPress
	 *
	 * @param  Feature $feature An instance of the Feature class
	 * @since  1.0
	 * @return boolean
	 */
	public function register_feature( Feature $feature ) {
		$this->features[ $feature->slug ] = $feature;

		return true;
	}

    /**
     * Setup and Load Features
     *
     * @return void
     */
    public function setup_features() : void {
		foreach ( $this->features as $feature_slug => $feature ) {
			if ( $feature->is_active() ) {
				$feature->setup();
			}
		}
    }

    /**
	 * Return singleton instance of class
	 *
	 * @return object
	 * @since 2.1
	 */
	public static function factory() {
		static $instance = false;

		if ( ! $instance ) {
			$instance = new self();
			$instance->setup();
		}

		return $instance;
	}
}