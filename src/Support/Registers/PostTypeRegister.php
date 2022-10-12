<?php

namespace Kinja\Framework\Support\Registers;

defined( 'ABSPATH' ) || exit;

class PostTypeRegister extends Register {
    /**
     * Register The taxonomy.
     *
     * @return self
     */
    public function register() : self {
        add_action( 'init', array( $this, 'register_post_type' ), 20 );
        
        return $this;
    }
    
    /**
     * REgister Post Type
     *
     * @return void
     */
    public function register_post_type() : void {        
        register_post_type( $this->slug, $this->args );
    }

    /**
     * Define Regsiter Labels.
     *
     * @return void
     */
    protected function define_labels(): void {
        $this->labels = array(
            "name"                  => _x( $this->plural, "Post Type General Name", "alligator" ),
            "singular_name"         => _x( $this->singular, "Post Type Singular Name", "alligator" ),
            "menu_name"             => __( $this->plural, "alligator" ),
            "name_admin_bar"        => __( $this->plural, "alligator" ),
            "archives"              => __( $this->singular . " Archives", "alligator" ),
            "attributes"            => __( $this->singular . " Attributes", "alligator" ),
            "parent_item_colon"     => __( "Parent {$this->singular}:", "alligator" ),
            "all_items"             => __( "All " . $this->plural, "alligator" ),
            "add_new_item"          => __( "Add New {$this->singular}", "alligator" ),
            "add_new"               => __( "Add New", "alligator" ),
            "new_item"              => __( "New {$this->singular}", "alligator" ),
            "edit_item"             => __( "Edit {$this->singular}", "alligator" ),
            "update_item"           => __( "Update {$this->singular}", "alligator" ),
            "view_item"             => __( "View {$this->singular}", "alligator" ),
            "view_items"            => __( "View " . $this->plural, "alligator" ),
            "search_items"          => __( "Search {$this->singular}", "alligator" ),
            "not_found"             => __( "Not found", "alligator" ),
            "not_found_in_trash"    => __( "Not found in Trash", "alligator" ),
            "featured_image"        => __( "Featured Image", "alligator" ),
            "set_featured_image"    => __( "Set featured image", "alligator" ),
            "remove_featured_image" => __( "Remove featured image", "alligator" ),
            "use_featured_image"    => __( "Use as featured image", "alligator" ),
            "insert_into_item"      => __( "Insert into {$this->singular}", "alligator" ),
            "uploaded_to_this_item" => __( "Uploaded to this {$this->singular}", "alligator" ),
            "items_list"            => __( "Items list", "alligator" ),
            "items_list_navigation" => __( "Items list navigation", "alligator" ),
            "filter_items_list"     => __( "Filter " . $this->plural . " list", "alligator" ),
        );
    }

    /**
     * Define Regsiter Labels.
     *
     * @return void
     */
    protected function define_rewrite(): void {
        $this->rewrite = array(
            "slug"       => $this->permalink,
            "with_front" => true,
            "pages"      => true,
            "feeds"      => true,
        );
    }

    /**
     * Define Args
     *
     * @return void
     */
    protected function define_args(): void {
        $this->args = array(
            "label"                 => __( $this->plural, "alligator" ),
            "description"           => __( $this->description, "alligator" ),
            "labels"                => $this->labels,
            "supports"              => array( "title", "editor", "thumbnail" ),
            "hierarchical"          => false,
            "public"                => true,
            "show_ui"               => true,
            "show_in_menu"          => true,
            "menu_position"         => 5,
            "show_in_admin_bar"     => true,
            "show_in_nav_menus"     => true,
            "can_export"            => true,
            "has_archive"           => true,
            "exclude_from_search"   => false,
            "publicly_queryable"    => true,
            "rewrite"               => $this->rewrite,
            "capability_type"       => "page",
            "show_in_rest"          => true,
            "rest_base"             => $this->permalink,
        );
    }
}