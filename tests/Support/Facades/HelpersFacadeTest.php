<?php

namespace Kinja\Tests\Support\Facades;

use Kinja\Framework\Support\Facades\Helpers;
use Kinja\Tests\KinjaTestCase;

class HelpersFacadeTest extends KinjaTestCase {

    public function test_get_value_helper_method(): void {
        $value = Helpers::get_value( null, 'default' );

        $this->assertEquals( 'default', $value );

        $value = Helpers::get_value( 1000, 'no found' );
        $this->assertEquals( 1000, $value );
    }
}