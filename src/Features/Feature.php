<?php

namespace Kinja\Framework\Features;

use Kinja\Framework\Contracts\Instanciable;

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
     * Components that this feature will load
     *
     * @var array
     */
    public $components = array();

    /**
     * WordPress Actions Registered on this feature
     * 
     * It must has the next structure:
     * 
     * $_actions = array(
     *  array(
     *      'name'     => 'init',
     *      'action'   => MyActionClass::run,
     *      'params'   => 1,
     *      'priority' => 10
     *  )
     * );
     *
     * @var array<string, mixed>
     */
    protected $_actions = array();

    /**
     * WordPress Filters Registered on this feature
     * 
     * It must has the next structure:
     * 
     * $_filters = array(
     *  array(
     *      'name'     => 'the_content',
     *      'action'   => MyFilterClass::run,
     *      'params'   => 1,
     *      'priority' => 10
     *  )
     * );
     *
     * @var array<string, mixed>
     */
    protected $_filters = array();

    /**
	 * Run on every page load for feature to set itself up
	 *
	 * @since  2.1
	 */
    public function setup() : void {
        add_action( 'init', array( $this, 'init' ), 10 );
    }

    /**
     * Initialize
     *
     * @return void
     */
    public function init() : void {
        $this->registers();
        
        if ( $this->components ) {
            foreach ( $this->components as $component ) {
                $component->setup();
            }
        }

        $this->actions();
        $this->filters();

        $this->load_hooks();
    }

    /**
     * Add actions needed
     *
     * @return void
     */
    public function actions() : void {

    }

    /**
     * Add Filters needed
     *
     * @return void
     */
    public function filters() : void {

    }

    /**
     * Register Post Types, Etc...
     *
     * @return void
     */
    public function registers() : void {

    }

    /**
     * Register an action to be loaded then.
     *
     * @param string $name
     * @param string $action
     * @param integer $priority
     * @param integer $params
     * @return void
     */
    public function add_action( string $name, string $action, int $priority = 10, int $params = 1, ) : void {
        $this->_actions[] = array(
            'name'     => $name,
            'action'   => $action,
            'priority' => $priority,
            'params'   => $params
        );
    }   

    /**
     * Register a filter hook to be loaded then.
     *
     * @param string $name
     * @param string $filter
     * @param integer $priority
     * @param integer $params
     * @return void
     */
    public function add_filter( string $name, string $filter, int $priority = 10, int $params = 1 ) : void {
        $this->_filters[] = array(
            'name'     => $name,
            'action'   => $filter,
            'priority' => $priority,
            'params'   => $params
        );
    }

    /**
     * Loadd hooks registered before
     *
     * @return void
     */
    public function load_hooks() : void {
        if ( $this->_actions ) {
            foreach ( $this->_actions as $action ) {
                if ( ! method_exists( $action['action' ], 'run' )  ) {
                    continue;
                }

                add_action( $action['name'], array( $action['action'], 'run' ), $action['priority'], $action['params'] );
            }
        }

        if ( $this->_filters ) {
            foreach ( $this->_filters as $filter ) {
                if ( ! method_exists( $filter['filter' ], 'run' )  ) {
                    continue;
                }

                add_filter( $filter['name'], array( $filter['filter'], 'run' ), $filter['priority'], $filter['params'] );
            }
        }
    }

    /**
     * Regsiter a sub-feature or registrable class
     *
     * @param Instanciable $register
     * @return void
     */
    protected function register( Instanciable $register ) : void {
        $this->components[] = $register;
    }

	/**
	 * Is the feature valid?
	 *
	 * @return boolean
	 */
	abstract public function is_active() : bool;
}