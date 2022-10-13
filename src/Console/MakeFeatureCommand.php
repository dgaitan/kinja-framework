<?php

namespace Kinja\Framework\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Command.
 */
class MakeFeatureCommand extends Command
{
    /**
     * Configure.
     */
    protected function configure()
    {
        parent::configure();
        $this->setName( 'make:feature' );
        $this->setDescription( 'Generate A Base Feature Command' );
        $this->setDefinition( new InputDefinition([
            new InputArgument( 'name', InputArgument::REQUIRED )
        ]));
    }

    /**
     * Execute command.
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int integer 0 on success, or an error code
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument( 'name' );
        $stub = file_get_contents( __DIR__ . '/stubs/feature.stub' );   
        $stub = str_replace( '{{ namespace }}', $this->get_namespace( $name ), $stub );
        $stub = str_replace( '{{ class }}', $name, $stub );

        $this->maybe_create_dir( $name );
        file_put_contents( $this->get_file( $name ), $stub );

        return Command::SUCCESS;
    }

    /**
     * Retrieve Namespace
     *
     * @param string $name
     * @return string
     */
    protected function get_namespace( string $name ) : string {        
        if ( ! defined( 'KINJA_PLUGIN_NAMESPACE' ) ) {
            define( 'KINJA_PLUGIN_NAMESPACE', 'Kinja\Plugin' );
        }

        return sprintf( '%s\Features\%s\%s', KINJA_PLUGIN_NAMESPACE, $name, $name );
    }

    /**
     * Retrieve FIle
     *
     * @param string $name
     * @return string
     */
    public function get_file( string $name ) : string {
        if ( ! defined( 'KINJA_APP_PATH' ) ) {
            define( 'KINJA_APP_PATH', __DIR__ . '/Fixtures' );
        }

        $file = sprintf( '%s/Features/%s/%s', KINJA_APP_PATH, $name, $name );

        return $file . '.php';
    }

    /**
     * Create dir if needed
     *
     * @param string $name
     * @return void
     */
    protected function maybe_create_dir( string $name ) {
        if ( ! defined( 'KINJA_APP_PATH' ) ) {
            define( 'KINJA_APP_PATH', __DIR__ . '/Fixtures' );
        }

        $dir = sprintf( '%s/Features/%s/', KINJA_APP_PATH, $name );

        if ( ! is_dir( $dir ) ) {
            mkdir( $dir );
        }
    }
}