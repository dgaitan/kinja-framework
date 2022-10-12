<?php

namespace Kinja\Framework\Support\Registers;

defined( 'ABSPATH' ) || exit;

class TaxonomyRegister extends Register {
    /**
     * Object Types to laod this taxonomy
     *
     * @var array
     */
    protected $object_type = array();

    /**
     * Register The taxonomy.
     *
     * @return self
     */
    public function register() : self {
        add_action( 'init', array( $this, 'register_taxonomy' ), 11 );

        return $this;
    }

    /**
     * REgister Taxonomy
     *
     * @return void
     */
    public function register_taxonomy() : void {        
        register_taxonomy( $this->slug, $this->object_type, $this->args );
    }

    /**
     * Set Object Types
     *
     * @param array $types
     * @return self
     */
    public function set_object_types( array $types = array( 'post' ) ) : self {
        $this->object_type = $types;

        return $this;
    }

    /**
     * Define Regsiter Labels.
     *
     * @return void
     */
    protected function define_labels(): void {
        $this->labels = array(
            'name'                       => _x( $this->plural, 'Taxonomy General Name', 'alligator' ),
            'singular_name'              => _x( $this->singular, 'Taxonomy Singular Name', 'alligator' ),
            'menu_name'                  => __( $this->plural, 'alligator' ),
            'all_items'                  => __( 'All Items', 'alligator' ),
            'parent_item'                => __( 'Parent Item', 'alligator' ),
            'parent_item_colon'          => __( 'Parent Item:', 'alligator' ),
            'new_item_name'              => __( 'New Item Name', 'alligator' ),
            'add_new_item'               => __( 'Add New Item', 'alligator' ),
            'edit_item'                  => __( 'Edit Item', 'alligator' ),
            'update_item'                => __( 'Update Item', 'alligator' ),
            'view_item'                  => __( 'View Item', 'alligator' ),
            'separate_items_with_commas' => __( 'Separate items with commas', 'alligator' ),
            'add_or_remove_items'        => __( 'Add or remove items', 'alligator' ),
            'choose_from_most_used'      => __( 'Choose from the most used', 'alligator' ),
            'popular_items'              => __( 'Popular Items', 'alligator' ),
            'search_items'               => __( 'Search Items', 'alligator' ),
            'not_found'                  => __( 'Not Found', 'alligator' ),
            'no_terms'                   => __( 'No items', 'alligator' ),
            'items_list'                 => __( 'Items list', 'alligator' ),
            'items_list_navigation'      => __( 'Items list navigation', 'alligator' ),
        );
    }

    /**
     * Define Regsiter Labels.
     *
     * @return void
     */
    protected function define_rewrite(): void {
        $this->rewrite = array(
            'slug'         => $this->permalink,
            'with_front'   => true,
            'hierarchical' => false,
        );
    }

    /**
     * Define Args
     *
     * @return void
     */
    protected function define_args(): void {
        $this->args = array(
            'labels'                     => $this->labels,
            'hierarchical'               => false,
            'public'                     => true,
            'show_ui'                    => true,
            'show_admin_column'          => true,
            'show_in_nav_menus'          => true,
            'show_tagcloud'              => true,
            'rewrite'                    => $this->rewrite,
            'show_in_rest'               => true,
            'rest_base'                  => $this->permalink,
        );
    }
}