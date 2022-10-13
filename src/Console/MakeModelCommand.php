<?php

namespace Kinja\Framework\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;

/**
 * Command.
 */
class MakeFilterCommand extends Command
{
    /**
     * Configure.
     */
    protected function configure()
    {
        parent::configure();
        $this->setName( 'make:filter' );
        $this->setDescription( 'Generate a filter for a Feature' );
        $this->setDefinition( new InputDefinition([
            new InputArgument( 'name', InputArgument::REQUIRED ),
            new InputOption( 'feature', 'f', InputOption::VALUE_REQUIRED )
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
    protected function execute( InputInterface $input, OutputInterface $output ) {
        $name = $input->getArgument( 'name' );
        $feature = $input->getOption( 'feature' );

        if ( ! $feature ) {
            $output->writeln('<error>Feature name is required</error>');

            return Command::FAILURE;
        }

        if ( ! $name ) {
            $output->writeln('<error>Filter name is required</error>');

            return Command::FAILURE;
        }

        try {
            $stub = file_get_contents( __DIR__ . '/stubs/filter.stub' );   
            $stub = str_replace( '{{ namespace }}', $this->get_namespace( $feature ), $stub );
            $stub = str_replace( '{{ class }}', $name, $stub );
    
            $this->maybe_create_dir( $feature );
            file_put_contents( $this->get_file( $name, $feature ), $stub );
    
            $output->writeln(sprintf('<info>Filter %s was created in %s</info>', $name, $feature));
        } catch ( Throwable $e ) {
            $output->writeln(sprintf('<error>%s</error>', $e->getMessage()));
        }

        return Command::SUCCESS;
    }

    /**
     * Retrieve Namespace
     *
     * @param string $name
     * @return string
     */
    protected function get_namespace( string $feature ) : string {        
        if ( ! defined( 'KINJA_PLUGIN_NAMESPACE' ) ) {
            define( 'KINJA_PLUGIN_NAMESPACE', 'Kinja\Plugin' );
        }

        return sprintf( '%s\Features\%s\Filters', KINJA_PLUGIN_NAMESPACE, $feature );
    }

    /**
     * Retrieve FIle
     *
     * @param string $name
     * @return string
     */
    public function get_file( string $name, string $feature ) : string {
        if ( ! defined( 'KINJA_APP_PATH' ) ) {
            define( 'KINJA_APP_PATH', __DIR__ . '/Fixtures' );
        }

        $file = sprintf( '%s/Features/%s/Filters/%s', KINJA_APP_PATH, $feature, $name );

        return $file . '.php';
    }

    /**
     * Create dir if needed
     *
     * @param string $name
     * @return void
     */
    protected function maybe_create_dir( string $feature ) {
        if ( ! defined( 'KINJA_APP_PATH' ) ) {
            define( 'KINJA_APP_PATH', __DIR__ . '/Fixtures' );
        }

        $dir = sprintf( '%s/Features/%s/Filters', KINJA_APP_PATH, $feature );

        if ( ! is_dir( $dir ) ) {
            mkdir( $dir );
        }
    }
}