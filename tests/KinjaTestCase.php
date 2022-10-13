<?php

namespace Kinja\Tests;

use PHPUnit\Framework\TestCase;

abstract class KinjaTestCase extends TestCase {

    protected function setUp(): void {
        parent::setUp();

        if ( ! defined( 'ABSPATH' ) ) {
            define( 'ABSPATH', dirname( __FILE__ ) );
        }
    }
}