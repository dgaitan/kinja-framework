<?php
/**
 * Main App Entry Point
 * 
 * @package doorstep
 */

namespace LC\DoorStep;

use Kinja\Framework\Concerns\Singletonable;
use Kinja\Framework\Contracts\Instanciable;
use Kinja\Framework\Features\Features;

defined( 'ABSPATH' ) || exit;

abstract class Plugin implements Instanciable {
    
    use Singletonable;

    /**
     * Initialize Everything
     *
     * @return void
     */
    public function setup() : void {
        // Features::factory()->register_feature( new Config );
        // Features::factory()->register_feature( new Requests );

        // require DOORSTEP_PLUGIN_PATH . '/includes/doorstep-functions.php';
    }
}