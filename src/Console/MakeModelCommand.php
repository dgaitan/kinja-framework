<?php

namespace Kinja\Framework\Console;

use Kinja\Framework\Support\Facades\Helpers;
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
class MakeModelCommand extends Command
{
    /**
     * Configure.
     */
    protected function configure()
    {
        parent::configure();
        $this->setName( 'make:model' );
        $this->setDescription( 
            'Generate a model for a Feature. Ie: php console make:model MyPostType --feature=MyFeature --slug=my-post-type --plural=My Post Type --singular=My Post Type --permalink=my-permalink --description=Example Post Type'
        );
        $this->setDefinition( new InputDefinition([
            new InputArgument( 'name', InputArgument::REQUIRED ),
            new InputOption( 'feature', 'f', InputOption::VALUE_REQUIRED ),
            new InputOption( 'slug', 's', InputOption::VALUE_REQUIRED ),
            new InputOption( 'plural', 'p', InputOption::VALUE_REQUIRED ),
            new InputOption( 'singular', 'sg', InputOption::VALUE_REQUIRED ),
            new InputOption( 'permalink', 'pm', InputOption::VALUE_REQUIRED ),
            new InputOption( 'description', 'd', InputOption::VALUE_REQUIRED )
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
        $slug = $input->getOption( 'slug' );
        $plural = $input->getOption( 'plural' );
        $feature = $input->getOption( 'feature' ); 
        $singular = Helpers::get_value( $input->getOption( 'singular' ), $plural );
        $permalink = Helpers::get_value( $input->getOption( 'permalink' ), $slug );
        $description = Helpers::get_value( $input->getOption( 'description' ), $plural );

        if ( ! $feature ) {
            $output->writeln('<error>Feature name is required. Use --feature flag to define the feature where this model will be attached.</error>');

            return Command::FAILURE;
        }

        if ( ! $plural ) {
            $output->writeln('<error>Plural name is required. Add --plular My Post Type for instance</error>');

            return Command::FAILURE;
        }

        if ( ! $slug ) {
            $output->writeln('<error>Slug is required. Use --slug my-post-type for instance.</error>');

            return Command::FAILURE;
        }

        try {
            $stub = file_get_contents( __DIR__ . '/stubs/model.stub' );   
            $stub = str_replace( '{{ namespace }}', $this->get_namespace( $feature ), $stub );
            $stub = str_replace( '{{ class }}', $name, $stub );
            $stub = str_replace( '{{ post_type_slug }}', $slug, $stub );
            $stub = str_replace( '{{ plural }}', $plural, $stub );
            $stub = str_replace( '{{ singular }}', $singular, $stub );
            $stub = str_replace( '{{ permalink }}', $permalink, $stub );
            $stub = str_replace( '{{ description }}', $description, $stub );
    
            $this->maybe_create_dir( $feature );
            file_put_contents( $this->get_file( $name, $feature ), $stub );
    
            $output->writeln(sprintf('<info>Model %s was created in %s</info>', $name, $feature));
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

        return sprintf( '%s\Features\%s\Models', KINJA_PLUGIN_NAMESPACE, $feature );
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

        $file = sprintf( '%s/Features/%s/Models/%s', KINJA_APP_PATH, $feature, $name );

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

        $dir = sprintf( '%s/Features/%s/Models', KINJA_APP_PATH, $feature );

        if ( ! is_dir( $dir ) ) {
            mkdir( $dir );
        }
    }
}