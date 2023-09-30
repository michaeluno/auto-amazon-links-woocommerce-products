<?php
namespace AutoAmazonLinks\WooCommerceProducts\Events\Actions;

use AutoAmazonLinks\WooCommerceProducts\Commons\MemberInterface;
use AutoAmazonLinks\WooCommerceProducts\Admin\Tabs\WooCommerce;

/**
 * Adds the `WooCommerce` tab to the Auto Amazon Links settings page
 * @since 1.1.0
 */
class SettingTab implements MemberInterface {

    public function run() {
        if ( ! is_admin() ) {
            return;
        }
        add_action( 'load_' . \AmazonAutoLinks_Registry::$aAdminPages[ 'main' ], [ $this, 'replyToLoad' ] );
    }

    public function replyToLoad( $oFactory ) {
        new WooCommerce( $oFactory, \AmazonAutoLinks_Registry::$aAdminPages[ 'main' ] );
    }

}