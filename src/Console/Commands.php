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
            HelloCommand::class
        );
    }

    /**
     * Initialize Console
     *
     * @return void
     */
    public static function console(): void {
        $application = new Application();

        foreach ( self::get_commands() as $command ) {
            $application->add(new $command());
        }

        $application->run();
    }
}