<?php

namespace Kinja\Framework\Http;

defined( 'ABSPATH' ) || exit;

use Kinja\Framework\Concerns\Component;
use Kinja\Framework\Exceptions\NotImplementedException;
use Throwable;

abstract class Ajaxable extends Component {
    /**
     * Ajax Actions.
     * 
     * The format will be as;
     * 
     * 'my_action_name' => array( 'public', 'private' )
     *
     * @var array
     */
    protected $actions = array();

    /**
     * Actions Prefix.
     * 
     * It helps to have a prefix in actions to avoid confusing with existing 
     * actions.
     * 
     * So for instance, if we have foo, and we have an action registered as
     * "login", the real action registered in wp will be "foo_login".
     *
     * @var string
     */
    protected $actions_prefix = 'kinja';

    /**
     * WP Nonce Name
     *
     * @var string
     */
    protected $nonce_action_name = '_wpnonce';

    /**
     * WP Nonce Query Arg
     *
     * @var string
     */
    protected $nonce_query_arg = '_wpnonce';

    /**
     * INitialize the Ajax instance
     */
    public function __construct() {}

    /**
     * Define the ajax actions
     *
     * @return void
     */
    public function endpoints() : void {
        throw new NotImplementedException(
            sprintf(
                'endpoints method in %s needs to be implemented',
                static::class
            )
        );
    }

    /**
     * Regsiter Actions
     *
     * @return void
     */
    public function register_actions() : void {
        if ( ! $this->actions ) {
            return;
        }
        
        foreach ( $this->actions as $action => $visibility ) {
            if ( in_array( 'private', $visibility ) ) {
                add_action(
                    "wp_ajax_" . $this->get_action_name( $action ),
                    array( $this, 'process_request' )
                );
            }
            
            if ( in_array( 'public', $visibility ) ) {                
                add_action(
                    "wp_ajax_nopriv_" . $this->get_action_name( $action ),
                    array( $this, 'process_request' )
                );
            }
        }
    }

    /**
     * Process every request
     *
     * @return mixed
     */
    public function process_request() {
        if ( ! isset( $_REQUEST['action'] ) ) {
            return wp_send_json_error( array(
                'message' => 'Invalid request. Action does not exists'
            ) );
        }

        $action   = sanitize_text_field( $_REQUEST['action'] );
        $callback = $this->get_real_action_name( $action );

        try {
            // Execute the real callback
            return $this->{$callback}();
        } catch ( Throwable $e ) {
            return wp_send_json_error( array(
                'message' => $e->getMessage(),
            ) );
        }
    }

    /**
     * Return the real action name deleting the action prefix
     *
     * @param string $action_name
     * @return string
     */
    protected function get_real_action_name( string $action_name ) : string {
        $prefix      = $this->actions_prefix . '_';
        $action_name = explode( $prefix, $action_name );

        return end( $action_name );
    }

    /**
     * GEt the real action name to register
     *
     * @param string $action_name
     * @return string
     */
    protected function get_action_name( string $action_name ) : string {
        return $this->actions_prefix . '_' . $action_name;
    }
    
    /**
     * Register an action as public
     *
     * @param string $action_name
     * @return void
     */
    protected function as_public( string $action_name ) : void {
        $this->actions[ $action_name ] = array( 'public' );
    }

    /**
     * Register a private action
     *
     * @param string $action_name
     * @return void
     */
    protected function as_private( string $action_name ) : void {
        $this->actions[ $action_name ] = array( 'private' );
    }

    /**
     * Regsiiter an action as public an private
     *
     * @param string $action_name
     * @return void
     */
    protected function as_public_and_private( string $action_name ) : void {
        $this->actions[ $action_name ] = array( 'public', 'private' );
    }

    /**
     * Check ajax referer helper
     *
     * @param string|null $action
     * @param string|null $query_arg
     * @return void
     */
    public function check_ajax_referer( $action = null, $query_arg = null ) : void {
        $action    = is_null( $action ) ? $this->nonce_action_name : $action;
        $query_arg = is_null( $query_arg ) ? $this->nonce_query_arg : $query_arg;

        if ( check_admin_referer( $action, $query_arg, false ) ) {
            throw new Exception( 'Invalid or Expired Request' );
        }
    }

    /**
     * Initialize Ajax
     *
     * @return void
     */
    public function setup() : void {
        $this->endpoints();
        $this->register_actions();
    }
}