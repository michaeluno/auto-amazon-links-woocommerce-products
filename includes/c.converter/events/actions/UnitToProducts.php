<?php

namespace AutoAmazonLinks\WooCommerceProducts\Converter\Events\Actions;

use AutoAmazonLinks\WooCommerceProducts\Commons\MemberInterface;
use AutoAmazonLinks\WooCommerceProducts\Commons\Utility;

/**
 * @since 0.1.0
 */
class UnitToProducts implements MemberInterface {

    use Utility;

    /**
     * @since 0.1.0
     * @var   \AmazonAutoLinks_Unit_Utility
     */
    public $oUtil;

    /**
     * @since 0.1.0
     * @var   \AmazonAutoLinks_UnitOutput_Base
     */
    public $oUnitOutput;

    /**
     * @since 0.1.0
     * @var   integer
     */
    public $iLatestUpdated = 0;

    /**
     * @since 0.1.0
     */
    public function run() {
        add_action( 'aal/wcp/converter/action/convert_unit_to_products', [ $this, 'replyToDoAction' ], 10, 2 );
    }

    /**
     * @since 0.1.0
     */
    public function replyToDoAction( $iUnitID, $bForce ) {

        $this->oUtil = new \AmazonAutoLinks_Unit_Utility;
        add_filter( 'aal_filter_products', [ $this, 'replyToCaptureUnitOutputObject' ], 1, 3 );
        $_aProducts  = apply_filters( 'aal_filter_output_products', [], [ 'id' => $iUnitID ] );
        remove_filter( 'aal_filter_products', [ $this, 'replyToCaptureUnitOutputObject' ], 1 );

        $_iPreviousUpdated = ( integer ) get_post_meta( $iUnitID, '_unit_to_wc_products_updated_time', true );
        update_post_meta( $iUnitID, '_unit_to_wc_products_updated_time', time() );  // temporarily store the current time to prevent from the UnitOutput class to schedule another simultaneously

        // Process conversion
        foreach( $_aProducts as $_aProduct ) {
            try {
                $this->___tryConvertUnitProductToWooCommerceProduct( $_aProduct );
            } catch ( \Exception $_oException ) {
                new \AmazonAutoLinks_Error( 'UNIT_TO_WCPRODUCTS_FAILURE', $_oException->getMessage(), $_aProduct );
            }
        }
        
        update_post_meta( $iUnitID, '_unit_to_wc_products_updated_time', $this->iLatestUpdated ? $this->iLatestUpdated : $_iPreviousUpdated );

    }
        /**
         * @since    0.1.0
         * @callback add_filter() aal_filter_products
         */
        public function replyToCaptureUnitOutputObject( $aProducts, $deprecated, $oUnitOutput ) {
            $oUnitOutput->bUnitToWCProductsProcessing = true;   // store a custom flag so that the UnitOutput class can ignore the calls made by this class
            $this->oUnitOutput = $oUnitOutput;
            return $aProducts;
        }

    /**
     * @since  0.1.0
     * @throws \Exception
     */
    private function ___tryConvertUnitProductToWooCommerceProduct( $aItem ) {

        if ( ! isset( $aItem[ 'ASIN' ] ) ) {
            throw new \Exception( 'ASIN is not set.' );
        }
        
        $_iThisUpdatedTime = ( integer ) $this->oUtil->getElement( $aItem, [ 'updated_date' ], 0 );
        
        $_sSKU             = $this->oUtil->getElement( $aItem, [ 'product_id' ], $this->oUtil->getElement( $aItem, [ 'ASIN' ] ) );
        $_iProductID       = wc_get_product_id_by_sku( $_sSKU );

        $_iLastUpdatedTime = ( integer ) get_post_meta( $_iProductID, '_updated_time', true );

        // There are cases that only one or a few of the products are updated and the rest doesn't need to update.
        if ( $_iLastUpdatedTime && $_iLastUpdatedTime >= $_iThisUpdatedTime ) {
            throw new \Exception( $aItem[ 'ASIN' ] . ' The product has not been updated yet. Last updated: ' . $_iLastUpdatedTime . ' This updated: ' . $_iThisUpdatedTime );
        }

        $_iProductID       = $this->getWooCommerceProductCreatedFromUnitProduct( $aItem, $_iProductID );
        if ( ! $_iProductID ) {
            throw new \Exception( $aItem[ 'ASIN' ] . ' Failed to create/update a product.' );
        }
        update_post_meta( $_iProductID, '_updated_time', $_iThisUpdatedTime );
        $this->iLatestUpdated = $_iThisUpdatedTime > $this->iLatestUpdated ? $_iThisUpdatedTime : $this->iLatestUpdated;

    }

}