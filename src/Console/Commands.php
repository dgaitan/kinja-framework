<?php

namespace Kinja\Framework\Console;

use Symfony\Component\Console\Application;

class Commands {

    /**
     * Return Core Commands
     *
     * @return array
     */
    public static function get_commands() : array {
        return array(
            HelloCommand::class,
            MakeFeatureCommand::class,
            MakeActionCommand::class,
            MakeFilterCommand::class,
            MakeModelCommand::class
        );
    }

    /**
     * Initialize Console
     *
     * @return void
     */
    public static function console(): void {
        // Fake WP
        if ( ! defined( 'ABSPATH' ) ) {
            define( 'ABSPATH', __DIR__ );
        }

        $application = new Application();

        foreach ( self::get_commands() as $command ) {
            $application->add( new $command() );
        }

        $application->run();
    }
}