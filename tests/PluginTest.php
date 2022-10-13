<?php

namespace Kinja\Tests;

class PluginTest extends KinjaTestCase {

    public function test_plugin_instance_should_be_uniq(): void {
        $plugin = PluginSample::instance();

        $this->assertSame( $plugin, PluginSample::instance() );
    }
}