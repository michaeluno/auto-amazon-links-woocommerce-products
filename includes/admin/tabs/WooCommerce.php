<?php

namespace AutoAmazonLinks\WooCommerceProducts\Admin\Tabs;

use AutoAmazonLinks\WooCommerceProducts\Admin\Form\Sections\Main;

/**
 * Adds the `WooCommerce` tab to the 'Settings' page of the loader plugin.
 * 
 * @since 1.1.0
 */
class WooCommerce extends \AmazonAutoLinks_AdminPage_Tab_Base {

    /**
     * @return void
     * @since  1.1.0
     */
    protected function _loadTab( $oFactory ) {
        new Main( $oFactory, \AmazonAutoLinks_Registry::$aAdminPages[ 'main' ] );
    }

    /**
     * @return array
     * @since  1.1.0
     */
    protected function _getArguments() {
        return array(
            'tab_slug'  => 'woocommerce',
            'title'     => __( 'WooCommerce', 'amazon-auto-links' ),
            'order'     => 200,
        );
    }

    /**
     * @return void
     * @since  1.1.0
     */
    protected function _doTab( $oFactory ) {
        echo "<div class='right-submit-button'>"
                . get_submit_button()
            . "</div>";
    }
    
}
