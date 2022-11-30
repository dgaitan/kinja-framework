<?php

namespace Kinja\Framework\Support;

defined( 'ABSPATH' ) || exit;

class View {
    
    /**
     * Temlate Path
     *
     * @var string
     */
    protected string $template_path;

    /**
     * Template to load 
     *
     * @var string
     */
    public string $template;

    /**
     * Build View Instance
     * 
     * @package alligator
     */
    public function __construct( string $template ) {
        $this->template_path = KINJA_PATH . '/templates/';
        $this->template      = $template;
    }

    /**
     * Get the full template to load
     *
     * @return string
     */
    public function get_template() : string {
        return $this->template_path . $this->template . '.php';
    }   

    /**
     * Render a TEmplate
     *
     * @param string $template
     * @param array $context
     * @return void
     */
    public static function render( string $template, array $context = array() ) {
        $template = new self( $template );

        if ( ! $template->exists() ) {
            return null;
        }

        // Convert array values in variables
        if ( $context ) {
            extract( $context );
        }

        require $template->get_template();
    }

    /**
     * Does the template file exists?
     *
     * @return bool
     */
    public function exists() {
        return file_exists( $this->get_template() );
    }
}