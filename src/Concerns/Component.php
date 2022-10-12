<?php

namespace Kinja\Framework\Concerns;

use Kinja\Framework\Contracts\Instanciable;
use Kinja\Framework\Support\View;

defined( 'ABSPATH' ) || exit;

abstract class Component implements Instanciable {

    use Singletonable;

    /**
     * Render from templates
     *
     * @param string $template
     * @param array $context
     * @return string
     */
    public function render( string $template, array $context = array() ) {
        return View::render( $template, $context );
    }
}