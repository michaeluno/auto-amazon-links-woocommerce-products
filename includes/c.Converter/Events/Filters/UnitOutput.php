<?php
namespace AutoAmazonLinks\WooCommerceProducts\Converter\Events\Filters;

use AutoAmazonLinks\WooCommerceProducts\Commons\MemberInterface;

/**
 * Checks cache updates and if a cache has been renewed, this schedules a WP Cron task that converts unit products to WooCommerce products.
 * @since 0.1.0
 */
class UnitOutput implements MemberInterface {

    /**
     * @since 0.1.0
     */
    public function run() {
        add_filter( 'aal_filter_products', [ $this, 'replyToCheckUpdatedTime' ], 10, 3 );
    }

    /**
     * Get the last updated time stored in the post meta and compare it with the passed one.
     * If the passed time is newer, schedule a unit-to-wc-product conversion task.
     * @since 0.1.0
     */
    public function replyToCheckUpdatedTime( $aProducts, $deprecated, $oUnitOutput ) {

        // There are direct argument calls such shortcodes, widgets etc. and those don't have the unit ID argument.
        $_iUnitID = ( integer ) $oUnitOutput->oUnitOption->get( 'id' );
        if ( ! $_iUnitID ) {
            return $aProducts;
        }

        if ( ! $this->___shouldProceed( $oUnitOutput, $_iUnitID, $aProducts ) ) {
            return $aProducts;
        }

        $this->___scheduleUnitToProductsConversion( $_iUnitID );
        return $aProducts;

    }
        /**
         * @param  \AmazonAutoLinks_UnitOutput_Base $oUnitOutput
         * @param  integer $iUnitID
         * @param  array   $aProducts
         * @return boolean
         * @since  0.1.0
         */
        private function ___shouldProceed( $oUnitOutput, $iUnitID, $aProducts ) {

            if ( ! ( boolean ) get_post_meta( $iUnitID, '_unit_to_wc_products_enable', true ) ) {
                return false;
            }
            if ( ! empty( $oUnitOutput->bUnitToWCProductsProcessing ) ) {
                return false;
            }
            if ( ( boolean ) $oUnitOutput->oUnitOption->get( '_force_cache_renewal' ) ) {
                return true;
            }

            $_iLastUpdatedTime = ( integer ) get_post_meta( $iUnitID, '_unit_to_wc_products_updated_time', true );
            $_iUpdatedTime     = $this->___getLatestUpdatedTime( $aProducts );
            if ( $_iLastUpdatedTime && $_iLastUpdatedTime >= $_iUpdatedTime ) {
                return false;
            }
            return true;

        }
            /**
             * Retrieves the updated time from products array.
             * Note that each can have different time and this method picks the latest one.
             * @since  0.1.0
             * @param  array   $aProducts
             * @return integer
             */
            private function ___getLatestUpdatedTime( $aProducts ) {
                $_iUpdatedTimeLatest = 0;
                foreach( $aProducts as $_aProduct ) {
                    if ( ! isset( $_aProduct[ 'updated_date' ] ) ) {
                        continue;
                    }
                    $_iUpdatedTimeThis   = ( integer ) $_aProduct[ 'updated_date' ];
                    $_iUpdatedTimeLatest = $_iUpdatedTimeLatest < $_iUpdatedTimeThis
                        ? $_iUpdatedTimeThis
                        : $_iUpdatedTimeLatest;
                }
                return $_iUpdatedTimeLatest;
            }

        /**
         * @since 0.1.0
         * @param $iUnitID
         */
        private function ___scheduleUnitToProductsConversion( $iUnitID ) {
            $_aArguments = [ $iUnitID ];
            if ( wp_next_scheduled( 'aal/wcp/converter/action/convert_unit_to_products', $_aArguments ) ) {
                return;
            }
            wp_schedule_single_event( time(), 'aal/wcp/converter/action/convert_unit_to_products', $_aArguments );
        }

}