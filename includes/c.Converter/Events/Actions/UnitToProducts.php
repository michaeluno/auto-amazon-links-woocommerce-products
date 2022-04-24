<?php

namespace AutoAmazonLinks\WooCommerceProducts\Converter\Events\Actions;

use AutoAmazonLinks\WooCommerceProducts\Commons\MemberInterface;

/**
 * @since 0.1.0
 */
class UnitToProducts implements MemberInterface {

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
        add_action( 'aal/wcp/converter/action/convert_unit_to_products', [ $this, 'replyToDoAction' ] );
    }

    /**
     * @since 0.1.0
     */
    public function replyToDoAction( $iUnitID ) {
        $this->oUtil = new \AmazonAutoLinks_Unit_Utility;
        add_filter( 'aal_filter_products', [ $this, 'replyToCaptureUnitOutputObject' ], 1, 3 );
        $_aProducts  = apply_filters( 'aal_filter_output_products', [], [ 'id' => $iUnitID ] );
        remove_filter( 'aal_filter_products', [ $this, 'replyToCaptureUnitOutputObject' ], 1 );

        $_iPreviousUpdated = ( integer ) get_post_meta( $iUnitID, '_unit_to_wc_products_updated_time', true );
        update_post_meta( $iUnitID, '_unit_to_wc_products_updated_time', time() );  // temporarily store the current time to prevent from the UnitOutput class to schedule another simultaneously

        // Process conversion
        array_walk($_aProducts, [ $this, 'replyToConvertUnitToProducts' ] );
        update_post_meta( $iUnitID, '_unit_to_wc_products_updated_time', $this->iLatestUpdated ? $this->iLatestUpdated : $_iPreviousUpdated );

    }
        /**
         * @since 0.1.0
         */
        public function replyToCaptureUnitOutputObject( $aProducts, $deprecated, $oUnitOutput ) {
            $oUnitOutput->bUnitToWCProductsProcessing = true;   // store a custom flag so that the UnitOutput class can ignore the calls made by this class
            $this->oUnitOutput = $oUnitOutput;
            return $aProducts;
        }

    /**
     * @since 0.1.0
     */
    public function replyToConvertUnitToProducts( $aItem, $iIndex ) {

        if ( ! isset( $aItem[ 'ASIN' ] ) ) {
            return;
        }
        
        $_iThisUpdatedTime = ( integer ) $this->oUtil->getElement( $aItem, [ 'updated_date' ], 0 );
        
        $_sSKU             = $this->oUtil->getElement( $aItem, [ 'product_id' ], $this->oUtil->getElement( $aItem, [ 'ASIN' ] ) );
        $_iProductID       = wc_get_product_id_by_sku( $_sSKU );

        $_iLastUpdatedTime = ( integer ) get_post_meta( $_iProductID, '_updated_time', true );

        // There are cases that only one or a few of the products are updated and the rest doesn't need to update.
        if ( $_iLastUpdatedTime && $_iLastUpdatedTime >= $_iThisUpdatedTime ) {
            return;
        }

        $_oWCProduct       = new \WC_Product_Simple( $_iProductID );
        $_oWCProduct->set_name( $aItem[ 'title' ] );
        $_oWCProduct->set_status( 'publish' );
        $_oWCProduct->set_catalog_visibility( 'visible' );
        $_oWCProduct->set_sold_individually( false );   // allow multiple quantities
        $_oWCProduct->set_downloadable( false );
        $_oWCProduct->set_virtual( true );

        // Product meta
        $_oWCProduct->set_sku( $_sSKU );
        $_oWCProduct->set_short_description( $this->oUtil->getElement( $aItem, [ 'text_description' ] ) );
        $_oWCProduct->set_description( $this->oUtil->getElement( $aItem, [ 'feature' ] ) );
        $_sPriceProper     = $this->getPriceAmountExtracted(
            $this->oUtil->getElement(
                $aItem,
                [ 'price_amount' ], // for Product Search and PA-API units
                $this->oUtil->getElement( $aItem, [ 'formatted_price' ], '' )   // for Category units
            )
        );
        $_sPriceDiscounted = $this->getPriceAmountExtracted(
            $this->oUtil->getElement(
                $aItem, [ 'discounted_price' ], // for PA-API units
                $this->oUtil->getElement( $aItem, [ 'discounted_price_amount' ], '' )    // for Product Search units @todo set a proper element
            )
        );
        $_sPriceDisplay    = strlen( $_sPriceDiscounted ) ? $_sPriceDiscounted : $_sPriceProper;
        if ( strlen( $_sPriceDiscounted ) ) {
            $_oWCProduct->set_sale_price( $_sPriceDisplay );
        }
        if ( $_sPriceDisplay ) {
            $_oWCProduct->set_price( $_sPriceDisplay );
        }
        if ( strlen( $_sPriceProper ) ) {
            $_oWCProduct->set_regular_price( $_sPriceProper );
        }
        $_dRating = ( ( integer ) $this->oUtil->getElement( $aItem, [ 'rating' ] ) ) / 10;
        if ( $_dRating ) {
            $_oWCProduct->set_average_rating( $_dRating );
        }
        $_iNumberOfRatings = ( integer ) $this->oUtil->getElement( $aItem, [ 'number_of_reviews' ] );
        if ( $_iNumberOfRatings ) {
            $_oWCProduct->set_review_count( $_iNumberOfRatings );
        }

        $_iID = $_oWCProduct->save();
        if ( ! $_iID ) {
            return;
        }

        // Plugin specific post meta
        update_post_meta( $_iID, '_product_url', $aItem[ 'product_url' ] );
        update_post_meta( $_iID, '_thumbnail_url', $aItem[ 'thumbnail_url' ] );
        update_post_meta( $_iID, '_updated_time', $_iThisUpdatedTime );
        
        $this->___setCategoriesFromItem( $_iID, $aItem );
        
        $this->iLatestUpdated = $_iThisUpdatedTime > $this->iLatestUpdated ? $_iThisUpdatedTime : $this->iLatestUpdated;

    }
        private function ___setCategoriesFromItem( $iPostID, $aItem ) {
            foreach( array_values( $this->oUtil->getElementAsArray( $aItem, [ '_categories' ] ) ) as $_aCategories ) {
                $this->___setCategoriesWithHierarchalTerms( $iPostID, $_aCategories );
            }
        }
            private function ___setCategoriesWithHierarchalTerms( $iPostID, array $aTerms ) {
                $_aTermIDs    = [];
                foreach( $aTerms as $_iDepth => $_sTerm ) {
                    $_aTermIDs[ $_iDepth ] = $this->___createTermByDepth(
                        $_sTerm,
                        $_iDepth,
                        $this->oUtil->getElement( $_aTermIDs, [ $_iDepth - 1 ], 0 )
                    );
                }
                if ( empty( $_aTermIDs ) ) {
                    return;
                }
                wp_set_object_terms( $iPostID, $_aTermIDs, 'product_cat' );
            }
                private function ___createTermByDepth( $sTerm, $iDepth, $iParentTermID ) {
                    $_aoResult = wp_insert_term(
                      $sTerm, // the term
                      'product_cat', // the WooCommerce category taxonomy
                      array(
                          'slug'   => sanitize_title( $sTerm ) . ( $iDepth ? '-' . $iDepth : '' ),  // slugs shouldn't be auto-generated by WP Core because the same term labels can occur. So we distinguish terms with hierarchical depths.
                          'parent' => $iParentTermID,
                      )
                    );
                    if ( is_wp_error( $_aoResult ) && $_aoResult->get_error_code() === 'term_exists' ) {
                        return ( integer ) $_aoResult->get_error_data( 'term_exists' );
                    }
                    return ( integer ) $this->oUtil->getElement( $_aoResult, [ 'term_id' ] );
                }

    /**
     * Extracts the first-found comma-separated digits from a string.
     * Possible Cases:
     *  - discount + proper: <span class="amazon-prices"><span class="proper-price"><s>$3,610 ($361 / item)</s></span> <span class="offered-price">$2,796 ($280 / item)</span></span>
     *  - proper (regular):  <span class="amazon-prices"><span class="proper-price">$7,240</span></span>
     *  - range:             <span class="amazon-prices"><span>$200 - $1,096</span></span>
     * @param  string $sReadablePrice
     * @return string
     */
    static public function getPriceAmountExtracted( $sReadablePrice ) {
        preg_match( "/[0-9,.]+/", $sReadablePrice, $_aMatches );    // extracts the first occurrence of digits with comma
        return isset( $_aMatches[ 0 ] )
            ? $_aMatches[ 0 ]
            : '';
    }

}