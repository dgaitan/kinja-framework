<?php
/**
 * Main App Entry Point
 * 
 * @package doorstep
 */

namespace Kinja\Framework;

use Kinja\Framework\Concerns\Singletonable;
use Kinja\Framework\Contracts\Instanciable;
use Kinja\Framework\Features\Features;

defined( 'ABSPATH' ) || exit;

class Plugin implements Instanciable {
    
    use Singletonable;

    /**
     * Initialize Everything
     *
     * @return void
     */
    public function setup() : void {
    }
}