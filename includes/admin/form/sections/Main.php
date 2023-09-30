<?php
namespace AutoAmazonLinks\WooCommerceProducts\Admin\Form\Sections;

/**
 * @since 1.1.0
 */
class Main extends \AmazonAutoLinks_AdminPage_Section_Base {

    /**
     * @since  1.1.0
     * @return array
     */
    protected function _getArguments() {
        return [
            'tab_slug'      => 'woocommerce',
            'section_id'    => 'woocommerce',
            'title'         => __( 'WooCommerce Products', 'auto-amazon-links-woocommerce-products' ),
            'description'   => array(
                __( 'Settings for WooCommerce Products', 'auto-amazon-links-woocommerce-products' ),
            ),
        ];
    }

}