<?php
namespace AutoAmazonLinks\WooCommerceProducts\Commons;

/**
 * @since 0.3.0
 */
trait Utility {

    /**
     * @since  0.3.0
     * @remark This does not store the `_updated_time` meta value as there are use cases which should avoid it.
     * @param  array   $aItem   A unit product.
     * @param  integer $iWCProductID    An existing WooCommerce product ID. If this is given, the existing product will be updated. Otherwise, a new product will be created.
     * @return integer The created WooCommerce product ID.
     */
    static public function getWooCommerceProductCreatedFromUnitProduct( array $aItem, $iWCProductID=0 ) {
        
        $_oUtil           = new \AmazonAutoLinks_Utility();
        $_sSKU            = $_oUtil->getElement( $aItem, [ 'product_id' ], $_oUtil->getElement( $aItem, [ 'ASIN' ] ) );
        $iWCProductID     = $iWCProductID ? $iWCProductID : wc_get_product_id_by_sku( $_sSKU );
        add_filter( 'woocommerce_new_product_data', [ __CLASS__, 'replyToGetNonNullContent' ] );

        try {
            
            $_oWCProduct      = new \WC_Product_Simple( $iWCProductID );
            $_oWCProduct->set_name( $aItem[ 'title' ] );
            $_oWCProduct->set_status( 'publish' );
            $_oWCProduct->set_catalog_visibility( 'visible' );
            $_oWCProduct->set_sold_individually( false );   // allow multiple quantities
            $_oWCProduct->set_downloadable( false );
            $_oWCProduct->set_virtual( true );
    
            // Product meta
            $_oWCProduct->set_sku( $_sSKU );
            $_oWCProduct->set_short_description( $_oUtil->getElement( $aItem, [ 'text_description' ] ) );
            $_oWCProduct->set_description( $_oUtil->getElement( $aItem, [ 'feature' ] ) );  // empty string for a default value to avoid `null`. null is not allowed for the `post_content` column
            $_sPriceProper     = self::getPriceAmountExtracted(
                $_oUtil->getElement(
                    $aItem,
                    [ 'price_amount' ], // for Product Search and PA-API units
                    $_oUtil->getElement( $aItem, [ 'formatted_price' ], '' )   // for Category units
                )
            );
            $_sPriceDiscounted = self::getPriceAmountExtracted(
                $_oUtil->getElement(
                    $aItem, [ 'discounted_price' ], // for PA-API units
                    $_oUtil->getElement( $aItem, [ 'discounted_price_amount' ], '' )    // for Product Search units @todo set a proper element
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
            $_dRating = ( ( integer ) $_oUtil->getElement( $aItem, [ 'rating' ] ) ) / 10;
            if ( $_dRating ) {
                $_oWCProduct->set_average_rating( $_dRating );
            }
            $_iNumberOfRatings = ( integer ) $_oUtil->getElement( $aItem, [ 'number_of_reviews' ] );
            if ( $_iNumberOfRatings ) {
                $_oWCProduct->set_review_count( $_iNumberOfRatings );
            }            
            
        } catch ( \Exception $_oException ) {
            remove_filter( 'woocommerce_new_product_data', [ __CLASS__, 'replyToGetNonNullContent' ] );
            return 0;    
        }

        $_iWCProductID = $_oWCProduct->save();
        if ( ! $_iWCProductID ) {
            remove_filter( 'woocommerce_new_product_data', [ __CLASS__, 'replyToGetNonNullContent' ] );
            return 0;
        }

        // Plugin specific post meta
        update_post_meta( $_iWCProductID, '_product_url', $aItem[ 'product_url' ] );
        update_post_meta( $_iWCProductID, '_thumbnail_url', $aItem[ 'thumbnail_url' ] );
        update_post_meta( $_iWCProductID, '_asin', $aItem[ 'ASIN' ] );

        self::___setCategoriesFromItem( $_iWCProductID, $aItem );
        remove_filter( 'woocommerce_new_product_data', [ __CLASS__, 'replyToGetNonNullContent' ] );
        return $_iWCProductID;
        
    }

        static private function ___setCategoriesFromItem( $iPostID, $aItem ) {
            foreach( array_values( \AmazonAutoLinks_Utility::getElementAsArray( $aItem, [ '_categories' ] ) ) as $_aCategories ) {
                self::___setCategoriesWithHierarchicalTerms( $iPostID, $_aCategories );
            }
        }
            static private function ___setCategoriesWithHierarchicalTerms( $iPostID, array $aTerms ) {
                $_aTermIDs = [];
                foreach( $aTerms as $_iDepth => $_sTerm ) {
                    $_aTermIDs[ $_iDepth ] = self::___createTermByDepth(
                        $_sTerm,
                        $_iDepth,
                        \AmazonAutoLinks_Utility::getElement( $_aTermIDs, [ $_iDepth - 1 ], 0 )
                    );
                }
                if ( empty( $_aTermIDs ) ) {
                    return;
                }
                wp_set_object_terms( $iPostID, $_aTermIDs, 'product_cat' );
            }
                static private function ___createTermByDepth( $sTerm, $iDepth, $iParentTermID ) {
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
                    return ( integer ) \AmazonAutoLinks_Utility::getElement( $_aoResult, [ 'term_id' ] );
                }

        /**
         * Avoids `null` to prevent WP errors.
         *
         * For unknown reasons, there are cases that the `description` and `short_description` properties for a WC_Product object are not retrieved properly
         * when inserting a new post. When it happens WordPress produces an error saying tht `post_content`/`post_excerpt` table column cannot be null.
         * This method avoids that error.
         *
         * @since    0.3.0
         * @param    array $aPostData
         * @return   array
         * @callback add_filter() woocommerce_new_product_data
         */
        static public function replyToGetNonNullContent( $aPostData ) {
            $aPostData[ 'post_content' ] = isset( $aPostData[ 'post_content' ] ) ? $aPostData[ 'post_content' ] : '';
            $aPostData[ 'post_excerpt' ] = isset( $aPostData[ 'post_excerpt' ] ) ? $aPostData[ 'post_excerpt' ] : '';
            return $aPostData;
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