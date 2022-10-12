<?php
/**
 * Instanciable Contract
 * 
 * A WordPress Class should be isntanciable. 
 * 
 * Every class that runs actions in WordPress needs to be instanciable, and
 * a class should not load actions in the __constructor. It always should loads
 * hooks in the setup() class
 * 
 * @package doorstep
 */

namespace Kinja\Framework\Contracts;

defined( 'ABSPATH' ) || exit;

interface Instanciable {

    /**
     * Setup to laod hooks or whatever
     *
     * @return void
     */
    public function setup() : void;
}