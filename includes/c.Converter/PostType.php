<?php

namespace AutoAmazonLinks\WooCommerceProducts\Converter;

/**
 * @since 0.1.0
 * @deprecated 0.1.0
 */
class PostType extends \AmazonAutoLinks_AdminPageFramework_PostType {

    /**
     * @since 0.1.0
     */
    public function setUp() {

        $this->setArguments(
            array(            // @see http://codex.wordpress.org/Function_Reference/register_post_type#Arguments
                'labels'                => array(
                    'name'                  => __( 'Unit to WooCommerce Product Converters', 'amazon-auto-links' ),
                    'singular_name'         => __( 'Unit to Product Converter', 'amazon-auto-links' ),
                    'menu_name'             => __( 'Unit to Products', 'amazon-auto-links' ),    // this changes the root menu name
                    'add_new'               => __( 'Add New', 'amazon-auto-links' ),
                    'add_new_item'          => __( 'Add New Unit to Product Converter', 'amazon-auto-links' ),
                    'edit'                  => __( 'Edit', 'amazon-auto-links' ),
                    'edit_item'             => __( 'Edit Unit to Product Converter', 'amazon-auto-links' ),
                    'new_item'              => __( 'New Unit to Product Converter', 'amazon-auto-links' ),
                    'view'                  => __( 'View', 'amazon-auto-links' ),
                    'view_item'             => __( 'View Unit to Product Converter', 'amazon-auto-links' ),
                    'search_items'          => __( 'Search Unit to Product Converters', 'amazon-auto-links' ),
                    'not_found'             => __( 'No unit to product converters found', 'amazon-auto-links' ),
                    'not_found_in_trash'    => __( 'No Unit to Product Converters Found in Trash', 'amazon-auto-links' ),
                    // 'parent'                => __( 'Parent Converter', 'amazon-auto-links' ),

                    // framework specific keys
                    'plugin_action_link'    => __( 'Unit to Product Converters', 'amazon-auto-links' ),
                ),

                // 'menu_position'         => 130,
                'supports'              => array(
                    'title',
                ),    // e.g. array( 'title', 'editor', 'comments', 'thumbnail' ),    // 'custom-fields'
                'taxonomies'            => array( '' ),
                'has_archive'           => false,
                'hierarchical'          => false,
                'show_admin_column'     => true,
                'exclude_from_search'   => true,   // Whether to exclude posts with this post type from front end search results.
                'publicly_queryable'    => false,  // Whether queries can be performed on the front end as part of parse_request().
                'show_in_nav_menus'     => false,
                'show_ui'               => true,
                'public'                => false,
                'show_in_menu'          => 'edit.php?post_type=' . \AmazonAutoLinks_Registry::$aPostTypes[ 'unit' ],
                'can_export'            => true,
                'submenu_order_manage'  => 15,
            )

        );

        if (  $this->_isInThePage() ) {

            $this->setAutoSave( false );
            $this->setAuthorTableFilter( false );
            add_filter( 'months_dropdown_results', '__return_empty_array' );

            add_filter( 'post_updated_messages', function( $aMessages ) {
                if ( get_post_type( $GLOBALS['post_ID'] ) !== $this->oProp->sPostType ) {
                    return $aMessages;
                }
                return array();
            } );

        }

        add_action( 'wp_before_admin_bar_render', function(){
            $GLOBALS[ 'wp_admin_bar' ]->remove_node( 'new-' . $this->oProp->sPostType );
        } );

    }

}